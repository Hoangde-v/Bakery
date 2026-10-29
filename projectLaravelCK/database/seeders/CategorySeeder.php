<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $categories = [
            ['name' => 'Banh mi', 'description' => 'Cac loai banh mi tuoi moi ra lo.'],
            ['name' => 'Banh ngot', 'description' => 'Banh ngot dung kem va mut trai cay.'],
            ['name' => 'Banh kem', 'description' => 'Banh kem cho tiec va dip dac biet.'],
            ['name' => 'Banh quy', 'description' => 'Banh quy gion, phu hop dung cung tra.'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [...$category, 'created_at' => $now, 'updated_at' => $now],
            );
        }
    }
}
