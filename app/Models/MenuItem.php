<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'image', 'is_available', 'is_featured'
    ];

    // ✅ Category relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ✅ OrderItems relationship — ye missing tha!
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ✅ Reviews relationship
    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // ✅ Average rating
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    // ✅ Total reviews
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}