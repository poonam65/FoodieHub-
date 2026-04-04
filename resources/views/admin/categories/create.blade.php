@extends('layouts.admin')
@section('title', 'Add Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Add Category</h4>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        Back
    </a>
</div>

<div class="card p-4" style="max-width:600px">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Category Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="mb-4">
            <div class="form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary px-5 rounded-pill">Save Category</button>
    </form>
</div>
@endsection