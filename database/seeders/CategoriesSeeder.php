<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
public function run()
{
    $categories = ['Makanan', 'Transport', 'Hiburan', 'Tagihan', 'Lainnya'];
    foreach ($categories as $c) {
        Category::updateOrCreate(['name' => $c]);
    }
}

}
