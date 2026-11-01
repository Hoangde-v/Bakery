<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_daily_report_and_cancelled_orders_are_not_counted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->createProduct();
        $this->createOrder($product, 'completed', 2, now());
        $this->createOrder($product, 'cancelled', 8, now());

        $this->actingAs($admin)
            ->get(route('admin.reports.index', ['mode' => 'daily', 'date' => now()->toDateString()]))
            ->assertOk()
            ->assertSee('50.000 đ')
            ->assertSee('Đơn hàng hoàn thành')
            ->assertSee('Bánh test')
            ->assertSee('Xuất báo cáo CSV');
    }

    public function test_admin_can_filter_report_by_month(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->createProduct();
        $this->createOrder($product, 'completed', 3, now()->subMonth());

        $this->actingAs($admin)
            ->get(route('admin.reports.index', ['mode' => 'monthly', 'month' => now()->subMonth()->format('Y-m')]))
            ->assertOk()
            ->assertSee('75.000 đ');
    }

    public function test_admin_can_download_csv_report(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->createProduct();
        $this->createOrder($product, 'completed', 1, now());

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.index', [
                'mode' => 'daily',
                'date' => now()->toDateString(),
                'export' => 1,
            ]));

        $this->assertStringContainsString(
            '.csv',
            $response->headers->get('content-disposition'),
        );
        $response->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_non_admin_cannot_open_admin_report(): void
    {
        $customer = User::factory()->create(['role' => 'user']);

        $this->actingAs($customer)
            ->get(route('admin.reports.index'))
            ->assertForbidden();
    }

    private function createProduct(): Product
    {
        $category = Category::create(['name' => 'Danh mục test']);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Bánh test',
            'price' => 25000,
            'stock_quantity' => 10,
            'status' => 'available',
        ]);
    }

    private function createOrder(Product $product, string $status, int $quantity, $createdAt): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $order = Order::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'recipient_name' => 'Khach hang test',
            'recipient_phone' => '0900000000',
            'shipping_address' => 'Dia chi test',
            'status' => $status,
            'payment_method' => 'cod',
        ]);
        $order->created_at = $createdAt;
        $order->save();
    }
}
