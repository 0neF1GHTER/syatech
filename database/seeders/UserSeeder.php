<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User Owner (Kamu / Super Admin)
        User::create([
            'name'     => 'Syakli (Owner)',
            'email'    => 'owner@syatech.com',
            'password' => Hash::make('owner123'),
            'role'     => 'owner',
        ]);

        // 2. User Admin
        User::create([
            'name'     => 'Admin SyaTech',
            'email'    => 'admin@syatech.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // 3. User Pengguna / Penjual Biasa
        User::create([
            'name'     => 'Penjual Lokal 1',
            'email'    => 'user@syatech.com',
            'password' => Hash::make('user123'),
            'role'     => 'user',
        ]);
    }
}