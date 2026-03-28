<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'owner@waroongpintar.test'],
            [
                'name' => 'Owner Waroong Pintar',
                'phone' => '081234567890',
                'password' => 'password123',
                'status' => 'active',
            ]
        );

        if (!$user->hasRole('owner')) {
            $user->assignRole('owner');
        }
    }
}
