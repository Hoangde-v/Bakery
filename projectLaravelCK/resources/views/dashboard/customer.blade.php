<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản khách hàng - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --bakery-ink:#35251e; --bakery-cream:#fbf7f2; }
        body { min-height:100vh; background:var(--bakery-cream); color:var(--bakery-ink); }
        .store-navbar { background:#fff; }
        .navbar-brand { color:var(--bakery-ink)!important; }
        .card { border:1px solid #eee6df; border-radius:1rem; }
        .btn { border-radius:.65rem; }
        .account-hero { background:linear-gradient(120deg,#70412c,#b4774d); color:#fff; border-radius:1.15rem; }
        .account-hero p { color:rgba(255,255,255,.78); }
    </style>
</head>
<body>
    <nav class="navbar store-navbar border-bottom mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="{{ route('customer.products.index') }}">
                <i class="bi bi-shop-window me-2 text-warning"></i>Bakery
            </a>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-grid me-1"></i>Sản phẩm
                </a>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-receipt me-1"></i>Đơn hàng
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
        <section class="account-hero p-4 p-lg-5 mb-4 shadow-sm">
            <span class="badge rounded-pill bg-white bg-opacity-25 mb-3">TÀI KHOẢN BAKERY</span>
            <h1 class="h2 fw-bold mb-2">Xin chào, {{ auth()->user()->name }}</h1>
            <p class="mb-0">Theo dõi đơn hàng và quản lý thông tin tài khoản của bạn.</p>
        </section>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
            <div>
                <span class="text-uppercase small fw-semibold text-secondary">Cập nhật mới nhất</span>
                <h2 class="h4 fw-bold mb-0">Đơn hàng của bạn</h2>
            </div>
            <a href="{{ route('customer.products.index') }}" class="btn btn-dark btn-sm">
                <i class="bi bi-bag-plus me-1"></i>Tiếp tục mua sắm
            </a>
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

        @php
            $orderStatusLabels = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'shipping' => 'Đang giao',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy',
            ];
            $orderStatusClasses = [
                'pending' => 'text-bg-warning',
                'confirmed' => 'text-bg-primary',
                'shipping' => 'text-bg-info',
                'completed' => 'text-bg-success',
                'cancelled' => 'text-bg-secondary',
            ];
        @endphp
        <section class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <span class="text-muted small">5 đơn hàng gần nhất</span>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-dark btn-sm">
                    Xem tất cả đơn hàng <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @if ($orders->isEmpty())
                <div class="card-body p-4 text-center">
                    <p class="text-muted mb-3">Bạn chưa có đơn hàng nào.</p>
                    <a href="{{ route('customer.products.index') }}" class="btn btn-dark">
                        <i class="bi bi-shop me-1"></i>Khám phá sản phẩm
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->product->name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ number_format((float) $order->unit_price * $order->quantity, 0, ',', '.') }} đ</td>
                                    <td>
                                        <span class="badge {{ $orderStatusClasses[$order->status] ?? 'text-bg-secondary' }}">
                                            {{ $orderStatusLabels[$order->status] ?? $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h2 class="h5 mb-1"><i class="bi bi-shield-lock me-2"></i>Bảo mật tài khoản</h2>
                        <p class="text-muted small mb-0">Cập nhật mật khẩu để bảo vệ tài khoản của bạn.</p>
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
