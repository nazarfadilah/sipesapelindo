<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (role 1)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 1,
        ]);

        // Admin (role 2)
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 2,
        ]);

        // Petugas1 (role 3)
        User::create([
            'name' => 'Petugas1',
            'email' => 'petugas1@example.com',
            'password' => Hash::make('password'),
            'role' => 3,
        ]);

        // Petugas4 (role 3)
        User::create([
            'name' => 'Petugas4',
            'email' => 'petugas4@example.com',
            'password' => Hash::make('password'),
            'role' => 3,
        ]);
    }
}
