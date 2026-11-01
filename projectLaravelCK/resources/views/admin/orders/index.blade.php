<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --bakery-ink:#35251e; --bakery-cream:#fbf7f2; }
        body { background:var(--bakery-cream); color:var(--bakery-ink); }
        .admin-navbar { background:#fff; }
        .navbar-brand { color:var(--bakery-ink)!important; }
        .navbar-brand i { color:#b87346; }
        .card, .page-heading { border:1px solid #eee6df; border-radius:1rem; }
        .btn { border-radius:.65rem; }
        .table > :not(caption) > * > * { padding:.9rem .8rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark admin-navbar mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shop-window me-2"></i>Bakery Admin
            </a>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-speedometer2 me-1"></i>Bảng điều khiển
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-box-seam me-1"></i>Sản phẩm
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        <div class="page-heading bg-white p-4 mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <span class="text-uppercase small fw-semibold text-secondary">Vận hành cửa hàng</span>
                <h1 class="h3 fw-bold mb-1">Quản lý đơn hàng</h1>
                <p class="text-muted mb-0">Kiểm tra thông tin giao hàng và cập nhật trạng thái đơn.</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">
                <i class="bi bi-box-seam me-1"></i>Quản lý sản phẩm
            </a>
        </div>
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

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-3 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center"
                             style="width:50px;height:50px">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Tổng sản phẩm</p>
                            <p class="h3 fw-bold mb-0">{{ $productCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width:50px;height:50px">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Tổng đơn hàng</p>
                            <p class="h3 fw-bold mb-0">{{ $orderCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center"
                             style="width:50px;height:50px">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Đơn chờ xác nhận</p>
                            <p class="h3 fw-bold mb-0">{{ $pendingOrderCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-receipt me-2 text-secondary"></i>Đơn hàng mới nhất</span>
                <span class="text-muted small">{{ $orders->total() }} đơn hàng</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>SL / Tổng tiền</th>
                            <th>Giao hàng</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td><span class="fw-semibold">#{{ $order->id }}</span></td>
                                <td>{{ $order->user->name }}<br><small class="text-muted">{{ $order->user->email }}</small></td>
                                <td>{{ $order->product->name }}<br><small class="text-muted">{{ number_format((float) $order->unit_price, 0, ',', '.') }} đ / sản phẩm</small></td>
                                <td>{{ $order->quantity }} / {{ number_format((float) $order->unit_price * $order->quantity, 0, ',', '.') }} đ</td>
                                <td>
                                    {{ $order->recipient_name }}<br>
                                    <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $order->recipient_phone }}<br>
                                        <i class="bi bi-geo-alt me-1"></i>{{ $order->shipping_address }}</small>
                                    @if ($order->note)<br><small class="text-secondary"><i class="bi bi-chat-left-text me-1"></i>{{ $order->note }}</small>@endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="mb-2">
                                        <span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">
                                            {{ $statusLabels[$order->status] ?? $order->status }}
                                        </span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" aria-label="Trạng thái đơn hàng #{{ $order->id }}"
                                                @disabled($order->status === 'cancelled')>
                                            @foreach ($statuses as $status)
                                                @if ($status !== 'cancelled' || !in_array($order->status, ['shipping', 'completed'], true))
                                                <option value="{{ $status }}" @selected($order->status === $status)>
                                                    {{ $statusLabels[$status] }}
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-dark" @disabled($order->status === 'cancelled')>Lưu</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox display-6 text-secondary"></i>
                                    <p class="text-muted mt-2 mb-0">Chưa có đơn hàng nào cần xử lý.</p>
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
