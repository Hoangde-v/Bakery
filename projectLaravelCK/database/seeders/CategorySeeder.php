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
            ['name' => 'Bánh mì', 'description' => 'Các loại bánh mì tươi, mới ra lò mỗi ngày.'],
            ['name' => 'Bánh ngọt', 'description' => 'Bánh ngọt dùng kem và mứt trái cây.'],
            ['name' => 'Bánh kem', 'description' => 'Bánh kem cho tiệc và những dịp đặc biệt.'],
            ['name' => 'Bánh quy', 'description' => 'Bánh quy giòn, phù hợp dùng cùng trà.'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [...$category, 'created_at' => $now, 'updated_at' => $now],
            );
        }
    }
}
