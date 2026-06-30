<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()->wishlistProducts()
            ->with(['category', 'reviews'])
            ->paginate(15);

        return ProductResource::collection($products);
    }

    /**
     * Lightweight list of wishlisted product IDs, used by the storefront to
     * render filled/outline heart icons without fetching full product data.
     */
    public function ids(Request $request)
    {
        return response()->json([
            'data' => $request->user()->wishlistItems()->pluck('product_id'),
        ]);
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();
        
        $wishlistItem = $user->wishlistItems()->where('product_id', $validated['product_id'])->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json(['message' => 'Product removed from wishlist', 'in_wishlist' => false]);
        } else {
            $user->wishlistItems()->create(['product_id' => $validated['product_id']]);
            return response()->json(['message' => 'Product added to wishlist', 'in_wishlist' => true]);
        }
    }
}
