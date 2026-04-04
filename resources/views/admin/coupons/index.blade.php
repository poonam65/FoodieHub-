@extends('layouts.admin')
@section('title', 'Coupons')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Coupons</h4>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>Add Coupon
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Order</th>
                    <th>Used</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <strong class="text-danger">{{ $coupon->code }}</strong>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ ucfirst($coupon->type) }}</span>
                    </td>
                    <td>
                        @if($coupon->type === 'percentage')
                            <strong>{{ $coupon->value }}%</strong>
                        @else
                            <strong>Rs.{{ number_format($coupon->value, 2) }}</strong>
                        @endif
                    </td>
                    <td>Rs.{{ number_format($coupon->min_order_amount, 2) }}</td>
                    <td>
                        {{ $coupon->used_count }}
                        @if($coupon->usage_limit)
                            / {{ $coupon->usage_limit }}
                        @else
                            / Unlimited
                        @endif
                    </td>
                    <td>
                        @if($coupon->expires_at)
                            {{ $coupon->expires_at->format('d M Y') }}
                            @if($coupon->expires_at->isPast())
                                <span class="badge bg-danger">Expired</span>
                            @endif
                        @else
                            <span class="text-muted">No expiry</span>
                        @endif
                    </td>
                    <td>
                        @if($coupon->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.coupons.edit', $coupon) }}"
                            class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}"
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
                    <td colspan="8" class="text-center text-muted py-4">
                        Koi coupon nahi hai
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $coupons->links() }}
@endsection