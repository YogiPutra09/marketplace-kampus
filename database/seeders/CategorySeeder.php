<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Buku & Modul Kuliah',
            'Elektronik & Aksesoris PC',
            'Peralatan Kos & Rumah Tangga',
            'Pakaian & Fashion Kampus',
            'Alat Tulis & Gambar',
            'Kendaraan & Aksesoris',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }
    }
}