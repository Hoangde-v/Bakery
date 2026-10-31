<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang quản trị - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; background:#f4f6f9; }
        .admin-navbar { background:linear-gradient(135deg,#6f3b22,#a9623b); }
        .card { border:0; border-radius:.75rem; }
        .action-card { transition:.2s ease; text-decoration:none; color:inherit; }
        .action-card:hover { transform:translateY(-3px); box-shadow:0 .75rem 1.5rem rgba(0,0,0,.09)!important; color:inherit; }
        .action-icon { width:52px; height:52px; display:flex; align-items:center; justify-content:center; border-radius:.75rem; }
        .btn { border-radius:.5rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark admin-navbar shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shop-window me-2"></i>Bakery Admin
            </a>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
                </button>
            </form>
        </div>
    </nav>

    <main class="container py-4 pb-5">
        <div class="mb-4">
            <h1 class="h3 mb-1">Bảng điều khiển</h1>
            <p class="text-muted mb-0">Xin chào, {{ auth()->user()->name }}. Chọn chức năng bạn muốn quản lý.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <a href="{{ route('admin.products.index') }}" class="card action-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="action-icon bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h5 mb-1">Quản lý sản phẩm</h2>
                            <p class="text-muted small mb-0">Thêm, sửa và cập nhật sản phẩm.</p>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-secondary"></i>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="action-icon bg-danger-subtle text-danger">
                            <i class="bi bi-person-x fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h5 mb-1">Tài khoản quản trị</h2>
                            <p class="text-muted small mb-1">Vai trò: Quản trị viên</p>
                            <form method="POST" action="{{ route('account.delete') }}" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản quản trị này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i>Xóa tài khoản
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="h5 mb-0"><i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu</h2>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('account.changePassword') }}" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <input id="current_password" type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="password" class="form-label">Mật khẩu mới</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="password_confirmation" class="form-label">Nhập lại mật khẩu mới</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-key me-1"></i>Cập nhật mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
