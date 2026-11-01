<?php

use App\Http\Controllers\AuthController;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;

// Trang chủ và danh sách sản phẩm dành cho khách hàng.
Route::get('/', [CustomerProductController::class, 'index'])->name('home');

// Các route xử lý đăng nhập, đăng ký và đăng xuất.
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Khách hàng xem danh sách sản phẩm và chi tiết từng sản phẩm.
Route::get('/customer/products', [CustomerProductController::class, 'index'])
    ->name('customer.products.index');
Route::get('/customer/products/{product}', [CustomerProductController::class, 'show'])
    ->name('customer.products.show');

// Các chức năng tài khoản cần người dùng đăng nhập.
Route::middleware('auth')->group(function () {
    Route::delete('/account/delete', [AuthController::class, 'deleteAccount'])->name('account.delete');
    Route::post('/account/change-password', [AuthController::class, 'changePassword'])->name('account.changePassword');
});

// Trang tổng quan dành cho tài khoản có vai trò khách hàng.
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/customer', function () {
        $orders = Order::query()
            ->with('product')
            ->where('user_id', request()->user()->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.customer', compact('orders'));
    })->name('customer.dashboard');

    Route::get('/customer/orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders.index');
    Route::post('/customer/products/{product}/orders', [CustomerOrderController::class, 'store'])
        ->name('customer.orders.store');
});

// Các chức năng quản trị yêu cầu đăng nhập và có vai trò quản trị viên.
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard.admin', [
            'productCount' => Product::count(),
            'orderCount' => Order::count(),
            'pendingOrderCount' => Order::where('status', 'pending')->count(),
        ]);
    })->name('admin.dashboard');

    // Tạo đầy đủ các route CRUD cho quản lý sản phẩm.
    Route::resource('/admin/products', ProductController::class)
        ->names('admin.products')
        ->parameters(['products' => 'product']);

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders.index');
    Route::patch('/admin/orders/{order}', [AdminOrderController::class, 'update'])
        ->name('admin.orders.update');
});
