<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orderItem extends Model
{
    protected $fillable = ['order_id','menu_item_id','quantity','price','subtotal'];

    public function menuItem()
    {
        return $this->belongsTo(menuItem::class);
    }

    public function order() 
    {
        return $this->belongsTo(order::class);
    }
}
