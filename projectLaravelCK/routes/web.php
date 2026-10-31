<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;

Route::get('/', [CustomerProductController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/customer/products', [CustomerProductController::class, 'index'])
    ->name('customer.products.index');
Route::get('/customer/products/{product}', [CustomerProductController::class, 'show'])
    ->name('customer.products.show');

Route::middleware('auth')->group(function () {
    Route::delete('/account/delete', [AuthController::class, 'deleteAccount'])->name('account.delete');
    Route::post('/account/change-password', [AuthController::class, 'changePassword'])->name('account.changePassword');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/customer', function () {
        return view('dashboard.customer');
    })->name('customer.dashboard');
}); 

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    Route::resource('/admin/products', ProductController::class)
        ->names('admin.products')
        ->parameters(['products' => 'product']);
});
