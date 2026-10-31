<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f8f5f1; }
        .store-navbar { background:#fff; }
        .detail-image { width:100%; max-height:450px; object-fit:cover; border-radius:.75rem; }
        .price { color:#a9623b; }
    </style>
</head>
<body>
    <nav class="navbar store-navbar border-bottom mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="{{ route('customer.products.index') }}">
                <i class="bi bi-shop-window me-2 text-warning"></i>Bakery
            </a>
            <div class="d-flex gap-2">
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-sm">Trang cá nhân</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-dark btn-sm">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 bg-light p-3 d-flex align-items-center">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="detail-image">
                    @else
                        <div class="w-100 text-center text-muted py-5"><i class="bi bi-image fs-1"></i></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge text-bg-light border">{{ $product->category->name }}</span>
                        <h1 class="h2 mt-3">{{ $product->name }}</h1>
                        <h2 class="h3 price">{{ number_format($product->price, 0, ',', '.') }} đ</h2>
                        <p class="text-muted">Còn {{ $product->stock_quantity }} sản phẩm</p>
                        <hr>
                        <p>{{ $product->description ?: 'Đang cập nhật mô tả sản phẩm.' }}</p>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('customer.products.index') }}" class="btn btn-outline-dark">
                                <i class="bi bi-arrow-left me-1"></i>Quay lại
                            </a>
                            @auth
                                <button type="button" class="btn btn-dark" onclick="alert('Chức năng đặt hàng đang được cập nhật.')">
                                    <i class="bi bi-bag-check me-1"></i>Mua ngay
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-dark">
                                    <i class="bi bi-bag-check me-1"></i>Mua ngay
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
