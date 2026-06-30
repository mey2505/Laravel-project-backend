<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        // Admin view: include out-of-stock / inactive products too.
        $products = Product::with('category')
            ->when($request->search, fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->when($request->category, fn ($q, $cat) => $q->whereHas('category', fn ($q) => $q->where('slug', $cat)))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $product = $this->service->createProduct($validated);

        return (new ProductResource($product->load('category')))
            ->additional(['message' => 'Product created successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product);

        $this->service->updateProduct($product->id, $validated);

        return (new ProductResource($product->refresh()->load('category')))
            ->additional(['message' => 'Product updated successfully.']);
    }

    public function destroy(Product $product)
    {
        $this->service->deleteProduct($product->id);

        return response()->json(['message' => 'Product deleted successfully.']);
    }

    protected function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'sku'            => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product?->id)],
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock'          => 'required|integer|min:0',
            'featured'       => 'boolean',
            'status'         => 'boolean',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
    }
}
