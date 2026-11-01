<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $order = DB::transaction(function () use ($data, $product, $request) {
            $lockedProduct = Product::query()
                ->whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedProduct->status !== 'available') {
                throw ValidationException::withMessages([
                    'product' => 'Sản phẩm hiện không còn được bán.',
                ]);
            }

            if ($lockedProduct->stock_quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Số lượng đặt vượt quá số sản phẩm còn trong kho.',
                ]);
            }

            $lockedProduct->decrement('stock_quantity', $data['quantity']);

            return Order::create([
                'user_id' => $request->user()->id,
                'product_id' => $lockedProduct->id,
                'quantity' => $data['quantity'],
                'unit_price' => $lockedProduct->price,
                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],
                'shipping_address' => $data['shipping_address'],
                'note' => $data['note'] ?? null,
                'status' => 'pending',
                'payment_method' => 'cod',
            ]);
        });

        return redirect()
            ->route('customer.orders.index')
            ->with('status', "Đặt hàng thành công. Mã đơn hàng #{$order->id}.");
    }
}
