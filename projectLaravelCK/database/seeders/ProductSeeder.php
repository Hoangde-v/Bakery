<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category' => 'Banh mi',
                'name' => 'Banh mi baguette',
                'description' => 'Banh mi vo gion, ruot mem, nuong moi moi ngay.',
                'price' => 18000,
                'stock_quantity' => 40,
                'status' => 'available',
            ],
            [
                'category' => 'Banh ngot',
                'name' => 'Croissant bo',
                'description' => 'Banh croissant nhieu lop voi huong bo thom.',
                'price' => 28000,
                'stock_quantity' => 25,
                'status' => 'available',
            ],
            [
                'category' => 'Banh kem',
                'name' => 'Banh kem dau tay',
                'description' => 'Banh gato kem tuoi va dau tay tuoi.',
                'price' => 320000,
                'stock_quantity' => 8,
                'status' => 'available',
            ],
            [
                'category' => 'Banh quy',
                'name' => 'Banh quy bo',
                'description' => 'Hop banh quy bo nho, nuong vang gion.',
                'price' => 65000,
                'stock_quantity' => 30,
                'status' => 'available',
            ],
        ];

        $now = now();

        foreach ($products as $product) {
            $categoryId = DB::table('categories')
                ->where('name', $product['category'])
                ->value('id');

            DB::table('products')->updateOrInsert(
                ['name' => $product['name']],
                [
                    'category_id' => (int) $categoryId,
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock_quantity' => $product['stock_quantity'],
                    'image' => null,
                    'status' => $product['status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }
}
