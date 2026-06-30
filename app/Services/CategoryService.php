<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Str;

class CategoryService
{
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginatedCategories(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function createCategory(array $data)
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        return $this->repository->create($data);
    }

    public function updateCategory(int $id, array $data)
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $category = $this->repository->find($id);
            if ($category && $category->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $category->image));
            }
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        return $this->repository->update($id, $data);
    }

    public function deleteCategory(int $id)
    {
        $category = $this->repository->find($id);

        if ($category && $category->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $category->image));
        }

        return $this->repository->delete($id);
    }

    protected function handleImageUpload(\Illuminate\Http\UploadedFile $file)
    {
        $path = $file->store('categories', 'public');
        return '/storage/' . $path;
    }
}
