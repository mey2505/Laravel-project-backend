<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $orders = $this->service->getPaginated(
            perPage: (int) $request->input('per_page', 15),
            status: $request->input('status'),
            search: $request->input('search'),
        );

        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order = $this->service->findOrder($order->id);

        if (!$order) {
            abort(404);
        }

        return new OrderResource($order);
    }

    public function updateStatus(UpdateOrderRequest $request, Order $order)
    {
        $this->service->updateStatus($order->id, $request->validated('status'));

        return new OrderResource($this->service->findOrder($order->id));
    }

    public function updatePaymentStatus(UpdateOrderRequest $request, Order $order)
    {
        $this->service->updatePaymentStatus($order->id, $request->validated('payment_status'));

        return new OrderResource($this->service->findOrder($order->id));
    }
}
