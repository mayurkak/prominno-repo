<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Main Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admins1@examples.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'mobile_no' => '9876543219',
        ]);

        // 2. Create 5 Professional Seller Records using a loop
        $skillsList = ['PHP', 'Laravel', 'Vue.js', 'React', 'MySQL', 'AWS', 'Docker', 'Python'];
        $countries = ['India', 'USA', 'UK', 'Canada', 'Germany'];

        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Seller User $i",
                'email' => "sellers$i@exam.com",
                'password' => Hash::make('seller123'),
                'role' => 'seller',
                'mobile_no' => '900000000' . $i,
                'country' => $countries[array_rand($countries)],
                'state' => 'State ' . $i,
                // array_rand picks random keys, we map them to values
                'skills' => array_intersect_key($skillsList, array_flip((array) array_rand($skillsList, 3))),
            ]);
        }
    }
}
