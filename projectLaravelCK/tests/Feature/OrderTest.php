<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_place_order_and_stock_is_decremented(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $product = $this->createProduct(['stock_quantity' => 5]);

        $this->actingAs($customer)
            ->post(route('customer.orders.store', $product), [
                'quantity' => 2,
                'recipient_name' => 'Nguyen Van A',
                'recipient_phone' => '0900000000',
                'shipping_address' => '1 Nguyen Hue, Quan 1',
                'note' => 'Giao buoi sang',
            ])
            ->assertRedirect(route('customer.orders.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => '25000.00',
            'status' => 'pending',
        ]);
        $this->assertSame(3, $product->fresh()->stock_quantity);
    }

    public function test_customer_cannot_order_more_than_available_stock(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $product = $this->createProduct(['stock_quantity' => 1]);

        $this->actingAs($customer)
            ->from(route('customer.products.show', $product))
            ->post(route('customer.orders.store', $product), [
                'quantity' => 2,
                'recipient_name' => 'Nguyen Van A',
                'recipient_phone' => '0900000000',
                'shipping_address' => '1 Nguyen Hue, Quan 1',
            ])
            ->assertRedirect(route('customer.products.show', $product))
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $product->fresh()->stock_quantity);
    }

    public function test_customer_dashboard_shows_only_their_recent_orders(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $otherCustomer = User::factory()->create(['role' => 'user']);
        $product = $this->createProduct();

        $customerOrder = Order::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
            'recipient_name' => 'My Order Recipient',
            'recipient_phone' => '0900000000',
            'shipping_address' => 'My delivery address',
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);
        $otherOrder = Order::create([
            'user_id' => $otherCustomer->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
            'recipient_name' => 'Other Customer Recipient',
            'recipient_phone' => '0900000001',
            'shipping_address' => 'Other delivery address',
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);

        $this->actingAs($customer)
            ->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee('Đơn hàng của bạn')
            ->assertSeeText("#{$customerOrder->id}")
            ->assertDontSeeText("#{$otherOrder->id}");
    }

    public function test_admin_can_view_orders_and_cancelling_restores_stock_once(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'user']);
        $product = $this->createProduct(['stock_quantity' => 3]);
        $order = Order::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
            'recipient_name' => 'Nguyen Van A',
            'recipient_phone' => '0900000000',
            'shipping_address' => '1 Nguyen Hue, Quan 1',
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Nguyen Van A');

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), ['status' => 'cancelled'])
            ->assertRedirect(route('admin.orders.index'));

        $this->assertSame(5, $product->fresh()->stock_quantity);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), ['status' => 'cancelled'])
            ->assertRedirect(route('admin.orders.index'));

        $this->assertSame(5, $product->fresh()->stock_quantity);
    }

    public function test_admin_statistics_are_shown_on_order_management_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'user']);
        $product = $this->createProduct();

        Order::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
            'recipient_name' => 'Nguyen Van A',
            'recipient_phone' => '0900000000',
            'shipping_address' => '1 Nguyen Hue, Quan 1',
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Tổng sản phẩm')
            ->assertDontSee('Tổng đơn hàng')
            ->assertDontSee('Đơn chờ xác nhận');

        $this->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Tổng sản phẩm')
            ->assertSee('Tổng đơn hàng')
            ->assertSee('Đơn chờ xác nhận')
            ->assertSee('>1</p>', false);
    }

    public function test_order_navigation_buttons_are_not_added_to_the_main_user_and_admin_heroes(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($customer)
            ->get(route('customer.products.index'))
            ->assertOk();

        auth()->logout();
        $this->get(route('customer.products.index'))
            ->assertOk()
            ->assertDontSee('Tạo tài khoản')
            ->assertSee('Đăng ký');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Kiểm tra đơn hàng')
            ->assertSee('Quản lý đơn hàng');
    }

    private function createProduct(array $overrides = []): Product
    {
        $category = Category::create(['name' => 'Bánh test']);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Bánh ngọt test',
            'description' => 'Sản phẩm kiểm thử',
            'price' => 25000,
            'stock_quantity' => 5,
            'status' => 'available',
        ], $overrides));
    }
}
