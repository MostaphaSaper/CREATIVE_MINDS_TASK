<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $user = User::create([
            'name' => 'User 1',
            'email' => 'User1@example.com',
            'phone' => '01111111111',
            'password' => bcrypt('12345678'),
        ]);
        $user = User::create([
            'name' => 'User 2',
            'email' => 'User2@example.com',
            'phone' => '01222222222',
            'password' => bcrypt('12345678'),
        ]);
        $user = User::create([
            'name' => 'User 3',
            'email' => 'User3@example.com',
            'phone' => '01333333333',
            'password' => bcrypt('12345678'),
        ]);
        $user = User::create([
            'name' => 'User 4',
            'email' => 'User4@example.com',
            'phone' => '01444444444',
            'password' => bcrypt('12345678'),
        ]);
        $user = User::create([
            'name' => 'User 5',
            'email' => 'User5@example.com',
            'phone' => '01555555555',
            'password' => bcrypt('12345678'),
        ]);
    }
}
