@extends('layouts.admin')

@section('title', 'Order #{{ $order->order_number }}')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Order Items</h3>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" class="text-right"><strong>Subtotal</strong></td><td>${{ number_format($order->subtotal, 2) }}</td></tr>
                        <tr><td colspan="3" class="text-right"><strong>Tax</strong></td><td>${{ number_format($order->tax, 2) }}</td></tr>
                        <tr><td colspan="3" class="text-right"><strong>Shipping</strong></td><td>${{ number_format($order->shipping_fee, 2) }}</td></tr>
                        <tr><td colspan="3" class="text-right"><strong>Total</strong></td><td><strong>${{ number_format($order->total, 2) }}</strong></td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Order Details</h3></div>
            <div class="card-body">
                <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                <hr>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label>Order Status</label>
                        <select name="status" class="form-control">
                            @foreach(['pending','processing','shipped','completed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                </form>
                <hr>
                <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label>Payment Status</label>
                        <select name="payment_status" class="form-control">
                            @foreach(['pending','paid','failed','refunded'] as $p)
                            <option value="{{ $p }}" {{ $order->payment_status === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info btn-block">Update Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
