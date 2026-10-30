<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Bakery Admin',
                'email' => 'admin@bakery.com',
                'password' => 'admin123',
                'phone' => '0900000001',
                'role' => 'admin',
            ],
            [
                'name' => 'Nguyen Van An',
                'email' => 'customer@bakery.com',
                'password' => 'customer123',
                'phone' => '0900000002',
                'role' => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user,
            );
        }
    }
}
