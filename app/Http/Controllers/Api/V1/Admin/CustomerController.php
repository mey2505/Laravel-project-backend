<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CustomerResource;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected CustomerService $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $customers = $this->service->getPaginated(
            perPage: (int) $request->input('per_page', 15),
            search: $request->input('search'),
        );

        return CustomerResource::collection($customers);
    }

    public function show(User $customer)
    {
        $customer = $this->service->findCustomer($customer->id);

        if (!$customer) {
            abort(404);
        }

        return new CustomerResource($customer);
    }
}
