@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Order List</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                    <td>${{ number_format($order->total, 2) }}</td>
                    <td>
                        <span class="badge {{ $order->status_badge }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $orders->links() }}
    </div>
</div>
@endsection
