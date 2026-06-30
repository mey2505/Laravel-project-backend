<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15, ?string $status = null, ?string $search = null)
    {
        return $this->model
            ->with(['user'])
            ->withCount('items')
            ->when($status, fn ($q, $status) => $q->where('status', $status))
            ->when($search, fn ($q, $search) => $q
                ->where(fn ($q) => $q
                    ->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($q) => $q
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                    )
                )
            )
            ->latest()
            ->paginate($perPage);
    }

    public function findWithRelations(int $id): ?Order
    {
        return $this->model
            ->with(['user', 'items.product'])
            ->find($id);
    }
}
