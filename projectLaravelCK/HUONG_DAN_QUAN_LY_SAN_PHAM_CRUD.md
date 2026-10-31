# Hướng dẫn xây dựng chức năng quản lý sản phẩm CRUD

Tài liệu này hướng dẫn cách tự triển khai các chức năng:

- Xem danh sách sản phẩm.
- Xem chi tiết sản phẩm.
- Thêm sản phẩm.
- Sửa sản phẩm.
- Xóa sản phẩm.

Không thực hiện thay đổi nào trực tiếp vào code của dự án.

## 1. Điều kiện trước khi làm

Chuyển đến thư mục dự án:

```powershell
cd D:\Laravel\Bakery\projectLaravelCK
```

Dự án đã có migration `products`, seeder và bảng dữ liệu liên quan:

- `database/migrations/2026_10_29_175005_create_products_table.php`
- `database/seeders/ProductSeeder.php`
- `database/seeders/CategorySeeder.php`
- `database/migrations/2026_10_29_175014_create_orders_table.php`

Các trường quan trọng của bảng `products`:

```text
id
category_id
name
description
price
stock_quantity
image
status
created_at
updated_at
```

Chạy migration và seed dữ liệu nếu database chưa có dữ liệu:

```powershell
php artisan migrate
php artisan db:seed
```

### 1.1. Thêm 10 dữ liệu sản phẩm mẫu

Dữ liệu mẫu chỉ cần thêm vào mảng trong `database/seeders/ProductSeeder.php`, sau khi đã seed danh mục trong `CategorySeeder.php`.

```php
public function run(): void
{
    $categories = [
        'Bánh mì' => Category::where('name', 'Bánh mì')->firstOrFail()->id,
        'Bánh ngọt' => Category::where('name', 'Bánh ngọt')->firstOrFail()->id,
        'Bánh kem' => Category::where('name', 'Bánh kem')->firstOrFail()->id,
        'Bánh quy' => Category::where('name', 'Bánh quy')->firstOrFail()->id,
    ];

    $products = [
        ['category_id' => $categories['Bánh mì'], 'name' => 'Bánh mì baguette', 'description' => 'Bánh mì vỏ giòn, ruột mềm, nướng mỗi ngày.', 'price' => 18000, 'stock_quantity' => 40, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh mì'], 'name' => 'Bánh mì thịt nướng', 'description' => 'Bánh mì giòn cùng thịt nướng và rau thơm.', 'price' => 35000, 'stock_quantity' => 25, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh mì'], 'name' => 'Bánh mì sandwich trứng', 'description' => 'Sandwich trứng, rau xanh và sốt mayonnaise.', 'price' => 28000, 'stock_quantity' => 20, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh ngọt'], 'name' => 'Croissant bơ', 'description' => 'Croissant nhiều lớp với hương bơ thơm.', 'price' => 28000, 'stock_quantity' => 25, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh ngọt'], 'name' => 'Bánh donut socola', 'description' => 'Donut mềm phủ sốt socola và vụn hạt.', 'price' => 22000, 'stock_quantity' => 30, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh ngọt'], 'name' => 'Bánh tart trứng', 'description' => 'Tart ngọt với nhân trứng béo mềm.', 'price' => 25000, 'stock_quantity' => 18, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh kem'], 'name' => 'Bánh kem dâu tây', 'description' => 'Bánh gato kem tươi và dâu tây tươi.', 'price' => 320000, 'stock_quantity' => 8, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh kem'], 'name' => 'Bánh kem socola', 'description' => 'Bánh kem socola mềm với lớp ganache.', 'price' => 350000, 'stock_quantity' => 7, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh kem'], 'name' => 'Bánh tiramisu', 'description' => 'Bánh tiramisu cà phê với kem mascarpone.', 'price' => 290000, 'stock_quantity' => 10, 'status' => 'available', 'image' => null],
        ['category_id' => $categories['Bánh quy'], 'name' => 'Bánh quy bơ', 'description' => 'Hộp bánh quy bơ nướng giòn thơm.', 'price' => 65000, 'stock_quantity' => 30, 'status' => 'available', 'image' => null],
    ];

    foreach ($products as $product) {
        Product::updateOrCreate(
            ['name' => $product['name']],
            $product
        );
    }
}
```

File seeder cần import các model:

```php
use App\Models\Category;
use App\Models\Product;
```

Đảm bảo thứ tự gọi seeder trong `DatabaseSeeder.php` là danh mục trước sản phẩm:

```php
$this->call([
    CategorySeeder::class,
    ProductSeeder::class,
]);
```

Sau đó chạy:

```powershell
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=ProductSeeder
```

Hoặc:

```powershell
php artisan migrate:fresh --seed
```

## 2. Tạo model

Chạy lệnh:

```powershell
php artisan make:model Product
```

Mở `app/Models/Product.php` và thêm:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

Lưu ý: vì form sản phẩm cần chọn danh mục, hãy tạo `Category` nếu chưa có:

```powershell
php artisan make:model Category
```

Nội dung quan hệ danh mục trong `app/Models/Category.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

## 3. Tạo controller

Chạy:

```powershell
php artisan make:controller Admin\\ProductController --resource
```

Controller phải nằm tại:

```text
app/Http/Controllers/Admin/ProductController.php
```

### 3.1. Danh sách và tìm kiếm

```php
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

public function index(Request $request)
{
    $products = Product::query()
        ->with('category')
        ->when($request->filled('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.products.index', compact('products'));
}
```

### 3.2. Mở form thêm

```php
public function create()
{
    return view('admin.products.create', [
        'categories' => Category::orderBy('name')->get(),
    ]);
}
```

### 3.3. Thêm sản phẩm

```php
use Illuminate\Support\Facades\Storage;

public function store(Request $request)
{
    $data = $request->validate([
        'category_id' => ['required', 'exists:categories,id'],
        'name' => ['required', 'string', 'max:150', 'unique:products,name'],
        'description' => ['nullable', 'string'],
        'price' => ['required', 'numeric', 'min:0'],
        'stock_quantity' => ['required', 'integer', 'min:0'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'status' => ['required', 'in:available,unavailable'],
    ]);

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    Product::create($data);

    return redirect()
        ->route('admin.products.index')
        ->with('status', 'Thêm sản phẩm thành công.');
}
```

### 3.4. Xem chi tiết

Laravel tự lấy đúng sản phẩm theo route model binding:

```php
public function show(Product $product)
{
    return view('admin.products.show', [
        'product' => $product->load('category'),
    ]);
}
```

### 3.5. Mở form sửa

```php
public function edit(Product $product)
{
    return view('admin.products.edit', [
        'product' => $product,
        'categories' => Category::orderBy('name')->get(),
    ]);
}
```

### 3.6. Sửa sản phẩm

Tên sản phẩm phải không trùng với các sản phẩm khác, nhưng cho phép giữ tên của chính sản phẩm đang sửa:

```php
public function update(Request $request, Product $product)
{
    $data = $request->validate([
        'category_id' => ['required', 'exists:categories,id'],
        'name' => [
            'required',
            'string',
            'max:150',
            'unique:products,name,' . $product->id,
        ],
        'description' => ['nullable', 'string'],
        'price' => ['required', 'numeric', 'min:0'],
        'stock_quantity' => ['required', 'integer', 'min:0'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'status' => ['required', 'in:available,unavailable'],
    ]);

    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

    return redirect()
        ->route('admin.products.index')
        ->with('status', 'Cập nhật sản phẩm thành công.');
}
```

### 3.7. Xóa sản phẩm

Migration đơn hàng có khóa ngoại đến sản phẩm:

```text
products.id <- orders.product_id
```

Trước khi xóa, kiểm tra sản phẩm có trong đơn hàng hay không:

```php
use Illuminate\Support\Facades\DB;

public function destroy(Product $product)
{
    if (DB::table('orders')->where('product_id', $product->id)->exists()) {
        return back()->withErrors([
            'product' => 'Không thể xóa sản phẩm đã có trong đơn hàng.',
        ]);
    }

    if ($product->image) {
        Storage::disk('public')->delete($product->image);
    }

    $product->delete();

    return redirect()
        ->route('admin.products.index')
        ->with('status', 'Xóa sản phẩm thành công.');
}
```

## 4. Thêm route

Trong `routes/web.php`, thêm controller:

```php
use App\Http\Controllers\Admin\ProductController;
```

Trong nhóm middleware admin:

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    Route::resource('/admin/products', ProductController::class)
        ->names('admin.products')
        ->parameters(['products' => 'product']);
});
```

Các route được tạo:

| Tác vụ | Method | URL | Tên route |
|---|---|---|---|
| Danh sách | GET | `/admin/products` | `admin.products.index` |
| Form thêm | GET | `/admin/products/create` | `admin.products.create` |
| Lưu thêm | POST | `/admin/products` | `admin.products.store` |
| Chi tiết | GET | `/admin/products/{product}` | `admin.products.show` |
| Form sửa | GET | `/admin/products/{product}/edit` | `admin.products.edit` |
| Cập nhật | PUT/PATCH | `/admin/products/{product}` | `admin.products.update` |
| Xóa | DELETE | `/admin/products/{product}` | `admin.products.destroy` |

Kiểm tra route:

```powershell
php artisan route:list --path=admin/products
```

## 5. Tạo thư mục view

Tạo thư mục:

```text
resources/views/admin/products
```

Các file cần tạo:

```text
index.blade.php
create.blade.php
edit.blade.php
show.blade.php
_form.blade.php
```

## 6. Form dùng chung

Tạo `resources/views/admin/products/_form.blade.php`.

```blade
@csrf

<label for="category_id">Danh mục</label>
<select id="category_id" name="category_id" required>
    <option value="">-- Chọn danh mục --</option>
    @foreach ($categories as $category)
        <option value="{{ $category->id }}"
            @selected(old('category_id', $product->category_id ?? '') == $category->id)>
            {{ $category->name }}
        </option>
    @endforeach
</select>

<label for="name">Tên sản phẩm</label>
<input id="name" type="text" name="name" maxlength="150"
       value="{{ old('name', $product->name ?? '') }}" required>

<label for="description">Mô tả</label>
<textarea id="description" name="description"
          rows="5">{{ old('description', $product->description ?? '') }}</textarea>

<label for="price">Giá</label>
<input id="price" type="number" name="price" min="0" step="0.01"
       value="{{ old('price', $product->price ?? '') }}" required>

<label for="stock_quantity">Số lượng tồn kho</label>
<input id="stock_quantity" type="number" name="stock_quantity" min="0"
       value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>

<label for="status">Trạng thái</label>
<select id="status" name="status" required>
    <option value="available"
        @selected(old('status', $product->status ?? 'available') === 'available')>
        Đang bán
    </option>
    <option value="unavailable"
        @selected(old('status', $product->status ?? '') === 'unavailable')>
        Ngừng bán
    </option>
</select>

<label for="image">Ảnh sản phẩm</label>
<input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">

@isset($product)
    @if ($product->image)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}"
             alt="{{ $product->name }}" width="160">
    @endif
@endisset
```

## 7. Trang danh sách

Tạo `resources/views/admin/products/index.blade.php`.

Nội dung tối thiểu:

```blade
<h1>Quản lý sản phẩm</h1>

@if (session('status'))
    <div>{{ session('status') }}</div>
@endif

<form method="GET" action="{{ route('admin.products.index') }}">
    <input type="search" name="search" value="{{ request('search') }}"
           placeholder="Tìm theo tên sản phẩm">
    <button type="submit">Tìm kiếm</button>
</form>

<a href="{{ route('admin.products.create') }}">Thêm sản phẩm</a>

<table border="1" cellpadding="8">
    <thead>
    <tr>
        <th>Tên</th>
        <th>Danh mục</th>
        <th>Giá</th>
        <th>Tồn kho</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name }}</td>
            <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
            <td>{{ $product->stock_quantity }}</td>
            <td>{{ $product->status === 'available' ? 'Đang bán' : 'Ngừng bán' }}</td>
            <td>
                <a href="{{ route('admin.products.show', $product) }}">Xem chi tiết</a>
                <a href="{{ route('admin.products.edit', $product) }}">Sửa</a>

                <form action="{{ route('admin.products.destroy', $product) }}"
                      method="POST"
                      onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Xóa</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $products->links() }}
```

## 8. Trang thêm sản phẩm

Tạo `resources/views/admin/products/create.blade.php`:

```blade
<h1>Thêm sản phẩm</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('admin.products.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @include('admin.products._form')
    <button type="submit">Thêm sản phẩm</button>
    <a href="{{ route('admin.products.index') }}">Hủy</a>
</form>
```

## 9. Trang sửa sản phẩm

Tạo `resources/views/admin/products/edit.blade.php`:

```blade
<h1>Sửa sản phẩm: {{ $product->name }}</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('admin.products.update', $product) }}"
      method="POST"
      enctype="multipart/form-data">
    @method('PUT')
    @include('admin.products._form', ['product' => $product])
    <button type="submit">Lưu thay đổi</button>
    <a href="{{ route('admin.products.index') }}">Hủy</a>
</form>
```

## 10. Trang xem chi tiết

Tạo `resources/views/admin/products/show.blade.php`:

```blade
<h1>{{ $product->name }}</h1>

@if ($product->image)
    <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}"
         alt="{{ $product->name }}" width="300">
@else
    <p>Chưa có ảnh sản phẩm.</p>
@endif

<p><strong>Danh mục:</strong> {{ $product->category->name }}</p>
<p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} đ</p>
<p><strong>Tồn kho:</strong> {{ $product->stock_quantity }}</p>
<p><strong>Trạng thái:</strong>
    {{ $product->status === 'available' ? 'Đang bán' : 'Ngừng bán' }}
</p>
<p><strong>Mô tả:</strong> {{ $product->description ?: 'Không có mô tả' }}</p>

<a href="{{ route('admin.products.edit', $product) }}">Sửa sản phẩm</a>
<a href="{{ route('admin.products.index') }}">Quay lại danh sách</a>
```

## 11. Bật quản lý ảnh upload

Chạy:

```powershell
php artisan storage:link
```

Ảnh được lưu trong:

```text
storage/app/public/products
```

và truy cập công khai bằng:

```text
storage/products/<ten-file>
```

## 12. Thêm liên kết vào dashboard admin

Trong `resources/views/dashboard/admin.blade.php`, thêm:

```blade
<a href="{{ route('admin.products.index') }}">
    Quản lý sản phẩm
</a>
```

## 13. Kiểm tra

Chạy:

```powershell
php artisan optimize:clear
php artisan view:cache
php artisan route:list --path=admin/products
php artisan test
```

Kiểm tra thủ công bằng tài khoản admin:

1. Đăng nhập admin.
2. Mở `/admin/products`.
3. Thêm một sản phẩm hợp lệ.
4. Mở trang xem chi tiết.
5. Sửa tên, giá hoặc số lượng.
6. Xóa sản phẩm chưa nằm trong đơn hàng.
7. Kiểm tra tài khoản thường bị HTTP 403 nếu gọi trực tiếp URL admin.

## 14. Giao diện và cách sử dụng mới

Các trang CRUD sản phẩm dùng chung file layout
`resources/views/admin/products/layout.blade.php` để có thanh điều hướng,
Bootstrap 5 và thông báo nhất quán.

### Danh sách sản phẩm

Trang `/admin/products` gồm ô tìm kiếm theo tên, ảnh nhỏ, danh mục, giá,
số lượng tồn kho, trạng thái và các nút xem, sửa, xóa.

- Nhấn **Thêm sản phẩm** để mở form thêm.
- Nhập từ khóa rồi nhấn **Tìm kiếm** để lọc.
- Nhấn nút mắt để xem chi tiết.
- Nhấn nút bút chì để sửa.
- Nhấn nút thùng rác để xóa sau bước xác nhận.
- Sản phẩm đã có trong đơn hàng không thể xóa.

### Form thêm và sửa

Các trường bắt buộc là danh mục, tên, giá, số lượng tồn kho và trạng thái.
Tên sản phẩm phải không trùng trong hệ thống. Khi chọn ảnh mới, trình duyệt
sẽ hiển thị ảnh xem trước ngay dưới ô chọn ảnh.

Khi dữ liệu không hợp lệ, Laravel trả người dùng về đúng form và hiển thị
danh sách lỗi cùng lớp đỏ ở từng trường.

### Upload và lưu ảnh

Ảnh được lưu trên disk công khai tại thư mục `products`. Đường dẫn ảnh được
tạo bằng:

```php
$request->file('image')->store('products', 'public');
```

Trước khi sửa ảnh, hệ thống xóa ảnh cũ nếu có. Khi xóa sản phẩm, ảnh cũng
được xóa khỏi storage để tránh file rác.

### Kiểm tra giao diện sau khi thay đổi

```powershell
cd D:\Laravel\Bakery\projectLaravelCK
php artisan optimize:clear
php artisan view:cache
php artisan route:list --path=admin/products
php artisan test
php artisan serve
```

Sau đó đăng nhập bằng tài khoản admin, mở:

- `http://127.0.0.1:8000/admin/products`
- `http://127.0.0.1:8000/admin/products/create`
