@extends('admin.products.layout')
@section('title', $product->name)

@section('content')
    <a href="{{ route('admin.products.index') }}" class="btn btn-link text-decoration-none px-0 mb-3">
        <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
    </a>
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4 rounded-start">
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm" style="max-height:360px">
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-image fs-1"></i>
                        <p class="mt-2 mb-0">Chưa có ảnh sản phẩm</p>
                    </div>
                @endif
            </div>
            <div class="col-md-7">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <h1 class="h3">{{ $product->name }}</h1>
                            <span class="badge text-bg-light border">{{ $product->category->name }}</span>
                        </div>
                        <span class="badge {{ $product->status === 'available' ? 'text-bg-success' : 'text-bg-secondary' }}">
                            {{ $product->status === 'available' ? 'Đang bán' : 'Ngừng bán' }}
                        </span>
                    </div>
                    <h2 class="h4 text-danger mt-4">{{ number_format($product->price, 0, ',', '.') }} đ</h2>
                    <p class="mb-1"><strong>Tồn kho:</strong> {{ $product->stock_quantity }}</p>
                    <hr>
                    <p class="text-muted mb-4">{{ $product->description ?: 'Không có mô tả.' }}</p>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>Sửa sản phẩm
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection