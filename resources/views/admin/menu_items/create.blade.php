@extends('layouts.admin')
@section('title', 'Add Menu Item')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Add Menu Item</h4>
    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Back</a>
</div>

<div class="card p-4" style="max-width:700px">
    <form action="{{ route('admin.menu-items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Item Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Category *</label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Price *</label>
            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                value="{{ old('price') }}" step="0.01" required>
            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="mb-4 d-flex gap-4">
            <div class="form-check">
                <input type="checkbox" name="is_available" class="form-check-input" id="is_available" value="1" checked>
                <label class="form-check-label" for="is_available">Available</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1">
                <label class="form-check-label" for="is_featured">Featured</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary px-5 rounded-pill">Save Item</button>
    </form>
</div>
@endsection