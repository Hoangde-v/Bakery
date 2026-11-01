<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --bakery-ink:#35251e; --bakery-brown:#75432d; --bakery-cream:#fbf7f2; }
        body { background:var(--bakery-cream); color:var(--bakery-ink); }
        .store-navbar { background:#fff; }
        .navbar-brand { color:var(--bakery-ink)!important; }
        .detail-image { width:100%; max-height:500px; object-fit:cover; border-radius:.9rem; }
        .price { color:var(--bakery-brown); }
        .card { border-radius:1rem; }
        .btn { border-radius:.65rem; }
        .order-panel { background:#fbf8f4; border:1px solid #eee6df; border-radius:.9rem; }
    </style>
</head>
<body>
    <nav class="navbar store-navbar border-bottom mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="{{ route('customer.products.index') }}">
                <i class="bi bi-shop-window me-2 text-warning"></i>Bakery
            </a>
            <div class="d-flex flex-wrap gap-2">
                @auth
                    @if (auth()->user()->role === 'user')
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary btn-sm">Đơn hàng</a>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-sm">Trang cá nhân</a>
                    @endif
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
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 bg-light p-3 p-lg-4 d-flex align-items-center">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="detail-image">
                    @else
                        <div class="w-100 text-center text-muted py-5">
                            <i class="bi bi-cake2 display-1"></i>
                            <p class="mb-0 mt-2">Ảnh sản phẩm đang được cập nhật</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge rounded-pill text-bg-light border">{{ $product->category->name }}</span>
                        <h1 class="h2 mt-3">{{ $product->name }}</h1>
                        <h2 class="h3 price fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</h2>
                        <p class="text-muted">
                            @if ($product->stock_quantity > 0)
                                <i class="bi bi-check-circle-fill text-success me-1"></i>
                                Còn {{ $product->stock_quantity }} sản phẩm
                            @else
                                <i class="bi bi-x-circle-fill text-danger me-1"></i>Hiện đã hết hàng
                            @endif
                        </p>
                        <hr>
                        <p class="text-secondary">{{ $product->description ?: 'Đang cập nhật mô tả sản phẩm.' }}</p>
                        @if (auth()->check() && auth()->user()->role === 'user' && $product->stock_quantity > 0)
                            <div class="order-panel p-3 p-md-4 mt-4">
                                <h2 class="h5 fw-bold mb-1"><i class="bi bi-bag-check me-2"></i>Đặt hàng</h2>
                                <p class="small text-muted mb-3">Thanh toán khi nhận hàng (COD).</p>
                                <form method="POST" action="{{ route('customer.orders.store', $product) }}" class="row g-3">
                                    @csrf
                                    <div class="col-sm-4">
                                        <label for="quantity" class="form-label">Số lượng</label>
                                        <input id="quantity" type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                               min="1" max="{{ min($product->stock_quantity, 100) }}"
                                               value="{{ old('quantity', 1) }}" required>
                                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-sm-8">
                                        <label for="recipient_name" class="form-label">Tên người nhận</label>
                                        <input id="recipient_name" type="text" name="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror"
                                               maxlength="100" value="{{ old('recipient_name', auth()->user()->name) }}" required>
                                        @error('recipient_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="recipient_phone" class="form-label">Số điện thoại</label>
                                        <input id="recipient_phone" type="text" name="recipient_phone" class="form-control @error('recipient_phone') is-invalid @enderror"
                                               maxlength="20" value="{{ old('recipient_phone', auth()->user()->phone) }}" required>
                                        @error('recipient_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="shipping_address" class="form-label">Địa chỉ giao hàng</label>
                                        <input id="shipping_address" type="text" name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror"
                                               maxlength="255" value="{{ old('shipping_address') }}" required>
                                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="note" class="form-label">Ghi chú <span class="text-muted">(không bắt buộc)</span></label>
                                        <textarea id="note" name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                                    </div>
                                    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                        <span class="small text-muted">Tạm tính: <strong class="text-dark">{{ number_format($product->price, 0, ',', '.') }} đ</strong> / sản phẩm</span>
                                        <button type="submit" class="btn btn-dark px-4">
                                            <i class="bi bi-bag-check me-1"></i>Đặt hàng
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @elseif (!auth()->check())
                            <div class="alert alert-light border mt-4 mb-0">
                                <i class="bi bi-info-circle me-1"></i>Đăng nhập để đặt mua sản phẩm.
                                <a href="{{ route('login') }}" class="alert-link">Đăng nhập</a>
                            </div>
                        @elseif (auth()->user()->role === 'user' && $product->stock_quantity < 1)
                            <button type="button" class="btn btn-secondary mt-3" disabled>Hiện đã hết hàng</button>
                        @endif
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('customer.products.index') }}" class="btn btn-outline-dark">
                                <i class="bi bi-arrow-left me-1"></i>Tiếp tục xem bánh
                            </a>
                            @if (auth()->check() && auth()->user()->role === 'user' && $product->stock_quantity > 0)
                                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-receipt me-1"></i>Đơn hàng của tôi
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
