@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Create New Product</h3>
    </div>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Discount Price ($)</label>
                    <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ old('discount_price') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                </div>
                <div class="col-md-8 form-group">
                    <label>Image</label>
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" accept="image/*">
                        <label class="custom-file-label">Choose file</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch custom-control-inline">
                    <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="status">Active</label>
                </div>
                <div class="custom-control custom-switch custom-control-inline">
                    <input type="checkbox" class="custom-control-input" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="featured">Featured Product</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-default float-right">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $('.custom-file-input').on('change', function() { 
       var fileName = $(this).val().split('\\').pop(); 
       $(this).next('.custom-file-label').addClass("selected").html(fileName); 
    });
</script>
@endpush
