@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Category List</h3>
        <div class="card-tools">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Create New</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $categories->links() }}
    </div>
</div>
@endsection
