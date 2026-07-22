<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@calmay.com'],
            [
                'name'              => 'Admin',
                'email'             => 'admin@calmay.com',
                'password'          => Hash::make('admin'),
                'is_admin'          => true,
                'email_verified_at' => now(),
                'phone'             => null,
            ]
        );
    }
}
