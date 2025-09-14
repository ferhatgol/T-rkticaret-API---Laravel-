<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Uygulamanın veritabanını tohumla
     */
    public function run(): void
    {
        // Admin kullanıcı oluştur
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Normal kullanıcı oluştur
        User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);

        // Kategoriler oluştur (Case study gereksinimi: en az 3 kategori)
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Elektronik ürünler ve aksesuarlar'
            ],
            [
                'name' => 'Giyim',
                'description' => 'Kadın, erkek ve çocuk giyim ürünleri'
            ],
            [
                'name' => 'Ev & Yaşam',
                'description' => 'Ev dekorasyonu ve yaşam ürünleri'
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Her kategori için 5 ürün oluştur
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'name' => $category->name . ' Ürün ' . $i,
                    'description' => $category->name . ' kategorisinde yer alan kaliteli ürün ' . $i,
                    'price' => rand(50, 500),
                    'stock_quantity' => rand(10, 100),
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
