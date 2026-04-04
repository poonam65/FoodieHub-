<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_item_id',
        'order_id',
        'rating',
        'comment',
        'is_approved',
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function menuItem(){
        return $this->belongsTo(menuItem::class);
    }

    public function order(){
        return $this->belongsTo(order::class);
    }
}
