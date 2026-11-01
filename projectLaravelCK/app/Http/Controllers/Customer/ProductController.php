<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['q'] ?? '');

        $productQuery = Product::query()
            ->with('category')
            ->where('status', 'available');

        if ($search !== '') {
            $productQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $products = $productQuery
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('customer.products.index', compact('products', 'search'));
    }

    public function show(Product $product)
    {
        abort_unless($product->status === 'available', 404);

        return view('customer.products.show', [
            'product' => $product->load('category'),
        ]);
    }
}
