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
        $categoryIds = DB::table('categories')
            ->whereIn('name', ['Bánh mì', 'Bánh ngọt', 'Bánh kem', 'Bánh quy'])
            ->pluck('id', 'name');

        $products = [
            ['category' => 'Bánh mì', 'name' => 'Bánh mì que pate', 'description' => 'Bánh mì que nướng giòn, nhân pate, đồ chua và bơ hành.', 'price' => 22000, 'stock_quantity' => 35, 'status' => 'available'],
            ['category' => 'Bánh mì', 'name' => 'Bánh mì chả cá Nha Trang', 'description' => 'Bánh mì giòn nóng kèm chả cá, rau sống và nước mắm gừng đặc trưng.', 'price' => 39000, 'stock_quantity' => 24, 'status' => 'available'],
            ['category' => 'Bánh mì', 'name' => 'Croissant trứng muối', 'description' => 'Bánh sừng bò nhiều lớp cùng trứng muối, phô mai và sốt bơ thơm.', 'price' => 42000, 'stock_quantity' => 18, 'status' => 'available'],
            ['category' => 'Bánh ngọt', 'name' => 'Mousse chanh dây', 'description' => 'Mousse mát lạnh vị chanh dây chua nhẹ, phủ lớp thạch trái cây.', 'price' => 38000, 'stock_quantity' => 16, 'status' => 'available'],
            ['category' => 'Bánh ngọt', 'name' => 'Bánh su kem vani', 'description' => 'Bánh su mềm xốp nhồi kem vani béo nhẹ, rắc đường bột lên trên.', 'price' => 16000, 'stock_quantity' => 42, 'status' => 'available'],
            ['category' => 'Bánh ngọt', 'name' => 'Bánh tart trứng Hong Kong', 'description' => 'Đế bánh giòn, nhân trứng vàng mịn và hương vanilla nhẹ nhàng.', 'price' => 18000, 'stock_quantity' => 30, 'status' => 'available'],
            ['category' => 'Bánh kem', 'name' => 'Bánh kem sinh nhật dâu tây', 'description' => 'Bánh gato vani hai tầng, kem tươi và dâu tây tươi, phù hợp tiệc sinh nhật 6-8 người.', 'price' => 350000, 'stock_quantity' => 6, 'status' => 'available'],
            ['category' => 'Bánh kem', 'name' => 'Bánh Red Velvet', 'description' => 'Red Velvet mềm mịn xen lớp cream cheese, trang trí đơn giản và sang trọng.', 'price' => 420000, 'stock_quantity' => 5, 'status' => 'available'],
            ['category' => 'Bánh kem', 'name' => 'Bánh tiramisu truyền thống', 'description' => 'Bánh tiramisu thấm cà phê, kem mascarpone và cacao nguyên chất.', 'price' => 280000, 'stock_quantity' => 8, 'status' => 'available'],
            ['category' => 'Bánh quy', 'name' => 'Bánh quy hạt điều bơ', 'description' => 'Hộp 250g bánh quy bơ giòn, topping hạt điều rang thơm, thích hợp làm quà.', 'price' => 75000, 'stock_quantity' => 22, 'status' => 'available'],
        ];

        $now = now();

        foreach ($products as $product) {
            $categoryId = $categoryIds->get($product['category']);

            if (! $categoryId) {
                throw new \RuntimeException("Không tìm thấy danh mục: {$product['category']}");
            }

            DB::table('products')->updateOrInsert(
                ['name' => $product['name']],
                [
                    'category_id' => $categoryId,
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