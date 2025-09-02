<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReturnExpiredStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:return-expired {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Return stock from expired unpaid transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $this->info($dryRun ? 'DRY RUN MODE - No changes will be made' : 'PRODUCTION MODE - Changes will be applied');

        // Cari transaksi yang expired dan belum dibayar
        $expiredTransactions = Transaction::where('status', 'UNPAID')
            ->where('checkout_created_at', '<', now()->subHours(24)) // Expired dalam 24 jam
            ->whereNotNull('reserved_stock')
            ->with('product')
            ->get();

        $this->info("Found {$expiredTransactions->count()} expired transactions");

        $processed = 0;
        $errors = 0;

        foreach ($expiredTransactions as $transaction) {
            try {
                DB::transaction(function () use ($transaction, $dryRun, &$processed) {
                    // Lock produk untuk mencegah race condition
                    $product = $transaction->product;

                    if (!$product) {
                        Log::warning("Product not found for expired transaction", [
                            'transaction_id' => $transaction->id,
                            'product_id' => $transaction->product_id
                        ]);
                        return;
                    }

                    $product = $product->lockForUpdate()->find($product->id);

                    if (!$dryRun) {
                        // Kembalikan stock sesuai quantity yang dibeli
                        $transactionQuantity = $transaction->quantity ?? 1;
                        $product->stock += $transactionQuantity;
                        $product->save();

                        // Update transaksi sebagai expired
                        $transaction->status = 'EXPIRED';
                        $transaction->response = array_merge($transaction->response ?? [], [
                            'expired_at' => now(),
                            'stock_returned' => true,
                            'stock_returned_at' => now()
                        ]);
                        $transaction->save();
                    }

                    $transactionQuantity = $transaction->quantity ?? 1;
                    Log::info('Stock returned from expired transaction', [
                        'transaction_id' => $transaction->id,
                        'merchant_ref' => $transaction->merchant_ref,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity_returned' => $transactionQuantity,
                        'stock_before' => $product->stock - ($dryRun ? 0 : $transactionQuantity),
                        'stock_after' => $product->stock,
                        'dry_run' => $dryRun
                    ]);

                    $processed++;
                });
            } catch (\Exception $e) {
                $errors++;
                Log::error('Failed to return stock from expired transaction', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("Processed: {$processed} transactions");
        if ($errors > 0) {
            $this->error("Errors: {$errors} transactions failed to process");
        }

        return $errors > 0 ? 1 : 0;
    }
}
