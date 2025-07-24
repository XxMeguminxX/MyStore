<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan ini diimpor
use Illuminate\Support\Facades\Hash; // Tambahkan ini jika Anda menggunakan Hash::make()

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Erik',
            'email' => 'saputraerik042@gmail.com',
            'password' => Hash::make('sdncepoko1'), // Gunakan Hash::make()
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        // Anda bisa menambahkan lebih banyak pengguna di sini
        // User::factory()->count(10)->create(); // Contoh menggunakan factory untuk 10 pengguna dummy
    }
}