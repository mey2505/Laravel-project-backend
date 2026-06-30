@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Customer List</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->is_active ? 'Active' : 'Banned' }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">View Profile</a>
                        <button class="btn btn-sm btn-warning">Toggle Status</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $customers->links() }}
    </div>
</div>
@endsection
