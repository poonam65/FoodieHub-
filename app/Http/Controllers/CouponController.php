<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        // Step 1: Check cart is not empty
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('coupon_error', 'Your cart is empty!');
        }

        // Step 2: Calculate subtotal from cart
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Step 3: Find coupon by code (case insensitive)
        $coupon = Coupon::where('code', strtoupper($request->coupon_code))
                        ->where('is_active', true)
                        ->first();

        // Step 4: Coupon not found
        if (!$coupon) {
            return back()->with('coupon_error', 'Invalid coupon code!');
        }

        // Step 5: Check expiry
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return back()->with('coupon_error', 'This coupon has expired!');
        }

        // Step 6: Check usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return back()->with('coupon_error', 'This coupon usage limit has been reached!');
        }

        // Step 7: Check minimum order amount
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return back()->with('coupon_error', 
                'Minimum order of Rs.' . number_format($coupon->min_order_amount, 2) . ' required for this coupon!'
            );
        }

        // Step 8: Calculate discount correctly
        if ($coupon->type === 'percentage') {
            // e.g. 20% of subtotal
            $discount = ($subtotal * $coupon->value) / 100;

            // Apply max discount cap if set
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            // Fixed amount discount
            $discount = $coupon->value;
        }

        // Step 9: Discount cannot exceed subtotal
        $discount = min($discount, $subtotal);

        // Step 10: Save coupon to session
        session()->put('coupon', [
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => round($discount, 2),
        ]);

        return back()->with('coupon_success', 
            'Coupon applied! Rs.' . number_format($discount, 2) . ' discount applied!'
        );
    }

    public function remove()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed!');
    }
}