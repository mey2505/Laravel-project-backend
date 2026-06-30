<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getSalesSummary(string $from, string $to): array
    {
        $orders = Order::whereBetween('created_at', [$from, $to])
            ->where('payment_status', 'paid')
            ->selectRaw('COUNT(*) as total_orders, SUM(total) as revenue, AVG(total) as avg_order_value')
            ->first();

        return [
            'total_orders'    => $orders->total_orders ?? 0,
            'revenue'         => $orders->revenue ?? 0,
            'avg_order_value' => $orders->avg_order_value ?? 0,
        ];
    }

    public function getRevenueByMonth(int $year): array
    {
        return Order::where('payment_status', 'paid')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();
    }

    public function getOrdersByStatus(string $from, string $to): array
    {
        return Order::whereBetween('created_at', [$from, $to])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    public function getTopSellingProducts(int $limit = 10, string $from = null, string $to = null): \Illuminate\Support\Collection
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->when($from && $to, fn ($q) =>
                $q->join('orders', 'order_items.order_id', '=', 'orders.id')
                  ->whereBetween('orders.created_at', [$from, $to])
            )
            ->selectRaw('products.name, SUM(order_items.quantity) as total_sold, SUM(order_items.total) as revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    public function getCustomerGrowth(int $year): array
    {
        return User::whereYear('created_at', $year)
            ->whereDoesntHave('roles', fn ($q) => $q->whereIn('name', ['Super Admin', 'Admin', 'Manager', 'Staff']))
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    public function getLowStockProducts(int $threshold = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Product::where('stock', '<=', $threshold)
            ->where('status', true)
            ->orderBy('stock')
            ->get();
    }

    public function exportOrdersCsv(string $from, string $to): string
    {
        $orders = Order::with('user')
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $rows   = ["Order #,Customer,Email,Total,Status,Payment Status,Date"];
        foreach ($orders as $order) {
            $rows[] = implode(',', [
                $order->order_number,
                $order->user->name ?? 'Guest',
                $order->user->email ?? '-',
                $order->total,
                $order->status,
                $order->payment_status,
                $order->created_at->format('Y-m-d'),
            ]);
        }

        return implode("\n", $rows);
    }
}
