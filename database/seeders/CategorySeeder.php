<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{

    private array $categoryNames = [
        'Payments',
        'Account Management',
        'Technical Issues',
        'Security',
    ];


    public function run(): void
    {
        foreach ($this->categoryNames as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);
        }
    }
}
