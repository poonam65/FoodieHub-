@extends('layouts.app')
@section('title', 'Rate Your Order')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h2 style="font-family:'Playfair Display',serif" class="mb-2">
                ⭐ Rate Your Order
            </h2>
            <p class="text-muted mb-4">
                Order: <strong>{{ $order->order_number }}</strong>
            </p>

            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                @foreach($order->items as $index => $item)
                @if(!in_array($item->menu_item_id, $reviewedItems))
                <div class="card mb-4 p-4">
                    <input type="hidden"
                        name="reviews[{{ $index }}][menu_item_id]"
                        value="{{ $item->menu_item_id }}">

                    <div class="d-flex gap-3 align-items-center mb-3">
                        @if($item->menuItem->image)
                            <img src="{{ asset('storage/'.$item->menuItem->image) }}"
                                width="70" height="70"
                                style="object-fit:cover;border-radius:12px">
                        @else
                            <div style="width:70px;height:70px;background:#ffd7d7;border-radius:12px;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-utensils text-danger"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $item->menuItem->name }}</h5>
                            <small class="text-muted">
                                Rs.{{ number_format($item->price, 2) }} × {{ $item->quantity }}
                            </small>
                        </div>
                    </div>

                    {{-- Star Rating --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rating *</label>
                        <div class="star-rating" data-index="{{ $index }}">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio"
                                    name="reviews[{{ $index }}][rating]"
                                    value="{{ $i }}"
                                    id="star-{{ $index }}-{{ $i }}"
                                    required>
                                <label for="star-{{ $index }}-{{ $i }}"
                                    title="{{ $i }} star">
                                    ★
                                </label>
                            @endfor
                        </div>
                        <small class="text-muted" id="rating-text-{{ $index }}">
                            Star select karo
                        </small>
                    </div>

                    {{-- Comment --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Comment (Optional)</label>
                        <textarea name="reviews[{{ $index }}][comment]"
                            rows="2"
                            class="form-control"
                            placeholder="Apna experience share karo..."></textarea>
                    </div>
                </div>
                @else
                <div class="card mb-4 p-4 bg-light">
                    <div class="d-flex gap-3 align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $item->menuItem->name }}</h5>
                            <span class="badge bg-success">
                                ✅ Already reviewed
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach

                <button type="submit"
                    class="btn btn-primary btn-lg w-100 rounded-pill py-3">
                    <i class="fas fa-star me-2"></i>Submit Reviews
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
    margin-bottom: 5px;
}
.star-rating input {
    display: none;
}
.star-rating label {
    font-size: 35px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}
.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #f4a261;
}
</style>

<script>
const ratingTexts = {
    1: '😞 Very bad',
    2: '😕 Not good',
    3: '😊 Okay',
    4: '😄 Good',
    5: '🤩 Very good!'
};

document.querySelectorAll('.star-rating input').forEach(function(input) {
    input.addEventListener('change', function() {
        var index = this.closest('.star-rating').dataset.index;
        var text  = document.getElementById('rating-text-' + index);
        if (text) {
            text.textContent = ratingTexts[this.value];
            text.style.color = '#e63946';
            text.style.fontWeight = 'bold';
        }
    });
});
</script>
@endsection