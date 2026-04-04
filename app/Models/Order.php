<?php

namespace App\Models;

use GrahamCampbell\ResultType\Success;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
   use HasFactory;
protected $fillable = [
    'user_id', 'order_number', 'customer_name', 'customer_email',
    'customer_phone', 'delivery_address', 'subtotal',
    'delivery_fee', 'discount', 'coupon_code', 'total', 'status', 'notes'
];
  public function user ()
  {
    return $this-> belongsTO(user::class);
  }

  public function items()
  {
    return $this->hasMany(OrderItem::class);
  }

  public static function generateOrderNumber(){
    return 'ORD-' .strtoupper(uniqid());
  }

 public function getStautsBadgeAttribute(){
    return match ($this->status){
        'pemding'=>'warning',
        'confimed'=>'info',
        'preparing'=>'primary',
        'out_for_delivery'=>'secondary',
        'delivered'=>'Success',
        'cancelled'=>'danger',
        default=>'secondary'
    };
 }

 public function reviews(){
  return $this->hasMany(Review::class);
 }

}
