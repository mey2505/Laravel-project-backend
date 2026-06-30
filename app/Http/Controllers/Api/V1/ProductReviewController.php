<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index(Product $product)
    {
        $reviews = $product->reviews()->where('is_approved', true)->with('user')->paginate(10);
        return ReviewResource::collection($reviews);
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        // Check if user has bought the product
        $hasBought = $request->user()->orders()->whereHas('items', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->exists();

        if (!$hasBought) {
            abort(403, 'You can only review products you have purchased.');
        }

        // Check if user already reviewed
        if ($product->reviews()->where('user_id', $request->user()->id)->exists()) {
            abort(422, 'You have already reviewed this product.');
        }

        $review = $product->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_approved' => false, // Require admin approval
        ]);

        return new ReviewResource($review);
    }
}
