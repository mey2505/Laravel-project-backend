<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'sku'            => $this->sku,
            'description'    => $this->description,
            'price'          => (float) $this->price,
            'discount_price' => $this->discount_price ? (float) $this->discount_price : null,
            'stock'          => $this->stock,
            'in_stock'       => $this->stock > 0,
            'image'          => $this->image,
            'featured'       => $this->featured,
            'category'       => new CategoryResource($this->whenLoaded('category')),
            'reviews_avg'    => $this->whenLoaded('reviews', fn () =>
                round($this->reviews->where('is_approved', true)->avg('rating'), 1)
            ),
            'reviews_count'  => $this->whenLoaded('reviews', fn () =>
                $this->reviews->where('is_approved', true)->count()
            ),
            'reviews'        => $this->whenLoaded('reviews', fn () =>
                $this->reviews->where('is_approved', true)->map(fn ($r) => [
                    'id'         => $r->id,
                    'rating'     => $r->rating,
                    'title'      => $r->title,
                    'body'       => $r->body,
                    'created_at' => $r->created_at->toIso8601String(),
                    'user'       => $r->relationLoaded('user') && $r->user
                        ? ['name' => $r->user->name]
                        : null,
                ])->values()
            ),
        ];
    }
}
