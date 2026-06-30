<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'order_number'   => $this->order_number,
            'subtotal'       => (float) $this->subtotal,
            'tax'            => (float) $this->tax,
            'shipping_fee'   => (float) $this->shipping_fee,
            'total'          => (float) $this->total,
            'status'         => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'shipping_address'=> $this->shipping_address,
            'notes'          => $this->notes,
            'items'          => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at'     => $this->created_at->toIso8601String(),
        ];
    }
}
