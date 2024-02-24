<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Muhamad Zakaria Saputra',
                'username' => 'zakariasptr',
                'email' => 'zakaria@example.com',
                'password' => '1234567890'
            ],
            [
                'name' => 'Hanifah Muslimah',
                'username' => 'hanifahmusl',
                'email' => 'hanifah@example.com',
                'password' => '1234567890'
            ],
            [
                'name' => 'Chipi Chapa',
                'username' => 'chipichapa',
                'email' => 'chipichapa@example.com',
                'password' => '1234567890'
            ],
            [
                'name' => 'Dubi Daba',
                'username' => 'dubidaba',
                'email' => 'dubidaba@example.com',
                'password' => '1234567890'
            ]
        ];

        foreach ($data as $datum) {
            User::create($datum);
        }
    }
}
