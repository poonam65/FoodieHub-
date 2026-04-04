<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MenuItem;  // <- add this line

class Category extends Model {
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    public function menuItems() {
        return $this->hasMany(MenuItem::class);
    }

    public function getRouteKeyName() {
        return 'slug';
    }
}