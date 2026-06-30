<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    protected CustomerService $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $customers = $this->service->getPaginated();
        return view('admin.customers.index', compact('customers'));
    }

    // Additional methods (show, update status) would go here
}
