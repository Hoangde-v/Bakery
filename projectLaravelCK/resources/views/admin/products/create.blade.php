@extends('admin.products.layout')
@section('title', 'Thêm sản phẩm')

@section('content')
    <a href="{{ route('admin.products.index') }}" class="btn btn-link text-decoration-none px-0 mb-3">
        <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
    </a>
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h1 class="h4 mb-0">Thêm sản phẩm mới</h1>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.products._form')
                <div class="d-flex gap-2 justify-content-end mt-3">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection