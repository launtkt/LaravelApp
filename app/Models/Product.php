<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

protected $fillable = [
    'name', 'product_code', 'category_id', 'price', 'quantity', 'description',
    'status', 'image1', 'image2', 'image3', 'image4', 'color', 'sizes'
];

// Accessor & Mutator cho sizes
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

}
