<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->model
            ->with(['category'])
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%"))
            ->latest()
            ->paginate($perPage);
    }
}
