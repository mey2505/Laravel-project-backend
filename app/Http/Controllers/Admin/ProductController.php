<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Fetch paginated products with their categories eager loaded
        $products = $this->service->getPaginated();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // Fetch categories for the select dropdown
        $categories = \App\Models\Category::where('status', 1)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->service->createProduct($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = \App\Models\Category::where('status', 1)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->service->updateProduct($product->id, $request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->service->deleteProduct($product->id);
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
