<?php

namespace App\Repositories;

use App\Models\User;

class CustomerRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function paginateCustomers(int $perPage = 15, ?string $search = null)
    {
        // Fetch only users who do NOT have admin roles
        return $this->model->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Admin', 'Manager', 'Staff']);
            })
            ->withCount('orders')
            ->withSum(['orders as total_spent' => function ($query) {
                $query->where('payment_status', 'paid');
            }], 'total')
            ->when($search, fn ($q, $search) => $q
                ->where(fn ($q) => $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                )
            )
            ->latest()
            ->paginate($perPage);
    }

    public function findCustomerWithOrders(int $id): ?User
    {
        return $this->model
            ->withCount('orders')
            ->withSum(['orders as total_spent' => function ($query) {
                $query->where('payment_status', 'paid');
            }], 'total')
            ->with(['orders' => fn ($q) => $q->with('items.product')->latest()])
            ->find($id);
    }
}
