@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <h2 style="font-family:'Playfair Display',serif" class="mb-4">
        My Orders
    </h2>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Koi order nahi hai abhi</h4>
            <a href="{{ route('menu.index') }}" class="btn btn-primary mt-3 rounded-pill px-5">
                Menu Dekho
            </a>
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>{{ $order->items->count() }} items</td>
                            <td>
                                <strong class="text-danger">
                                    Rs.{{ number_format($order->total, 2) }}
                                </strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order->order_number) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>

                                    {{-- ✅ Review button -- sirf delivered orders ke liye --}}
    @if($order->status === 'delivered')
        <a href="{{ route('reviews.create', $order->order_number) }}"
            class="btn btn-sm btn-outline-warning rounded-pill">
            <i class="fas fa-star me-1"></i> Review
        </a>
    @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection