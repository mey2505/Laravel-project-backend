@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Product List</h3>
        <div class="card-tools">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                Create New Product
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                <tr>
                    {{-- Display number (1,2,3,4...) not database ID --}}
                    <td>{{ $products->firstItem() + $loop->index }}</td>

                    <td>{{ $product->name }}</td>

                    <td>{{ $product->sku }}</td>

                    <td>
                        ${{ number_format($product->price, 2) }}
                    </td>

                    <td>
                        {{ $product->stock }}
                    </td>

                    <td>
                        {{ $product->status ? 'Active' : 'Inactive' }}
                    </td>

                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                           class="btn btn-sm btn-info">
                            Edit
                        </a>

                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                              method="POST" 
                              style="display:inline-block;">
                            
                            @csrf
                            @method('DELETE')

                            <button type="submit" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete
                            </button>

                        </form>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center">
                        No products found.
                    </td>
                </tr>

                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        {{ $products->links() }}
    </div>
</div>
@endsection