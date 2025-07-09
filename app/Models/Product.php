<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'product_code', 'category_id', 'price', 'quantity', 'description', 'status', 'color', 'sizes', 'is_active'];
    protected $casts = [
        'sizes' => 'array',
    ];

    public function getSizesAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
    public function setSizesAttribute($value)
    {
        $this->attributes['sizes'] = is_array($value) ? json_encode($value) : $value;
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    // App\Models\Product.php

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'in' => 'Còn hàng',
            'low' => 'Sắp hết hàng',
            'out' => 'Hết hàng',
            default => 'Không xác định',
        };
    }

    public function getStatusClassAttribute()
    {
        return match ($this->status) {
            'in' => 'stock-in',
            'low' => 'stock-low',
            'out' => 'stock-out',
            default => '',
        };
    }
}
