<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\CustomerRepository;

class CustomerService
{
    protected CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(int $perPage = 15, ?string $search = null)
    {
        return $this->repository->paginateCustomers($perPage, $search);
    }

    public function findCustomer(int $id): ?User
    {
        return $this->repository->findCustomerWithOrders($id);
    }
}
