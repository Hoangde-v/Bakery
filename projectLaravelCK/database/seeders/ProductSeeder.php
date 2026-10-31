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
        // Lấy ID danh mục để chuyển tên danh mục trong dữ liệu mẫu thành khóa ngoại.
        $categoryIds = DB::table('categories')
            ->whereIn('name', ['Bánh mì', 'Bánh ngọt', 'Bánh kem', 'Bánh quy'])
            ->pluck('id', 'name');

        // Danh sách sản phẩm mẫu: mỗi dòng tương ứng một mặt hàng trong cửa hàng.
        $products = [
            // Nhóm Bánh mì.
            ['category' => 'Bánh mì', 'name' => 'Bánh mì que pate', 'description' => 'Bánh mì que nướng giòn, nhân pate, đồ chua và bơ hành.', 'price' => 22000, 'stock_quantity' => 35, 'status' => 'available'],
            ['category' => 'Bánh mì', 'name' => 'Bánh mì chả cá Nha Trang', 'description' => 'Bánh mì giòn nóng kèm chả cá, rau sống và nước mắm gừng đặc trưng.', 'price' => 39000, 'stock_quantity' => 24, 'status' => 'available'],
            ['category' => 'Bánh mì', 'name' => 'Croissant trứng muối', 'description' => 'Bánh sừng bò nhiều lớp cùng trứng muối, phô mai và sốt bơ thơm.', 'price' => 42000, 'stock_quantity' => 18, 'status' => 'available'],
            ['category' => 'Bánh mì', 'name' => 'Bánh mì thịt nướng sa tế', 'description' => 'Bánh mì giòn rụm nhân thịt nướng sa tế đậm vị, dưa leo và đồ chua.', 'price' => 35000, 'stock_quantity' => 28, 'status' => 'available'],
            ['category' => 'Bánh mì', 'name' => 'Bánh mì sandwich gà xông khói', 'description' => 'Bánh mì sandwich mềm cùng gà xông khói, rau xanh, trứng và sốt mayonnaise.', 'price' => 45000, 'stock_quantity' => 20, 'status' => 'available'],

            // Nhóm Bánh ngọt.
            ['category' => 'Bánh ngọt', 'name' => 'Mousse chanh dây', 'description' => 'Mousse mát lạnh vị chanh dây chua nhẹ, phủ lớp thạch trái cây.', 'price' => 38000, 'stock_quantity' => 16, 'status' => 'available'],
            ['category' => 'Bánh ngọt', 'name' => 'Bánh su kem vani', 'description' => 'Bánh su mềm xốp nhồi kem vani béo nhẹ, rắc đường bột lên trên.', 'price' => 16000, 'stock_quantity' => 42, 'status' => 'available'],
            ['category' => 'Bánh ngọt', 'name' => 'Bánh tart trứng Hong Kong', 'description' => 'Đế bánh giòn, nhân trứng vàng mịn và hương vanilla nhẹ nhàng.', 'price' => 18000, 'stock_quantity' => 30, 'status' => 'available'],
            ['category' => 'Bánh ngọt', 'name' => 'Bánh bông lan cuộn matcha', 'description' => 'Bánh bông lan cuộn mềm mịn, nhân kem matcha thơm nhẹ và ít ngọt.', 'price' => 48000, 'stock_quantity' => 18, 'status' => 'available'],
            ['category' => 'Bánh ngọt', 'name' => 'Brownie socola hạt óc chó', 'description' => 'Brownie socola mềm đậm vị, thêm hạt óc chó giòn và lớp sốt socola.', 'price' => 36000, 'stock_quantity' => 24, 'status' => 'available'],

            // Nhóm Bánh kem.
            ['category' => 'Bánh kem', 'name' => 'Bánh kem sinh nhật dâu tây', 'description' => 'Bánh gato vani hai tầng, kem tươi và dâu tây tươi, phù hợp tiệc sinh nhật 6-8 người.', 'price' => 350000, 'stock_quantity' => 6, 'status' => 'available'],
            ['category' => 'Bánh kem', 'name' => 'Bánh Red Velvet', 'description' => 'Red Velvet mềm mịn xen lớp cream cheese, trang trí đơn giản và sang trọng.', 'price' => 420000, 'stock_quantity' => 5, 'status' => 'available'],
            ['category' => 'Bánh kem', 'name' => 'Bánh tiramisu truyền thống', 'description' => 'Bánh tiramisu thấm cà phê, kem mascarpone và cacao nguyên chất.', 'price' => 280000, 'stock_quantity' => 8, 'status' => 'available'],
            ['category' => 'Bánh kem', 'name' => 'Bánh kem socola việt quất', 'description' => 'Bánh kem socola bông mịn kết hợp việt quất chua ngọt và trang trí quả tươi.', 'price' => 390000, 'stock_quantity' => 7, 'status' => 'available'],
            ['category' => 'Bánh kem', 'name' => 'Bánh kem matcha sữa dừa', 'description' => 'Bánh kem matcha Nhật kết hợp kem sữa dừa béo nhẹ, phù hợp tiệc thân mật.', 'price' => 360000, 'stock_quantity' => 6, 'status' => 'available'],

            // Nhóm Bánh quy.
            ['category' => 'Bánh quy', 'name' => 'Bánh quy hạt điều bơ', 'description' => 'Hộp 250g bánh quy bơ giòn, topping hạt điều rang thơm, thích hợp làm quà.', 'price' => 75000, 'stock_quantity' => 22, 'status' => 'available'],
            ['category' => 'Bánh quy', 'name' => 'Bánh quy yến mạch nho khô', 'description' => 'Hộp 250g bánh quy yến mạch giòn nhẹ cùng nho khô tự nhiên và hương vani.', 'price' => 68000, 'stock_quantity' => 26, 'status' => 'available'],
            ['category' => 'Bánh quy', 'name' => 'Bánh quy socola chip', 'description' => 'Hộp 250g bánh quy bơ giòn với nhiều mẩu socola đen tan chảy khi thưởng thức.', 'price' => 72000, 'stock_quantity' => 24, 'status' => 'available'],
        ];

        // Dùng cùng một thời điểm cho các bản ghi được thêm hoặc cập nhật trong lần seed này.
        $now = now();

        foreach ($products as $product) {
            $categoryId = $categoryIds->get($product['category']);

            // Dừng seed nếu dữ liệu danh mục chưa được tạo đầy đủ.
            if (! $categoryId) {
                throw new \RuntimeException("Không tìm thấy danh mục: {$product['category']}");
            }

            // Tìm sản phẩm theo tên: cập nhật nếu đã tồn tại, thêm mới nếu chưa có.
            DB::table('products')->updateOrInsert(
                ['name' => $product['name']],
                [
                    'category_id' => $categoryId,
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock_quantity' => $product['stock_quantity'],
                    // Chưa gắn ảnh trong seeder; quản trị viên có thể upload sau trong trang quản lý.
                    'image' => null,
                    'status' => $product['status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }
}