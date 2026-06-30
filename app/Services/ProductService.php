<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function createProduct(array $data)
    {
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        $data['featured'] = $data['featured'] ?? false;
        $data['status'] = $data['status'] ?? false;

        return \App\Models\Product::create($data);
    }

    public function updateProduct(int $id, array $data)
    {
        $product = \App\Models\Product::findOrFail($id);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if it exists
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
            }
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        $data['featured'] = $data['featured'] ?? false;
        $data['status'] = $data['status'] ?? false;

        $product->update($data);
        return $product;
    }

    public function deleteProduct(int $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
        }

        return $product->delete();
    }

    protected function handleImageUpload(\Illuminate\Http\UploadedFile $file)
    {
        $path = $file->store('products', 'public');
        return '/storage/' . $path;
    }
}
