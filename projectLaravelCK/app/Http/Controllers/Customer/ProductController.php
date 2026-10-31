<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with('category')
            ->where('status', 'available')
            ->latest()
            ->paginate(12);

        return view('customer.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        abort_unless($product->status === 'available', 404);

        return view('customer.products.show', [
            'product' => $product->load('category'),
        ]);
    }
}
