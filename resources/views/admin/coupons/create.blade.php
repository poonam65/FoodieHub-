@extends('layouts.admin')
@section('title', 'Add Coupon')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Add Coupon</h4>
    <a href="{{ route('admin.coupons.index') }}"
        class="btn btn-outline-secondary rounded-pill px-4">Back</a>
</div>

<div class="card p-4" style="max-width:600px">
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Coupon Code *</label>
            <input type="text" name="code"
                class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code') }}"
                placeholder="e.g. SAVE20"
                style="text-transform:uppercase"
                required>
            <small class="text-muted">Automatically uppercase ho jayega</small>
            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Discount Type *</label>
                <select name="type" class="form-select" required>
                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                        Percentage (%)
                    </option>
                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>
                        Fixed Amount (Rs.)
                    </option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Discount Value *</label>
                <input type="number" name="value"
                    class="form-control @error('value') is-invalid @enderror"
                    value="{{ old('value') }}"
                    placeholder="e.g. 20"
                    step="0.01" required>
                @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Min Order Amount</label>
                <input type="number" name="min_order_amount"
                    class="form-control"
                    value="{{ old('min_order_amount', 0) }}"
                    placeholder="e.g. 200"
                    step="0.01">
                <small class="text-muted">0 = no minimum</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Max Discount (Rs.)</label>
                <input type="number" name="max_discount"
                    class="form-control"
                    value="{{ old('max_discount') }}"
                    placeholder="e.g. 100"
                    step="0.01">
                <small class="text-muted">Percentage type ke liye</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Usage Limit</label>
                <input type="number" name="usage_limit"
                    class="form-control"
                    value="{{ old('usage_limit') }}"
                    placeholder="e.g. 100">
                <small class="text-muted">Khali chhodo = unlimited</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Expiry Date</label>
                <input type="date" name="expires_at"
                    class="form-control"
                    value="{{ old('expires_at') }}"
                    min="{{ date('Y-m-d') }}">
                <small class="text-muted">Khali chhodo = no expiry</small>
            </div>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input type="checkbox" name="is_active"
                    class="form-check-input" id="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary px-5 rounded-pill">
            Save Coupon
        </button>
    </form>
</div>
@endsection