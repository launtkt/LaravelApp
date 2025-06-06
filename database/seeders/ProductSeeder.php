<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Áo thun nữ',
                'category_id' => 1,
                'price' => 299000,
                'quantity' => 500,
                'status' => 'Còn hàng',
                'product_code' => '01001',
                'image' => 'aopolotintuc.png',
                'description' => 'Áo thun nữ thời trang',
                'sizes' => json_encode(['M', 'L']),
                'color' => 'white'
            ],
            [
                'name' => 'Quần jean nam',
                'category_id' => 2,
                'price' => 499000,
                'quantity' => 200,
                'status' => 'Còn hàng',
                'product_code' => '02001',
                'image' => null,
                'description' => 'Quần jean nam cá tính',
                'sizes' => json_encode(['L']),
                'color' => 'blue'
            ]
        ]);
    }
}
