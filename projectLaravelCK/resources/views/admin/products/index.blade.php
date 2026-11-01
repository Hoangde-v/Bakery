@extends('admin.products.layout')
@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <span class="text-uppercase small fw-semibold text-secondary">Danh mục cửa hàng</span>
            <h1 class="h3 fw-bold mb-1">Quản lý sản phẩm</h1>
            <p class="text-muted mb-0">Đang quản lý {{ $products->total() }} sản phẩm.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm
        </a>
    </div>

    @error('product')
        <div class="alert alert-danger shadow-sm">{{ $message }}</div>
    @enderror

    {{-- Phần tìm kiếm sản phẩm dành cho admin --}}
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-4">
        <label for="admin-product-search" class="form-label fw-semibold">Tìm sản phẩm theo tên, mô tả hoặc danh mục</label>
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input
                type="search"
                id="admin-product-search"
                name="q"
                class="form-control"
                value="{{ $search }}"
                maxlength="100"
                placeholder="Ví dụ: bánh kem, croissant..."
            >
            <button type="submit" class="btn btn-dark">Tìm kiếm</button>
            @if ($search !== '')
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Xóa</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-box-seam me-2 text-secondary"></i>Danh sách sản phẩm</span>
            <span class="text-muted small">Trang {{ $products->currentPage() }} / {{ max($products->lastPage(), 1) }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-image bg-secondary-subtle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-secondary"></i>
                                    </div>
                                @endif
                                <span class="fw-semibold">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td><span class="badge text-bg-light border">{{ $product->category->name }}</span></td>
                        <td class="fw-semibold text-danger">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                        <td>
                            <span class="badge {{ $product->stock_quantity > 0 ? 'text-bg-light border text-dark' : 'text-bg-danger' }}">
                                {{ $product->stock_quantity }} sản phẩm
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->status === 'available' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $product->status === 'available' ? 'Đang bán' : 'Ngừng bán' }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-secondary" title="Xem chi tiết" aria-label="Xem {{ $product->name }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Sửa" aria-label="Sửa {{ $product->name }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa" aria-label="Xóa {{ $product->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox display-6 text-secondary"></i>
                            <p class="text-muted mt-2 mb-3">
                                @if ($search !== '')
                                    Không tìm thấy sản phẩm phù hợp với "{{ $search }}".
                                @else
                                    Chưa có sản phẩm nào trong cửa hàng.
                                @endif
                            </p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-dark btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm đầu tiên
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endsection