<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        $category = Category::factory()->create();
        Product::factory()->count(5)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'products' => [
                            '*' => [
                                'id',
                                'name',
                                'description',
                                'price',
                                'stock_quantity',
                                'category_id',
                                'category'
                            ]
                        ],
                        'pagination'
                    ]
                ]);
    }

    public function test_can_show_single_product()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'product' => [
                            'id',
                            'name',
                            'description',
                            'price',
                            'stock_quantity',
                            'category_id',
                            'category'
                        ]
                    ]
                ]);
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = auth()->login($admin);
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock_quantity' => 50,
            'category_id' => $category->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/products', $productData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'product' => [
                            'id',
                            'name',
                            'description',
                            'price',
                            'stock_quantity',
                            'category_id'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99
        ]);
    }

    public function test_admin_can_update_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = auth()->login($admin);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $updateData = [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 149.99,
            'stock_quantity' => 75,
            'category_id' => $category->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'product' => [
                            'id',
                            'name',
                            'description',
                            'price',
                            'stock_quantity',
                            'category_id'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 149.99
        ]);
    }

    public function test_admin_can_delete_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = auth()->login($admin);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Ürün başarıyla silindi'
                ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }

    public function test_regular_user_cannot_create_product()
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = auth()->login($user);
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock_quantity' => 50,
            'category_id' => $category->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/products', $productData);

        $response->assertStatus(403);
    }

    public function test_can_filter_products_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $response = $this->getJson("/api/products?category_id={$category1->id}");

        $response->assertStatus(200);
        
        $data = $response->json('data.products');
        $this->assertCount(3, $data);
    }

    public function test_can_search_products()
    {
        $category = Category::factory()->create();
        Product::factory()->create([
            'name' => 'iPhone 13',
            'category_id' => $category->id
        ]);
        Product::factory()->create([
            'name' => 'Samsung Galaxy',
            'category_id' => $category->id
        ]);

        $response = $this->getJson('/api/products?search=iPhone');

        $response->assertStatus(200);
        
        $data = $response->json('data.products');
        $this->assertCount(1, $data);
        $this->assertEquals('iPhone 13', $data[0]['name']);
    }
}
