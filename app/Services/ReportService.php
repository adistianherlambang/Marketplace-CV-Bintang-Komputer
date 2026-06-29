<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ReturnLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get statistics for the dashboard.
     */
    public function getDashboardStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. Total active products
        $totalProducts = Product::where('is_active', true)->count();

        // 2. Total categories
        $totalCategories = Category::count();

        // 3. Total stock
        $totalStock = Product::sum('stock');

        // 4. Products low on stock
        $lowStockCount = Product::where('stock', '<=', DB::raw('min_stock'))->count();

        // 5. Total sales today (Lunas orders)
        $salesToday = Order::where('status', 'Lunas')
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        // 6. Total sales this month (Lunas orders)
        $salesThisMonth = Order::where('status', 'Lunas')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        // 7. Total invoices
        $invoiceCount = Order::count();

        // 8. Total returns
        $returnCount = ReturnLog::count();

        // 9. Low stock list
        $lowStockProducts = Product::with(['category', 'brand'])
            ->where('stock', '<=', DB::raw('min_stock'))
            ->limit(5)
            ->get();

        // 10. Monthly sales chart data (last 6 months)
        $monthlyChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            $monthLabel = $monthStart->format('M Y');

            $amount = Order::where('status', 'Lunas')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_amount');

            $monthlyChartData[] = [
                'month' => $monthLabel,
                'total' => (float)$amount
            ];
        }

        // 11. Top selling products
        $topProducts = OrderItem::select('product_id', 'item_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereNotNull('product_id')
            ->groupBy('product_id', 'item_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->map(function($p) {
                return [
                    'name' => $p->item_name,
                    'total_sold' => (int) $p->total_qty,
                ];
            })
            ->toArray();

        return [
            'total_products' => $totalProducts,
            'total_categories' => $totalCategories,
            'total_stock' => $totalStock,
            'low_stock_count' => $lowStockCount,
            'sales_today' => $salesToday,
            'sales_this_month' => $salesThisMonth,
            'invoice_count' => $invoiceCount,
            'return_count' => $returnCount,
            'low_stock_products' => $lowStockProducts,
            'monthly_chart_data' => $monthlyChartData,
            'top_products' => $topProducts,
        ];
    }

    /**
     * Get report data for a specific date (Daily).
     */
    public function getDailyReportData(string $date): array
    {
        $orders = Order::with(['customer', 'user', 'items'])
            ->whereDate('created_at', $date)
            ->get();

        $totalSales = $orders->where('status', 'Lunas')->sum('total_amount');
        $totalTransactions = $orders->count();

        return [
            'date' => Carbon::parse($date)->format('d F Y'),
            'orders' => $orders,
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
        ];
    }

    /**
     * Get report data for a specific month (e.g. 2026-06).
     */
    public function getMonthlyReportData(string $yearMonth): array
    {
        $startOfMonth = Carbon::parse($yearMonth . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($yearMonth . '-01')->endOfMonth();

        $orders = Order::with(['customer', 'user'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $totalSales = $orders->where('status', 'Lunas')->sum('total_amount');
        $totalTransactions = $orders->count();

        return [
            'month' => $startOfMonth->format('F Y'),
            'orders' => $orders,
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
        ];
    }

    /**
     * Get report data for a specific year (e.g. 2026).
     */
    public function getYearlyReportData(string $year): array
    {
        $startOfYear = Carbon::parse($year . '-01-01')->startOfYear();
        $endOfYear = Carbon::parse($year . '-01-01')->endOfYear();

        $orders = Order::whereBetween('created_at', [$startOfYear, $endOfYear])->get();
        $totalSales = $orders->where('status', 'Lunas')->sum('total_amount');
        $totalTransactions = $orders->count();

        return [
            'year' => $year,
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
        ];
    }

    /**
     * Get current stock report.
     */
    public function getStockReportData(): array
    {
        $products = Product::with(['category', 'brand', 'supplier'])
            ->orderBy('stock', 'asc')
            ->get();

        $totalStock = $products->sum('stock');
        $totalValue = $products->sum(function ($p) {
            return $p->stock * $p->price_modal;
        });

        return [
            'products' => $products,
            'total_stock' => $totalStock,
            'total_value' => $totalValue,
        ];
    }

    /**
     * Get return logs report.
     */
    public function getReturnReportData(): array
    {
        $returns = ReturnLog::with(['order', 'product'])
            ->orderBy('date', 'desc')
            ->get();

        return [
            'returns' => $returns,
        ];
    }

    /**
     * Get top products report.
     */
    public function getTopProductsReportData(): array
    {
        $products = OrderItem::select('product_id', 'item_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereNotNull('product_id')
            ->groupBy('product_id', 'item_name')
            ->orderByDesc('total_qty')
            ->get();

        return [
            'products' => $products,
        ];
    }
}
