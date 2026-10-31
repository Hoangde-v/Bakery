<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f8f5f1; }
        .store-navbar { background:#fff; }
        .product-card { border:0; border-radius:.8rem; overflow:hidden; transition:.2s; }
        .product-card:hover { transform:translateY(-4px); box-shadow:0 .75rem 1.5rem rgba(0,0,0,.08)!important; }
        .product-image { height:220px; object-fit:cover; width:100%; }
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
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-dark btn-sm">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        <div class="text-center mb-4">
            <h1 class="h2">Danh sách sản phẩm</h1>
            <p class="text-muted mb-0">Khám phá các sản phẩm đang được bán tại Bakery.</p>
        </div>

        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <article class="card product-card h-100 shadow-sm">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                        @else
                            <div class="product-image bg-secondary-subtle d-flex align-items-center justify-content-center text-secondary">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <span class="badge text-bg-light border align-self-start mb-2">{{ $product->category->name }}</span>
                            <h2 class="h6 flex-grow-1">{{ $product->name }}</h2>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <strong class="price">{{ number_format($product->price, 0, ',', '.') }} đ</strong>
                                <a href="{{ route('customer.products.show', $product) }}" class="btn btn-sm btn-dark">Xem chi tiết</a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">Hiện chưa có sản phẩm đang bán.</div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">{{ $products->links() }}</div>
    </main>
</body>
</html>
