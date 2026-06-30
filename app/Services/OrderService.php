<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected OrderRepository $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(int $perPage = 15, ?string $status = null, ?string $search = null)
    {
        return $this->repository->paginate($perPage, $status, $search);
    }

    public function findOrder(int $id): ?Order
    {
        return $this->repository->findWithRelations($id);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return DB::transaction(function () use ($id, $status) {
            return $this->repository->update($id, ['status' => $status]);
        });
    }

    public function updatePaymentStatus(int $id, string $paymentStatus): bool
    {
        return DB::transaction(function () use ($id, $paymentStatus) {
            return $this->repository->update($id, ['payment_status' => $paymentStatus]);
        });
    }
}
