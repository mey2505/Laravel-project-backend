<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                'products_count'   => Product::count(),
                'categories_count' => Category::count(),
                'orders_count'     => Order::count(),
                'customers_count'  => User::where('role', 'Customer')->count(),
                'low_stock_count'  => Product::where('stock', '<=', 5)->count(),
                'revenue_total'    => (float) Order::where('payment_status', 'paid')->sum('total'),
                'recent_orders'    => Order::with('user')->latest()->take(5)->get(),
            ],
        ]);
    }
}
