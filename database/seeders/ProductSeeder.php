<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'image' => 'https://i02.appmifile.com/563_item_id/25/06/2024/a215a7a1c96c92826ce2f7339d3169ec!800x800!85.png',
            'name' => 'POCO F6 12/512',
            'price' => 4612000,
            'description' => 'POCO F6 12/512
        - Platform Mobile Snapdragon 8s Gen 3 Unggulan
        - DotDisplay AMOLED Flow 6,67" CrystalRes 1,5K
        - CPU Prosesor Octa-core, hingga 3,0GHz
        1x X4@3.0GHz+4x A720@2.8GHz+3x A520@2.0GHz
        - GPU: GPU Adreno
        - AI: Qualcomm AI Engine
        - Refresh rate: Hingga 120 Hz
        - Kecerahan: 500 nit (umum), 1000-1200 nit (kecerahan HBM) , 2400 nit (kecerahan puncak)
        - Rasio kontras: 5.000.000:1
        - Resolusi: 2712 x 1220
        - PPI:446
        - Kamera utama 50 MP
        - Kamera ultra-lebar 8 MP
        - Kamera depan 20 MP
        - Baterai & Pengisian Daya 5000 mAh
        - Pengisian daya turbo 90 W
        - NFC
        - Sensor sidik jari di layar
        - Dual SIM, dual standby 5G+5G, 5G+4G
        - Didukung oleh Xiaomi HyperOS'
        ]);
    }
}