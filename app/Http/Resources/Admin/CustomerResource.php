<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'email_verified_at'  => $this->email_verified_at?->toIso8601String(),
            'is_active'          => (bool) ($this->is_active ?? true),
            'orders_count'       => $this->whenCounted('orders'),
            'total_spent'        => (float) ($this->total_spent ?? 0),
            'orders'             => $this->when($this->relationLoaded('orders'), fn () => $this->orders->map(fn ($order) => [
                'id'             => $order->id,
                'order_number'   => $order->order_number,
                'total'          => (float) $order->total,
                'status'         => $order->status,
                'payment_status' => $order->payment_status,
                'items'          => OrderItemResource::collection($order->items),
                'created_at'     => $order->created_at->toIso8601String(),
            ])),
            'created_at'         => $this->created_at->toIso8601String(),
        ];
    }
}
