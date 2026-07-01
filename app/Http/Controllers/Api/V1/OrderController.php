<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items.product')->latest()->paginate(10);
        return OrderResource::collection($orders);
    }

    public function show(Order $order, Request $request)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        return new OrderResource($order->load('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    abort(422, "Insufficient stock for product: {$product->name}");
                }

                $product->decrement('stock', $item['quantity']);

                $price = $product->discount_price ?? $product->price;
                $total = $price * $item['quantity'];
                $subtotal += $total;

                $orderItems[] = new OrderItem([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $price,
                    'quantity' => $item['quantity'],
                    'total' => $total,
                ]);
            }

            $taxRate = \App\Models\Setting::getValue('tax_rate', 0);
            $shippingFee = \App\Models\Setting::getValue('shipping_fee', 0);
            
            $tax = $subtotal * ($taxRate / 100);
            $totalAmount = $subtotal + $tax + $shippingFee;

            $order = $request->user()->orders()->create([
                'order_number' => \App\Models\Setting::getValue('order_prefix', 'ORD-') . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_fee' => $shippingFee,
                'total' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
            ]);

            $order->items()->saveMany($orderItems);

            // Notify admins
            $admins = \App\Models\User::role(['Super Admin', 'Admin'])->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewOrderNotification($order));

            return $order;
        });

        return new OrderResource($order->load('items'));
    }
}
