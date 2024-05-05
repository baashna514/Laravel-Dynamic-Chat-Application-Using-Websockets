<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Test User 1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Test User 2',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
