@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Review List</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Title</th>
                    <th>Approved</th>
                    <th>Hidden</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->user->name ?? '-' }}</td>
                    <td>{{ $review->product->name ?? '-' }}</td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </td>
                    <td>{{ $review->title }}</td>
                    <td>
                        <span class="badge {{ $review->is_approved ? 'badge-success' : 'badge-warning' }}">
                            {{ $review->is_approved ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $review->is_hidden ? 'badge-danger' : 'badge-success' }}">
                            {{ $review->is_hidden ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        @unless($review->is_approved)
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-success">Approve</button>
                        </form>
                        @endunless
                        <form action="{{ route('admin.reviews.hide', $review) }}" method="POST" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-warning">{{ $review->is_hidden ? 'Unhide' : 'Hide' }}</button>
                        </form>
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this review?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No reviews found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
