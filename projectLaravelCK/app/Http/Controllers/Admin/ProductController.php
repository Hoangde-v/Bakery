<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm cùng danh mục để hiển thị và phân trang.
        $products = Product::query()
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // Truyền danh mục đã sắp xếp để chọn khi tạo sản phẩm mới.
        return view('admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào trước khi lưu sản phẩm.
        $data = $request->validate([
            'category_id'    => ['required', 'exists:categories,id'],
            'name'           => ['required', 'string', 'max:150', 'unique:products,name'],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'         => ['required', 'in:available,unavailable'],
        ]);

        // Lưu ảnh vào thư mục products trên disk public nếu được tải lên.
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Thêm sản phẩm thành công.');
    }

    public function show(Product $product)
    {
        // Tải thông tin danh mục để hiển thị đầy đủ chi tiết sản phẩm.
        return view('admin.products.show', [
            'product' => $product->load('category'),
        ]);
    }

    public function edit(Product $product)
    {
        // Hiển thị biểu mẫu chỉnh sửa và danh sách danh mục có thể chọn.
        return view('admin.products.edit', [
            'product'    => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        // Cho phép giữ nguyên tên sản phẩm hiện tại nhưng vẫn chặn tên trùng của sản phẩm khác.
        $data = $request->validate([
            'category_id'    => ['required', 'exists:categories,id'],
            'name'           => [
                'required',
                'string',
                'max:150',
                'unique:products,name,' . $product->id,
            ],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'         => ['required', 'in:available,unavailable'],
        ]);

        // Khi thay ảnh, xóa ảnh cũ trước khi lưu ảnh mới.
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

    public function destroy(Product $product)
    {
        // Không cho xóa sản phẩm đã xuất hiện trong đơn hàng để tránh mất dữ liệu liên quan.
        if (DB::table('orders')->where('product_id', $product->id)->exists()) {
            return back()->withErrors([
                'product' => 'Không thể xóa sản phẩm đã có trong đơn hàng.',
            ]);
        }

        // Xóa file ảnh trước khi xóa bản ghi sản phẩm khỏi cơ sở dữ liệu.
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Xóa sản phẩm thành công.');
    }
}