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
                'user_email' => '2@g.com',
                'product_name' => 'Banh mi baguette',
                'quantity' => 2,
                'unit_price' => 18000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000001',
                'shipping_address' => '12 Nguyen Trai, Quan 1, TP HCM',
                'note' => 'Giao buoi sang',
                'status' => 'completed',
            ],
            [
                'user_email' => '2@g.com',
                'product_name' => 'Croissant bo',
                'quantity' => 3,
                'unit_price' => 28000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000002',
                'shipping_address' => '12 Nguyen Trai, Quan 1, TP HCM',
                'note' => null,
                'status' => 'shipping',
            ],
            [
                'user_email' => '1@g.com',
                'product_name' => 'Banh kem dau tay',
                'quantity' => 1,
                'unit_price' => 320000,
                'recipient_name' => 'Bakery Admin',
                'recipient_phone' => '0910000003',
                'shipping_address' => '25 Le Loi, Quan 1, TP HCM',
                'note' => 'Viet chu Chuc mung sinh nhat',
                'status' => 'confirmed',
            ],
            [
                'user_email' => '2@g.com',
                'product_name' => 'Banh quy bo',
                'quantity' => 2,
                'unit_price' => 65000,
                'recipient_name' => 'Nguyen Van An',
                'recipient_phone' => '0910000004',
                'shipping_address' => '12 Nguyen Trai, Quan 1, TP HCM',
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
