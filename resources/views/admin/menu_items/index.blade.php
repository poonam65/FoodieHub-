@extends('layouts.admin')
@section('title', 'Menu Items')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Menu Items</h4>
    <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>Add Item
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
                    <th>Category</th>
                    <th>Price</th>
                    <th>Available</th>
                    <th>Featured</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" width="50" height="50"
                                style="object-fit:cover;border-radius:8px">
                        @else
                            <div style="width:50px;height:50px;background:#ffd7d7;border-radius:8px;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-utensils text-danger"></i>
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td><span class="badge bg-warning text-dark">{{ $item->category->name ?? 'N/A' }}</span></td>
                    <td><strong class="text-danger">Rs.{{ number_format($item->price, 2) }}</strong></td>
                    <td>
                        @if($item->is_available)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        @if($item->is_featured)
                            <span class="badge bg-warning text-dark">Featured</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.menu-items.edit', $item) }}"
                            class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.menu-items.destroy', $item) }}"
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
                    <td colspan="8" class="text-center text-muted py-4">Koi menu item nahi hai</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $items->links() }}
@endsection