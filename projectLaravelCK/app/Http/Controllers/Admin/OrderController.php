<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private const STATUSES = [
        'pending',
        'confirmed',
        'shipping',
        'completed',
        'cancelled',
    ];

    public function index()
    {
        $orders = Order::query()
            ->with(['user', 'product'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
        ]);

        DB::transaction(function () use ($data, $order) {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status === 'cancelled' && $data['status'] !== 'cancelled') {
                throw ValidationException::withMessages([
                    'status' => 'Đơn hàng đã hủy không thể chuyển sang trạng thái khác.',
                ]);
            }

            if ($lockedOrder->status !== 'cancelled' && $data['status'] === 'cancelled') {
                if (in_array($lockedOrder->status, ['shipping', 'completed'], true)) {
                    throw ValidationException::withMessages([
                        'status' => 'Không thể hủy đơn hàng đang giao hoặc đã hoàn thành.',
                    ]);
                }

                $product = Product::query()
                    ->whereKey($lockedOrder->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();
                $product->increment('stock_quantity', $lockedOrder->quantity);
            }

            $lockedOrder->update(['status' => $data['status']]);
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('status', "Đã cập nhật trạng thái đơn hàng #{$order->id}.");
    }
}
