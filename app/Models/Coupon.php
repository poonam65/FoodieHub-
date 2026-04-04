<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at'       => 'datetime',
        'is_active'        => 'boolean',
        'value'            => 'float',
        'min_order_amount' => 'float',
        'max_discount'     => 'float',
        'usage_limit'      => 'integer',
        'used_count'       => 'integer',
    ];

    // -------------------------------------------------------
    // Check if coupon is valid for given subtotal
    // Returns ['valid' => true/false, 'message' => '...']
    // -------------------------------------------------------
    public function isValid($subtotal): array
    {
        // Check active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'This coupon is inactive!'];
        }

        // Check expiry
        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'This coupon has expired!'];
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'This coupon usage limit has been reached!'];
        }

        // Check minimum order amount
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return [
                'valid'   => false,
                'message' => 'Minimum order of Rs.' . number_format($this->min_order_amount, 2) . ' required!'
            ];
        }

        return ['valid' => true, 'message' => 'Coupon is valid!'];
    }

    // -------------------------------------------------------
    // Calculate actual discount amount for given subtotal
    // -------------------------------------------------------
    public function calculateDiscount($subtotal): float
    {
        if ($this->type === 'percentage') {
            // Calculate percentage discount
            $discount = ($subtotal * $this->value) / 100;

            // Apply max discount cap if set
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            // Fixed discount
            $discount = $this->value;
        }

        // Discount cannot be more than subtotal
        return round(min($discount, $subtotal), 2);
    }

    // -------------------------------------------------------
    // Increment used_count when order is placed
    // Call this from OrderController when order is placed
    // -------------------------------------------------------
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}