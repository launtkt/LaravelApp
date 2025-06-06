<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['product_id', 'name', 'path', 'is_main'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
