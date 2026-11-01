<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_products_by_name_description_and_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cakeCategory = Category::create(['name' => 'Bánh kem']);
        $cookieCategory = Category::create(['name' => 'Bánh quy']);
        $cake = $this->createProduct($cakeCategory, 'Croissant dâu', 'Bánh phủ kem tươi');
        $this->createProduct($cookieCategory, 'Bánh quy bơ', 'Bánh giòn thơm');

        $this->actingAs($admin)
            ->get(route('admin.products.index', ['q' => 'Croissant']))
            ->assertOk()
            ->assertSeeText($cake->name)
            ->assertDontSeeText('Bánh quy bơ');

        $this->get(route('admin.products.index', ['q' => 'kem']))
            ->assertOk()
            ->assertSeeText($cake->name)
            ->assertDontSeeText('Bánh quy bơ');

        $this->get(route('admin.products.index', ['q' => 'tươi']))
            ->assertOk()
            ->assertSeeText($cake->name)
            ->assertDontSeeText('Bánh quy bơ');
    }

    private function createProduct(Category $category, string $name, string $description): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'description' => $description,
            'price' => 25000,
            'stock_quantity' => 5,
            'status' => 'available',
        ]);
    }
}
