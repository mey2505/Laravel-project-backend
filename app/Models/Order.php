<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'tax',
        'shipping_fee',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'shipping_address',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'badge-warning',
            'processing' => 'badge-info',
            'shipped'    => 'badge-primary',
            'completed'  => 'badge-success',
            'cancelled'  => 'badge-danger',
            default      => 'badge-secondary',
        };
    }
}
