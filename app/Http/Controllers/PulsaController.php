<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class PulsaController extends Controller
{
    private function callApi(string $endpoint): ?array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => config('services.tripay_h2h.url') . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . config('services.tripay_h2h.api_key')],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_TIMEOUT        => 10,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error || !$response) return null;

        $decoded = json_decode($response, true);
        return (isset($decoded['success']) && $decoded['success']) ? $decoded : null;
    }

    public function page()
    {
        return view('beli-pulsa');
    }

    public function getPricelist()
    {
        $result = Cache::remember('tripay_pulsa_pricelist', 3600, function () {
            $markup = (int) config('services.tripay_h2h.markup', 0);

            // Fetch operators pulsa (category_id = 1)
            $operatorsRes = $this->callApi('/pembelian/operator');
            if (!$operatorsRes) {
                return ['error' => 'Gagal mengambil data operator'];
            }

            $operators = array_filter(
                $operatorsRes['data'],
                fn($o) => $o['pembeliankategori_id'] == 1 && $o['status'] == 1
            );
            // Index by id for quick lookup
            $operatorMap = [];
            foreach ($operators as $op) {
                $operatorMap[$op['id']] = $op;
            }

            // Fetch semua produk
            $productsRes = $this->callApi('/pembelian/produk');
            if (!$productsRes) {
                return ['error' => 'Gagal mengambil data produk'];
            }

            // Filter: hanya pulsa (category 1) + status aktif + operator dikenal
            $products = array_filter($productsRes['data'], fn($p) =>
                $p['pembeliankategori_id'] == 1 &&
                $p['status'] == 1 &&
                isset($operatorMap[$p['pembelianoperator_id']])
            );

            // Attach operator info + markup
            $products = array_map(function ($p) use ($operatorMap, $markup) {
                $op = $operatorMap[$p['pembelianoperator_id']];
                return [
                    'code'        => $p['code'],
                    'name'        => $p['product_name'],
                    'price'       => (int) $p['price'] + $markup,
                    'desc'        => $p['desc'] ?? '',
                    'operator_id' => $op['product_id'],
                    'operator'    => $op['product_name'],
                ];
            }, $products);

            // Build operator list for filter tabs
            $operatorList = array_values(array_map(fn($op) => [
                'id'   => $op['product_id'],
                'name' => $op['product_name'],
            ], $operators));

            return [
                'products'  => array_values($products),
                'operators' => $operatorList,
            ];
        });

        if (isset($result['error'])) {
            return response()->json(['success' => false, 'message' => $result['error']], 500);
        }

        return response()->json([
            'success'   => true,
            'products'  => $result['products'],
            'operators' => $result['operators'],
        ]);
    }
}
