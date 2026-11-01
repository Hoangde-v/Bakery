@extends('admin.products.layout')

@section('title', 'Thống kê và báo cáo')

@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <span class="text-uppercase small fw-semibold text-secondary">Vận hành cửa hàng</span>
            <h1 class="h3 fw-bold mb-1">Thống kê và báo cáo</h1>
            <p class="text-muted mb-0">Xem kết quả thống kê và xuất báo cáo từ các đơn hàng đã hoàn thành.</p>
        </div>
        @if ($hasSearched && $orderCount > 0)
            <a href="{{ route('admin.reports.index', request()->query() + ['export' => 1]) }}" class="btn btn-outline-dark">
                <i class="bi bi-download me-1"></i>Xuất báo cáo CSV
            </a>
        @endif
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="mode" class="form-label fw-semibold">Hình thức thống kê</label>
                    <select id="mode" name="mode" class="form-select">
                        <option value="daily" @selected($mode === 'daily')>Theo ngày</option>
                        <option value="monthly" @selected($mode === 'monthly')>Theo tháng</option>
                        <option value="period" @selected($mode === 'period')>Khoảng thời gian</option>
                    </select>
                </div>
                <div class="col-md-4" data-filter="daily">
                    <label for="date" class="form-label fw-semibold">Ngày thống kê</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ request('date', $today) }}">
                </div>
                <div class="col-md-4" data-filter="monthly">
                    <label for="month" class="form-label fw-semibold">Tháng thống kê</label>
                    <input type="month" id="month" name="month" class="form-control" value="{{ request('month', $month) }}">
                </div>
                <div class="col-md-4" data-filter="period">
                    <label for="from" class="form-label fw-semibold">Từ ngày</label>
                    <input type="date" id="from" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-4" data-filter="period">
                    <label for="to" class="form-label fw-semibold">Đến ngày</label>
                    <input type="date" id="to" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-search me-1"></i>Thống kê
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if ($hasSearched)
        @if ($orderCount === 0)
            <div class="alert alert-info shadow-sm">
                <i class="bi bi-info-circle me-1"></i>Không có đơn hàng hoàn thành trong khoảng thời gian đã chọn.
            </div>
        @else
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <p class="text-muted mb-1">Doanh thu</p>
                            <p class="h3 fw-bold text-danger mb-0">{{ number_format((float) $totalRevenue, 0, ',', '.') }} đ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <p class="text-muted mb-1">Đơn hàng hoàn thành</p>
                            <p class="h3 fw-bold mb-0">{{ $orderCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-trophy me-2 text-warning"></i>Top sản phẩm bán chạy
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>Sản phẩm</th><th>Số lượng bán</th><th>Doanh thu</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($topProducts as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $product->name }}</td>
                                    <td>{{ $product->quantity_sold }}</td>
                                    <td>{{ number_format((float) $product->revenue, 0, ',', '.') }} đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
@endsection

@push('scripts')
    <script>
        const modeSelect = document.getElementById('mode');

        function updateReportFields() {
            document.querySelectorAll('[data-filter]').forEach((field) => {
                field.hidden = field.dataset.filter !== modeSelect.value;
            });
        }

        modeSelect.addEventListener('change', updateReportFields);
        updateReportFields();
    </script>
@endpush
