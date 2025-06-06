<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Áo thun', 'description' => 'Các loại áo thun'],
            ['name' => 'Quần jean', 'description' => 'Các loại quần jean'],
            ['name' => 'Giày dép', 'description' => 'Các loại giày dép'],
        ]);
    }
}
