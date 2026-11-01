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
        // Mỗi dòng mô tả một đơn hàng mẫu cùng thông tin người nhận và sản phẩm được đặt.
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
            [
                'user_email' => 'customer@bakery.com',
                'product_name' => 'Bánh mì que pate',
                'quantity' => 4,
                'unit_price' => 22000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000011',
                'shipping_address' => '12 Nguyễn Trãi, Quận 1, TP HCM',
                'note' => null,
                'status' => 'completed',
                'days_ago' => 1,
            ],
            [
                'user_email' => 'customer@bakery.com',
                'product_name' => 'Croissant trứng muối',
                'quantity' => 2,
                'unit_price' => 42000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000012',
                'shipping_address' => '12 Nguyễn Trãi, Quận 1, TP HCM',
                'note' => null,
                'status' => 'completed',
                'days_ago' => 5,
            ],
            [
                'user_email' => 'customer@bakery.com',
                'product_name' => 'Bánh kem sinh nhật dâu tây',
                'quantity' => 1,
                'unit_price' => 350000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000013',
                'shipping_address' => '12 Nguyễn Trãi, Quận 1, TP HCM',
                'note' => null,
                'status' => 'completed',
                'days_ago' => 12,
            ],
            [
                'user_email' => 'customer@bakery.com',
                'product_name' => 'Bánh quy hạt điều bơ',
                'quantity' => 3,
                'unit_price' => 75000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000014',
                'shipping_address' => '12 Nguyễn Trãi, Quận 1, TP HCM',
                'note' => null,
                'status' => 'completed',
                'days_ago' => 40,
            ],
            [
                'user_email' => 'customer@bakery.com',
                'product_name' => 'Bánh mì que pate',
                'quantity' => 5,
                'unit_price' => 22000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000015',
                'shipping_address' => '12 Nguyễn Trãi, Quận 1, TP HCM',
                'note' => null,
                'status' => 'completed',
                'days_ago' => 70,
            ],
        ];

        $now = now();

        foreach ($orders as $order) {
            // Quy đổi email khách hàng sang user_id để lưu quan hệ trong bảng orders.
            $userId = DB::table('users')
                ->where('email', $order['user_email'])
                ->value('id');

            // Quy đổi tên sản phẩm sang product_id thay vì lưu trùng chuỗi tên trong đơn hàng.
            $productId = DB::table('products')
                ->where('name', $order['product_name'])
                ->value('id');

            if (! $userId || ! $productId) {
                throw new \RuntimeException('Không tìm thấy người dùng hoặc sản phẩm để tạo đơn hàng mẫu.');
            }

            // Số điện thoại được dùng để nhận biết và cập nhật đơn hàng mẫu khi seed lại.
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
                    'created_at' => $now->copy()->subDays($order['days_ago'] ?? 0),
                    'updated_at' => $now,
                ],
            );
        }
    }
}
