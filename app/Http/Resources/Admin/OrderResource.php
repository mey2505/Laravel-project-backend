<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'order_number'      => $this->order_number,
            'subtotal'          => (float) $this->subtotal,
            'tax'               => (float) $this->tax,
            'shipping_fee'      => (float) $this->shipping_fee,
            'total'             => (float) $this->total,
            'status'            => $this->status,
            'payment_status'    => $this->payment_status,
            'payment_method'    => $this->payment_method,
            'shipping_address'  => $this->shipping_address,
            'notes'             => $this->notes,
            'items_count'       => $this->whenCounted('items'),
            'items'             => OrderItemResource::collection($this->whenLoaded('items')),
            'customer'          => $this->when($this->relationLoaded('user'), fn () => $this->user ? [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ] : null),
            'created_at'        => $this->created_at->toIso8601String(),
            'updated_at'        => $this->updated_at->toIso8601String(),
        ];
    }
}
