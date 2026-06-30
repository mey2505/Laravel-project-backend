<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->model
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->model->where('slug', $slug)->first();
    }
}
