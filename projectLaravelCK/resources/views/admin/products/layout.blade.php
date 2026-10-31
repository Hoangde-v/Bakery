<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản lý sản phẩm') - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .admin-navbar { background: linear-gradient(135deg, #6f3b22, #a9623b); }
        .product-image { width: 64px; height: 64px; object-fit: cover; border-radius: .5rem; }
        .preview-image { max-width: 220px; max-height: 220px; object-fit: cover; }
        .card { border: 0; border-radius: .75rem; }
        .table > :not(caption) > * > * { padding: 1rem .75rem; }
        .btn { border-radius: .5rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark admin-navbar mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shop-window me-2"></i>Bakery Admin
            </a>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-light btn-sm" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-1"></i>Bảng điều khiển
                </a>
                <a class="btn btn-light btn-sm" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box-seam me-1"></i>Sản phẩm
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
