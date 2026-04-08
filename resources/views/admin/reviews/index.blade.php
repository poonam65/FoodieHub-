@extends('layouts.admin')
@section('title', 'Reviews')

@section('content')
<h4 class="mb-4">Customer Reviews</h4>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td><strong>{{ $review->user->name }}</strong></td>
                    <td>{{ $review->menuItem->name }}</td>
                    <td>
                        <span class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </span>
                        <small class="text-muted">({{ $review->rating }}/5)</small>
                    </td>
                    <td>
                        {{ Str::limit($review->comment, 50) ?? '—' }}
                    </td>
                    <td>
                        @if($review->is_approved)
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>{{ $review->created_at->format('d M Y') }}</td>
                    <td>
                        <form action="{{ route('admin.reviews.toggle', $review) }}"
                            method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-{{ $review->is_approved ? 'warning' : 'success' }} me-1">
                                {{ $review->is_approved ? 'Hide' : 'Approve' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.reviews.destroy', $review) }}"
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
                    <td colspan="7" class="text-center text-muted py-4">
                        "No reviews yet" 
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $reviews->links() }}
@endsection