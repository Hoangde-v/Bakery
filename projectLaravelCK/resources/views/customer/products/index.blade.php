<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --bakery-ink:#35251e; --bakery-brown:#75432d; --bakery-cream:#fbf7f2; }
        body { background:var(--bakery-cream); color:var(--bakery-ink); }
        .store-navbar { background:#fff; }
        .navbar-brand { color:var(--bakery-ink)!important; }
        .product-card { border:1px solid #eee6df; border-radius:1rem; overflow:hidden; transition:.2s; }
        .product-card:hover { transform:translateY(-4px); box-shadow:0 1rem 2rem rgba(53,37,30,.1)!important; }
        .product-image { height:220px; object-fit:cover; width:100%; }
        .price { color:var(--bakery-brown); }
        .store-hero { background:linear-gradient(120deg,#70412c,#b4774d); color:white; border-radius:1.25rem; overflow:hidden; }
        .store-hero p { color:rgba(255,255,255,.78); }
        .btn { border-radius:.65rem; }
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
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-receipt me-1"></i>Đơn hàng
                        </a>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-person me-1"></i>Tài khoản
                        </a>
                    @elseif (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-speedometer2 me-1"></i>Quản trị
                        </a>
                    @endif
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
        <section class="store-hero p-4 p-lg-5 mb-5 shadow-sm">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge rounded-pill bg-white bg-opacity-25 mb-3">LÒ BÁNH MỖI NGÀY</span>
                    <h1 class="display-6 fw-bold mb-2">Bánh ngon, ngày thêm vui.</h1>
                    <p class="mb-0">Chọn món bánh yêu thích, đặt hàng nhanh và nhận bánh tận nơi.</p>
                </div>
            </div>
        </section>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-2 mb-3">
            <div>
                <span class="text-uppercase small fw-semibold text-secondary">Thực đơn hôm nay</span>
                <h2 class="h3 fw-bold mb-0">{{ $search !== '' ? 'Kết quả tìm kiếm' : 'Sản phẩm nổi bật' }}</h2>
            </div>
            <span class="text-muted small">{{ $products->total() }} sản phẩm</span>
        </div>

        {{-- ================= 1. FORM TÌM KIẾM ================= --}}
    <form method="GET" action="{{ route('customer.products.index') }}" class="mb-4">
        <label for="product-search" class="form-label fw-semibold">Tìm bánh theo tên, mô tả hoặc danh mục</label>
        
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            
            {{-- Ô nhập từ khóa tìm kiếm --}}
            <input 
                type="search" 
                id="product-search" 
                name="q" 
                class="form-control" 
                value="{{ $search }}" 
                maxlength="100" 
                placeholder="Ví dụ: bánh kem, croissant..."
            >
            
            <button type="submit" class="btn btn-dark">Tìm kiếm</button>

            {{-- Nếu người dùng đã nhập từ khóa tìm kiếm thì hiện nút "Xóa" bộ lọc --}}
            @if (!empty($search))
                <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary">Xóa</a>
            @endif
        </div>
    </form>

    {{-- ================= 2. DANH SÁCH SẢN PHẨM ================= --}}
    <div class="row g-4">
        {{-- Vòng lặp duyệt qua từng sản phẩm. Nếu rỗng ($products trống) thì chạy vào @empty --}}
        @forelse ($products as $product)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <article class="card product-card h-100 shadow-sm">
                    
                    {{-- Hiển thị hình ảnh hoặc ảnh mặc định nếu không có --}}
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                    @else
                        <div class="product-image bg-secondary-subtle d-flex align-items-center justify-content-center text-secondary">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        {{-- Tên danh mục --}}
                        <span class="badge text-bg-light border align-self-start mb-2">{{ $product->category->name }}</span>
                        
                        {{-- Tên sản phẩm --}}
                        <h2 class="h6 fw-semibold flex-grow-1">{{ $product->name }}</h2>
                        
                        {{-- Trạng thái tồn kho --}}
                        <div class="small text-muted mb-3">
                            @if ($product->stock_quantity > 0)
                                <span class="text-success"><i class="bi bi-check-circle me-1"></i>Còn hàng</span>
                            @else
                                <span class="text-danger"><i class="bi bi-x-circle me-1"></i>Tạm hết hàng</span>
                            @endif
                        </div>
                        
                        {{-- Giá tiền & Nút hành động --}}
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <strong class="price">{{ number_format($product->price, 0, ',', '.') }} đ</strong>
                            
                            <a href="{{ route('customer.products.show', $product) }}" class="btn btn-sm btn-dark">
                                {{ $product->stock_quantity > 0 ? 'Xem & đặt mua' : 'Xem chi tiết' }}
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @empty
            {{-- Phần hiển thị khi không tìm thấy sản phẩm nào --}}
            <div class="col-12">
                <div class="card border-0 text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-basket display-5 text-secondary"></i>
                        <p class="text-muted mt-3 mb-0">
                            @if (!empty($search))
                                Không tìm thấy loại bánh phù hợp với "{{ $search }}".
                            @else
                                Hiện chưa có sản phẩm đang bán. Vui lòng quay lại sau.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

        <div class="d-flex justify-content-center mt-4">{{ $products->links() }}</div>
    </main>
</body>
</html>
