<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        // Admin view: include inactive categories too, with product counts.
        $categories = Category::withCount('products')
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return CategoryResource::collection($categories);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category->loadCount('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'status'      => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $category = $this->service->createCategory($validated);

        return (new CategoryResource($category))
            ->additional(['message' => 'Category created successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => 'nullable|string',
            'status'      => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->service->updateCategory($category->id, $validated);

        return (new CategoryResource($category->refresh()))
            ->additional(['message' => 'Category updated successfully.']);
    }

    public function destroy(Category $category)
    {
        $this->service->deleteCategory($category->id);

        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
