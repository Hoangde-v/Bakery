<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'user_email' => 'customer@bakery.com',
                'product_name' => 'Bánh mì que pate',
                'quantity' => 2,
                'unit_price' => 22000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000001',
                'shipping_address' => '12 Nguyễn Trãi, Quận 1, TP HCM',
                'note' => 'Giao buoi sang',
                'status' => 'completed',
            ],
            [
                'user_email' => 'customer@bakery.com',
                'product_name' => 'Croissant trứng muối',
                'quantity' => 3,
                'unit_price' => 42000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000002',
                'shipping_address' => '12 Nguyễn Trãi, Quận 1, TP HCM',
                'note' => null,
                'status' => 'shipping',
            ],
            [
                'user_email' => 'admin@bakery.com',
                'product_name' => 'Bánh kem sinh nhật dâu tây',
                'quantity' => 1,
                'unit_price' => 350000,
                'recipient_name' => 'Bakery Admin',
                'recipient_phone' => '0910000003',
                'shipping_address' => '25 Lê Lợi, Quận 1, TP HCM',
                'note' => 'Viết chữ Chúc mừng sinh nhật',
                'status' => 'confirmed',
            ],
            [
                'user_email' => 'customer@bakery.com',
                'product_name' => 'Bánh quy hạt điều bơ',
                'quantity' => 2,
                'unit_price' => 75000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000004',
                'shipping_address' => '12 Nguyễn Trãi, Quận 1, TP HCM',
                'note' => null,
                'status' => 'pending',
            ],
        ];

        $now = now();

        foreach ($orders as $order) {
            $userId = DB::table('users')
                ->where('email', $order['user_email'])
                ->value('id');
            $productId = DB::table('products')
                ->where('name', $order['product_name'])
                ->value('id');

            if (! $userId || ! $productId) {
                throw new \RuntimeException('Không tìm thấy người dùng hoặc sản phẩm để tạo đơn hàng mẫu.');
            }

            DB::table('orders')->updateOrInsert(
                ['recipient_phone' => $order['recipient_phone']],
                [
                    'user_id' => (int) $userId,
                    'product_id' => (int) $productId,
                    'quantity' => $order['quantity'],
                    'unit_price' => $order['unit_price'],
                    'recipient_name' => $order['recipient_name'],
                    'shipping_address' => $order['shipping_address'],
                    'note' => $order['note'],
                    'status' => $order['status'],
                    'payment_method' => 'cod',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }
}
