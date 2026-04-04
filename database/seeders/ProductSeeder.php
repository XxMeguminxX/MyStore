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
            'image' => '/assets/img/gmail.jpg',
            'name' => 'Akun Gmail Fresh 1 acc',
            'price' => 10000,
            'description' => 'Dapatkan Akun Gmail Fresh yang dirancang khusus untuk memenuhi kebutuhan email Anda! Setiap pembelian akan mendapatkan 1 akun yang sepenuhnya baru dan siap digunakan, Anda dapat memisahkan pekerjaan, personal, dan berbagai keperluan lain dengan mudah. Setiap akun dibuat untuk memberikan akses penuh dan aman ke berbagai layanan Google, termasuk Google Drive, Google Docs, dan banyak lagi.
Bergabunglah dengan ribuan pelanggan lain yang telah merasakan manfaat dari Akun Gmail Fresh 1acc! Ini adalah pilihan ideal bagi Anda yang menginginkan keamanan dan kemudahan dalam pengelolaan akun email.
Manfaat menggunakan Akun Gmail Fresh :
Jangan tunggu lebih lama lagi! Dapatkan Akun Gmail Fresh sekarang juga dan nikmati kemudahan dalam berkomunikasi. Klik tombol beli di bawah ini dan mulai manfaatkan keunggulan dari akun email terkini!

'
        ]);

        Product::create([
            'image' => '/assets/img/gmail.jpg',
            'name' => 'Akun Gmail Fresh 5 acc',
            'price' => 30000,
            'description' => 'Dapatkan Akun Gmail Fresh yang dirancang khusus untuk memenuhi kebutuhan email Anda! Setiap pembelian akan mendapatkan 1 akun yang sepenuhnya baru dan siap digunakan, Anda dapat memisahkan pekerjaan, personal, dan berbagai keperluan lain dengan mudah. Setiap akun dibuat untuk memberikan akses penuh dan aman ke berbagai layanan Google, termasuk Google Drive, Google Docs, dan banyak lagi.
Bergabunglah dengan ribuan pelanggan lain yang telah merasakan manfaat dari Akun Gmail Fresh 1acc! Ini adalah pilihan ideal bagi Anda yang menginginkan keamanan dan kemudahan dalam pengelolaan akun email.
Manfaat menggunakan Akun Gmail Fresh :
Jangan tunggu lebih lama lagi! Dapatkan Akun Gmail Fresh sekarang juga dan nikmati kemudahan dalam berkomunikasi. Klik tombol beli di bawah ini dan mulai manfaatkan keunggulan dari akun email terkini!

'
        ]);

    }
}