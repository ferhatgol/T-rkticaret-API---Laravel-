<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_cart()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/cart');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'cart' => [
                            'id',
                            'user_id',
                            'items' => [
                                '*' => [
                                    'id',
                                    'product_id',
                                    'quantity',
                                    'product'
                                ]
                            ],
                            'total_amount'
                        ]
                    ]
                ]);
    }

    public function test_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $cartData = [
            'product_id' => $product->id,
            'quantity' => 3
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/cart/add', $cartData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'cart_item' => [
                            'id',
                            'cart_id',
                            'product_id',
                            'quantity'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);
    }

    public function test_user_can_update_cart_item_quantity()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $updateData = [
            'product_id' => $product->id,
            'quantity' => 5
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->putJson('/api/cart/update', $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'cart_item' => [
                            'id',
                            'cart_id',
                            'product_id',
                            'quantity'
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 5
        ]);
    }

    public function test_user_can_remove_product_from_cart()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/cart/remove/{$product->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Ürün sepetten çıkarıldı'
                ]);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id
        ]);
    }

    public function test_user_can_clear_cart()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        CartItem::factory()->count(3)->create(['cart_id' => $cart->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson('/api/cart/clear');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Sepet temizlendi'
                ]);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id
        ]);
    }

    public function test_cannot_add_product_with_insufficient_stock()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock_quantity' => 5
        ]);

        $cartData = [
            'product_id' => $product->id,
            'quantity' => 10
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/cart/add', $cartData);

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'Yetersiz stok'
                ]);
    }
}
