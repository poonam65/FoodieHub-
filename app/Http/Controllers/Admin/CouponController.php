<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;          
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);  // ✅ $coupons not $conpon
        return view('admin.coupons.index', compact('coupons')); // ✅ 'coupons'
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'required|string|unique:coupons,code',
            'type'             => 'required|in:percentage,fixed', // ✅ Fixed 'in::precentage'
            'value'            => 'required|numeric|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',       // ✅ nullable
            'max_discount'     => 'nullable|numeric|min:0',       // ✅ nullable
            'usage_limit'      => 'nullable|integer|min:1',       // ✅ nullable
            'expires_at'       => 'nullable|date|after:today',    // ✅ nullable
        ]);

        Coupon::create([                                          // ✅ Capital C
            'code'             => strtoupper($request->code),
            'type'             => $request->type,
            'value'            => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount'     => $request->max_discount,
            'usage_limit'      => $request->usage_limit,
            'expires_at'       => $request->expires_at,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created!');
    }

    public function edit(Coupon $coupon)                          // ✅ Capital C
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)     // ✅ Capital C
    {
        $request->validate([
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at'       => 'nullable|date',
        ]);

        $coupon->update([
            'type'             => $request->type,
            'value'            => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount'     => $request->max_discount,
            'usage_limit'      => $request->usage_limit,
            'expires_at'       => $request->expires_at,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated!');
    }

    public function destroy(Coupon $coupon)                      // ✅ Capital C
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted!');
    }
}