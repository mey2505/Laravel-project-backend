<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $orders = $this->service->getPaginated();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order = $this->service->findOrder($order->id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(UpdateOrderRequest $request, Order $order)
    {
        $this->service->updateStatus($order->id, $request->validated('status'));
        return back()->with('success', 'Order status updated successfully.');
    }

    public function updatePaymentStatus(UpdateOrderRequest $request, Order $order)
    {
        $this->service->updatePaymentStatus($order->id, $request->validated('payment_status'));
        return back()->with('success', 'Payment status updated successfully.');
    }
}
