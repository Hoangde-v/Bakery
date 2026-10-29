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
                'email' => '1@g.com',
                'password' => '1',
                'phone' => '0900000001',
                'role' => 'admin',
            ],
            [
                'name' => 'Nguyen Van An',
                'email' => '2@g.com',
                'password' => '2',
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
