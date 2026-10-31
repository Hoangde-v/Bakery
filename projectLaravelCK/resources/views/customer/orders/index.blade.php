<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --bakery-ink:#35251e; --bakery-cream:#fbf7f2; }
        body { background:var(--bakery-cream); color:var(--bakery-ink); }
        .store-navbar { background:#fff; }
        .navbar-brand { color:var(--bakery-ink)!important; }
        .card { border:1px solid #eee6df; border-radius:1rem; }
        .btn { border-radius:.65rem; }
        .page-heading { background:#fff; border:1px solid #eee6df; border-radius:1rem; }
        .table > :not(caption) > * > * { padding:1rem .85rem; }
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
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-dark btn-sm">
                    <i class="bi bi-person me-1"></i>Tài khoản
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        <div class="page-heading p-4 mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <span class="text-uppercase small fw-semibold text-secondary">Lịch sử mua sắm</span>
                <h1 class="h3 fw-bold mb-1">Đơn hàng của bạn</h1>
                <p class="text-muted mb-0">Theo dõi tiến độ và thông tin các đơn đã đặt.</p>
            </div>
            <a href="{{ route('customer.products.index') }}" class="btn btn-dark">
                <i class="bi bi-bag-plus me-1"></i>Mua thêm bánh
            </a>
        </div>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Người nhận</th>
                            <th>Địa chỉ giao</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            @php
                                $statusLabels = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipping' => 'Đang giao',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                ];
                                $statusClasses = [
                                    'pending' => 'text-bg-warning',
                                    'confirmed' => 'text-bg-primary',
                                    'shipping' => 'text-bg-info',
                                    'completed' => 'text-bg-success',
                                    'cancelled' => 'text-bg-secondary',
                                ];
                            @endphp
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->product->name }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>{{ number_format((float) $order->unit_price * $order->quantity, 0, ',', '.') }} đ</td>
                                <td>{{ $order->recipient_name }}<br><small class="text-muted">{{ $order->recipient_phone }}</small></td>
                                <td>{{ $order->shipping_address }}</td>
                                <td>
                                    <span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-receipt display-6 text-secondary"></i>
                                    <p class="text-muted mt-2 mb-3">Bạn chưa có đơn hàng nào.</p>
                                    <a href="{{ route('customer.products.index') }}" class="btn btn-dark btn-sm">Khám phá sản phẩm</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">{{ $orders->links() }}</div>
    </main>
</body>
</html>
