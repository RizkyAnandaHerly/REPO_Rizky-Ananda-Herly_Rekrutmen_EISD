<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin ResiCycle',
            'email' => 'admin@resicycle.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Warga Demo',
            'email' => 'warga@resicycle.com',
            'password' => 'password',
            'role' => 'resident',
        ]);
    }
}
