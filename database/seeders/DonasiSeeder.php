<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donasi;

class DonasiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Tea Ice',
                'description' => "Buy me an iced tea",
                'image' => 'assets/img/tea.jpg',
                'is_active' => true,
                'amount' => 5000,
            ],
            [
                'title' => 'Fanta',
                'description' => "Buy me an Fanta",
                'image' => 'assets/img/fanta.jpg',
                'is_active' => true,
                'amount' => 10000,
            ],
            [
                'title' => 'Boba',
                'description' => "Buy me an Boba",
                'image' => 'assets/img/boba.jpg',
                'is_active' => true,
                'amount' => 15000,
            ],
            [
                'title' => 'Sandwich',
                'description' => "Buy me an Sandwich",
                'image' => 'assets/img/sandwich.jpg',
                'is_active' => true,
                'amount' => 25000,
            ],
            [
                'title' => 'Pizza',
                'description' => "Buy me an Pizza",
                'image' => 'assets/img/pizza.jpg',
                'is_active' => true,
                'amount' => 30000,
            ],
        ];

        foreach ($items as $item) {
            Donasi::create($item);
        }
    }
}