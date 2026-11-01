@extends('admin.products.layout')
@section('title', 'Sửa sản phẩm')

@section('content')
    <a href="{{ route('admin.products.index') }}" class="btn btn-link text-decoration-none px-0 mb-3">
        <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
    </a>
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <span class="text-uppercase small fw-semibold text-secondary">Cập nhật danh mục cửa hàng</span>
            <h1 class="h4 fw-bold mb-1">Sửa sản phẩm</h1>
            <p class="text-muted small mb-0">{{ $product->name }}</p>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.products._form', ['product' => $product])
                <div class="d-flex gap-2 justify-content-end mt-3">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Hủy</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection