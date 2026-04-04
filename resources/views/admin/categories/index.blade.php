@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Categories</h4>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>Add Category
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}" width="50" height="50" style="object-fit:cover;border-radius:8px">
                        @else
                            <div style="width:50px;height:50px;background:#ffd7d7;border-radius:8px;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-tags text-danger"></i>
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td><span class="badge bg-info">{{ $category->menu_items_count }} items</span></td>
                    <td>
                        @if($category->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}"
                            class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Delete karna chahte ho?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Koi category nahi hai</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $categories->links() }}
@endsection