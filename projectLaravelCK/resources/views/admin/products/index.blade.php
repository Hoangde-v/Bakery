@extends('admin.products.layout')
@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Quản lý sản phẩm</h1>
            <p class="text-muted mb-0">Thêm, sửa và theo dõi sản phẩm trong cửa hàng.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm
        </a>
    </div>

    @error('product')
        <div class="alert alert-danger shadow-sm">{{ $message }}</div>
    @enderror

    <div class="card shadow-sm overflow-hidden">
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
                        <td>{{ $product->stock_quantity }}</td>
                        <td>
                            <span class="badge {{ $product->status === 'available' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $product->status === 'available' ? 'Đang bán' : 'Ngừng bán' }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            Không tìm thấy sản phẩm phù hợp.
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