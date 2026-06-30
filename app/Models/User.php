<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Alias used by WishlistController for the raw wishlist rows
     * (so it can create/delete individual entries by product_id).
     */
    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * The products on this user's wishlist, via the wishlists pivot.
     */
    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
