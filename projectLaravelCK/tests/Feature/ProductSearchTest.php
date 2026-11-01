<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_search_available_products_by_name(): void
    {
        $category = Category::create(['name' => 'Bánh ngọt']);
        $match = $this->createProduct($category, 'Croissant bơ');
        $this->createProduct($category, 'Bánh quy bơ');

        $this->get(route('customer.products.index', ['q' => 'Croissant']))
            ->assertOk()
            ->assertSee('Tìm bánh theo tên, mô tả hoặc danh mục')
            ->assertSee('product-search', false)
            ->assertSeeText($match->name)
            ->assertDontSeeText('Bánh quy bơ');
    }

    public function test_customer_can_search_products_by_category_or_description(): void
    {
        $category = Category::create(['name' => 'Bánh kem']);
        $cake = $this->createProduct($category, 'Bánh sinh nhật', 'Phủ kem tươi và dâu');

        $this->get(route('customer.products.index', ['q' => 'kem']))
            ->assertOk()
            ->assertSeeText($cake->name);

        $this->get(route('customer.products.index', ['q' => 'dâu']))
            ->assertOk()
            ->assertSeeText($cake->name);
    }

    public function test_search_does_not_show_unavailable_products_and_pagination_keeps_search(): void
    {
        $category = Category::create(['name' => 'Bánh mì']);

        for ($index = 1; $index <= 13; $index++) {
            $this->createProduct($category, "Bánh mì {$index}");
        }

        $unavailable = $this->createProduct($category, 'Bánh mì ngừng bán', null, 'unavailable');

        $response = $this->get(route('customer.products.index', ['q' => 'Bánh mì']))
            ->assertOk()
            ->assertDontSeeText($unavailable->name)
            ->assertSee('q=B%C3%A1nh%20m%C3%AC', false);

        $this->assertSame(13, $response->viewData('products')->total());
    }

    private function createProduct(Category $category, string $name, ?string $description = null, string $status = 'available'): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'description' => $description,
            'price' => 25000,
            'stock_quantity' => 5,
            'status' => $status,
        ]);
    }
}
