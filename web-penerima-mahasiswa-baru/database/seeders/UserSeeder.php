<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Admin',
            'email' => 'Admin@mhs.mdp.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'roles' => 'admin',
            'status' => '1',
        ]);

        User::create([
            'name' => 'User',
            'email' => 'User@mhs.mdp.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'status' => '1',
        ]);

    }
}
