<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\PulsaTransaction;
use App\Services\TripayService;

class PulsaTransactionController extends Controller
{
    public function __construct(private TripayService $tripay) {}

    /**
     * POST /api/transaksi — Buat transaksi pulsa baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_code'   => 'required|string',
            'phone'          => 'required|string|min:9|max:15',
            'payment_method' => 'required|string',
            'customer_name'  => 'required|string|max:100',
            'customer_email' => 'required|email',
        ]);

        // --- 1. Ambil produk dari cache pricelist ---
        $pricelist = Cache::get('tripay_pulsa_pricelist');

        if (!$pricelist || isset($pricelist['error'])) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan, coba refresh halaman',
            ], 422);
        }

        $products    = $pricelist['products'] ?? [];
        $productCode = $request->product_code;
        $product     = collect($products)->firstWhere('code', $productCode);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        // --- 2. Generate merchant_ref ---
        $merchantRef = 'PULSA-' . strtoupper(uniqid());

        // --- 3. Simpan record awal ---
        $trx = PulsaTransaction::create([
            'merchant_ref'   => $merchantRef,
            'product_code'   => $product['code'],
            'product_name'   => $product['name'],
            'phone'          => $request->phone,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'amount'         => (int) $product['price'],
            'payment_method' => $request->payment_method,
            'status'         => 'UNPAID',
        ]);

        // --- 4. Build payload TriPay ---
        $merchantCode = config('services.tripay.merchant_code');
        $privateKey   = config('services.tripay.private_key');
        $amount       = (int) $product['price'];

        $payload = [
            'method'         => $request->payment_method,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->phone,
            'order_items'    => [
                [
                    'sku'      => $product['code'],
                    'name'     => $product['name'],
                    'price'    => $amount,
                    'quantity' => 1,
                ],
            ],
            'return_url'     => url('/payment/thank-you?merchant_ref=' . $merchantRef),
            'callback_url'   => url('/api/callback'),
            'expired_time'   => time() + (24 * 60 * 60),
            'signature'      => hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey),
        ];

        // --- 5. Call TripayService ---
        Log::info('PulsaTransaction: creating TriPay transaction', [
            'merchant_ref'   => $merchantRef,
            'product_code'   => $product['code'],
            'amount'         => $amount,
            'payment_method' => $request->payment_method,
        ]);

        $result = $this->tripay->createTransaction($payload);

        // --- 6. Jika gagal ---
        if (empty($result['success']) || !$result['success']) {
            Log::error('PulsaTransaction: TriPay createTransaction failed', [
                'merchant_ref' => $merchantRef,
                'result'       => $result,
            ]);
            $trx->delete();

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Gagal membuat transaksi pembayaran',
            ], 400);
        }

        // --- 7. Update record dengan data TriPay ---
        $data            = $result['data'];
        $paymentUrl      = $data['checkout_url'] ?? ($data['payment_url'] ?? null);
        $tripayReference = $data['reference'] ?? null;

        $trx->update([
            'payment_url'      => $paymentUrl,
            'tripay_reference' => $tripayReference,
            'tripay_response'  => $result,
        ]);

        Log::info('PulsaTransaction: transaction created successfully', [
            'merchant_ref'     => $merchantRef,
            'tripay_reference' => $tripayReference,
            'payment_url'      => $paymentUrl,
        ]);

        // --- 8. Return response ---
        return response()->json([
            'success' => true,
            'data'    => [
                'merchant_ref'   => $merchantRef,
                'payment_url'    => $paymentUrl,
                'pay_code'       => $data['pay_code'] ?? null,
                'payment_name'   => $data['payment_name'] ?? $request->payment_method,
                'amount'         => $amount,
                'product_name'   => $product['name'],
                'phone'          => $request->phone,
                'expired_time'   => $data['expired_time'] ?? null,
            ],
        ]);
    }

    /**
     * POST /api/callback — Handle payment callback dari TriPay.
     */
    public function callback(Request $request)
    {
        $rawBody   = $request->getContent();
        $signature = $request->header('X-Callback-Signature');
        $event     = $request->header('X-Callback-Event');

        // Log semua incoming data
        Log::info('Pulsa callback received', [
            'event'            => $event,
            'signature'        => $signature,
            'raw_body_length'  => strlen($rawBody),
            'raw_body'         => $rawBody,
        ]);

        // Abaikan event selain payment_status
        if (!empty($event) && strtolower($event) !== 'payment_status') {
            Log::info('Pulsa callback: ignoring non payment_status event', ['event' => $event]);
            return response()->json(['success' => true, 'message' => 'Event ignored']);
        }

        // Verifikasi signature
        if (!$this->tripay->verifyCallbackSignature($rawBody, (string) $signature)) {
            Log::warning('Pulsa callback: invalid signature', [
                'received'  => $signature,
                'raw_body'  => $rawBody,
            ]);
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        // Parse JSON
        $data = json_decode($rawBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Pulsa callback: JSON parse error', ['raw_body' => $rawBody]);
            return response()->json(['success' => false, 'message' => 'Invalid JSON'], 400);
        }

        // Cari transaksi
        $merchantRef = $data['merchant_ref'] ?? null;
        if (!$merchantRef) {
            Log::error('Pulsa callback: missing merchant_ref', ['data' => $data]);
            return response()->json(['success' => false, 'message' => 'Missing merchant_ref'], 400);
        }

        $trx = PulsaTransaction::where('merchant_ref', $merchantRef)->first();
        if (!$trx) {
            // Bukan pulsa transaction — mungkin milik TripayController, kembalikan 200 agar tidak retry
            Log::info('Pulsa callback: merchant_ref not found in pulsa_transactions, skipping', [
                'merchant_ref' => $merchantRef,
            ]);
            return response()->json(['success' => true, 'message' => 'Transaction not found in pulsa records']);
        }

        // Update status
        $oldStatus = $trx->status;
        $newStatus = $data['status'] ?? $oldStatus;

        $trx->status = $newStatus;
        $trx->save();

        Log::info('Pulsa callback: status updated', [
            'merchant_ref' => $merchantRef,
            'old_status'   => $oldStatus,
            'new_status'   => $newStatus,
        ]);

        // Jika baru dibayar
        if (in_array($newStatus, ['PAID', 'SETTLED']) && !in_array($oldStatus, ['PAID', 'SETTLED'])) {
            $trx->paid_at       = now();
            $trx->callback_data = $data;
            $trx->save();

            $this->processToSupplier($trx);
        }

        return response()->json(['success' => true]);
    }

    /**
     * GET /api/payment-channels — Ambil daftar metode pembayaran.
     */
    public function paymentChannels()
    {
        $channels = $this->tripay->getPaymentChannels();
        return response()->json($channels);
    }

    /**
     * Kirim order ke supplier (H2H) — implementasi dummy sementara.
     */
    private function processToSupplier(PulsaTransaction $trx): void
    {
        // TODO: Implement actual H2H topup via tripay.id
        Log::info('Pulsa topup queued to supplier', [
            'merchant_ref' => $trx->merchant_ref,
            'product_code' => $trx->product_code,
            'phone'        => $trx->phone,
            'amount'       => $trx->amount,
        ]);
        $trx->supplier_status = 'PROCESSING';
        $trx->save();
    }
}
