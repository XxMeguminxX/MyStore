<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateUsersPhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update semua user yang belum memiliki phone dengan nomor default
        User::whereNull('phone')->update(['phone' => '08123456789']);
        
        $this->command->info('Users phone updated successfully!');
    }
}
