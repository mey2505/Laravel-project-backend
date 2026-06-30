@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Edit Category: {{ $category->name }}</h3>
    </div>
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $category->name) }}">
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" id="description" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>
            
            <div class="form-check">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" class="form-check-input" id="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Active</label>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-info">Update</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-default float-right">Cancel</a>
        </div>
    </form>
</div>
@endsection
