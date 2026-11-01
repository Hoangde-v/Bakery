<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $hasSearched = $request->query->has('mode');
        $mode = $request->query('mode', 'daily');
        $startDate = null;
        $endDate = null;
        $revenue = 0;
        $orderCount = 0;
        $dailyRows = collect();
        $topProducts = collect();

        if ($hasSearched) {
            $filters = $this->validateFilters($request, $mode);
            [$startDate, $endDate] = $this->getDateRange($filters, $mode);

            $completedOrders = $this->completedOrdersBetween($startDate, $endDate);

            // Doanh thu = tổng số lượng * đơn giá của các đơn đã hoàn thành.
            $revenue = (clone $completedOrders)
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')
                ->value('total');

            $orderCount = (clone $completedOrders)->count();

            // Gom doanh thu và số đơn theo ngày để đưa vào file báo cáo.
            $dailyRows = (clone $completedOrders)
                ->selectRaw('DATE(orders.created_at) as period, COUNT(*) as order_count, SUM(quantity * unit_price) as revenue')
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            // Gom số lượng theo sản phẩm rồi xếp từ bán nhiều nhất.
            $topProducts = (clone $completedOrders)
                ->join('products', 'orders.product_id', '=', 'products.id')
                ->selectRaw('products.name, SUM(orders.quantity) as quantity_sold, SUM(orders.quantity * orders.unit_price) as revenue')
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('quantity_sold')
                ->limit(10)
                ->get();

            if ($request->boolean('export') && $orderCount > 0) {
                return $this->downloadCsv(
                    $startDate,
                    $endDate,
                    $revenue,
                    $orderCount,
                    $dailyRows,
                    $topProducts,
                );
            }
        }

        return view('admin.reports.index', [
            'hasSearched' => $hasSearched,
            'mode' => $mode,
            'today' => now()->toDateString(),
            'month' => now()->format('Y-m'),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $revenue,
            'orderCount' => $orderCount,
            'topProducts' => $topProducts,
        ]);
    }

    private function validateFilters(Request $request, string $mode): array
    {
        return $request->validate([
            'mode' => ['required', Rule::in(['daily', 'monthly', 'period'])],
            'date' => ['nullable', Rule::requiredIf($mode === 'daily'), 'date_format:Y-m-d'],
            'month' => ['nullable', Rule::requiredIf($mode === 'monthly'), 'date_format:Y-m'],
            'from' => ['nullable', Rule::requiredIf($mode === 'period'), 'date_format:Y-m-d'],
            'to' => [
                'nullable',
                Rule::requiredIf($mode === 'period'),
                'date_format:Y-m-d',
                Rule::when($mode === 'period', ['after_or_equal:from']),
            ],
        ]);
    }

    private function getDateRange(array $filters, string $mode): array
    {
        if ($mode === 'daily') {
            return [$filters['date'], $filters['date']];
        }

        if ($mode === 'monthly') {
            $firstDay = $filters['month'].'-01';

            return [$firstDay, now()->parse($firstDay)->endOfMonth()->toDateString()];
        }

        return [$filters['from'], $filters['to']];
    }

    private function completedOrdersBetween(string $startDate, string $endDate): Builder
    {
        return Order::query()
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [
                now()->parse($startDate)->startOfDay(),
                now()->parse($endDate)->endOfDay(),
            ]);
    }

    private function downloadCsv(
        string $startDate,
        string $endDate,
        float|string $revenue,
        int $orderCount,
        $dailyRows,
        $topProducts,
    ) {
        $filename = "bao-cao-kinh-doanh-{$startDate}-{$endDate}.csv";

        return response()->streamDownload(function () use ($startDate, $endDate, $revenue, $orderCount, $dailyRows, $topProducts) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['BÁO CÁO KINH DOANH']);
            fputcsv($file, ['Từ ngày', $startDate, 'Đến ngày', $endDate]);
            fputcsv($file, ['Tổng doanh thu', $revenue, 'Đơn hoàn thành', $orderCount]);
            fputcsv($file, []);
            fputcsv($file, ['Ngày', 'Số đơn hoàn thành', 'Doanh thu']);

            foreach ($dailyRows as $row) {
                fputcsv($file, [$row->period, $row->order_count, $row->revenue]);
            }

            fputcsv($file, []);
            fputcsv($file, ['Sản phẩm bán chạy', 'Số lượng bán', 'Doanh thu']);

            foreach ($topProducts as $product) {
                fputcsv($file, [$product->name, $product->quantity_sold, $product->revenue]);
            }

            fclose($file);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
