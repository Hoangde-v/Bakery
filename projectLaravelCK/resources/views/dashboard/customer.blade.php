<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản khách hàng - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; background:#f8f5f1; }
        .store-navbar { background:#fff; }
        .card { border:0; border-radius:.75rem; }
        .btn { border-radius:.5rem; }
    </style>
</head>
<body>
    <nav class="navbar store-navbar border-bottom mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="{{ route('customer.products.index') }}">
                <i class="bi bi-shop-window me-2 text-warning"></i>Bakery
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-grid me-1"></i>Sản phẩm
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        <div class="mb-4">
            <h1 class="h3 mb-1">Tài khoản của tôi</h1>
            <p class="text-muted mb-0">Xin chào {{ auth()->user()->name }}.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h2 class="h5 mb-0"><i class="bi bi-key me-2"></i>Đổi mật khẩu</h2>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('account.changePassword') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                <input id="current_password" type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input id="password" type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Nhập lại mật khẩu mới</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5">Xóa tài khoản</h2>
                        <p class="text-muted small">Thao tác này không thể hoàn tác.</p>
                        <form method="POST" action="{{ route('account.delete') }}" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không? Hành động này không thể hoàn tác.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i>Xóa tài khoản
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
