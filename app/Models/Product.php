<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'discount_price',
        'stock',
        'image',
        'featured',
        'status',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'discount_price' => 'decimal:2',
        'featured'       => 'boolean',
        'status'         => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isLowStock(int $threshold = 5): bool
    {
        return $this->stock <= $threshold;
    }
}
