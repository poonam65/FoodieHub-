@extends('layouts.admin')
@section('title', 'Edit Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Edit Category</h4>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Back</a>
</div>

<div class="card p-4" style="max-width:600px">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-semibold">Category Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $category->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Image</label>
            @if($category->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$category->image) }}" width="80" height="80"
                        style="object-fit:cover;border-radius:8px">
                </div>
            @endif
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="mb-4">
            <div class="form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                    value="1" {{ $category->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary px-5 rounded-pill">Update Category</button>
    </form>
</div>
@endsection