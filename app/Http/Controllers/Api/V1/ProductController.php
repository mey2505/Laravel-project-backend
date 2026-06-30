<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'latest');

        $products = Product::with(['category', 'reviews'])
            ->where('status', true)
            ->when($request->category, fn ($q, $cat) => $q->whereHas('category', fn ($q) => $q->where('slug', $cat)))
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($request->boolean('featured'), fn ($q) => $q->where('featured', true))
            ->when($request->boolean('on_sale'), fn ($q) => $q->whereNotNull('discount_price'))
            ->when($request->min_price, fn ($q, $min) => $q->where('price', '>=', $min))
            ->when($request->max_price, fn ($q, $max) => $q->where('price', '<=', $max))
            ->when($sort === 'price_asc', fn ($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'name', fn ($q) => $q->orderBy('name', 'asc'))
            ->when($sort === 'latest', fn ($q) => $q->latest())
            ->paginate((int) $request->input('per_page', 15));

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        if (!$product->status) {
            abort(404);
        }

        return new ProductResource($product->load([
            'category',
            'reviews' => fn ($q) => $q->where('is_approved', true)->with('user')->latest(),
        ]));
    }
}
