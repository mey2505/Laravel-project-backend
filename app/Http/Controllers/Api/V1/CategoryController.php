<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', true)
            ->withCount(['products' => fn ($q) => $q->where('status', true)])
            ->get();

        return CategoryResource::collection($categories);
    }

    public function show(Category $category)
    {
        if (!$category->status) {
            abort(404);
        }

        return new CategoryResource($category->loadCount(['products' => fn ($q) => clone $q->where('status', true)]));
    }
}
