<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 100.00,
            'stock_quantity' => 10
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/orders');

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'order' => [
                            'id',
                            'user_id',
                            'total_amount',
                            'status',
                            'items' => [
                                '*' => [
                                    'id',
                                    'product_id',
                                    'quantity',
                                    'price'
                                ]
                            ]
                        ]
                    ]
                ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => 200.00
        ]);

        // Stok kontrolü
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8
        ]);
    }

    public function test_user_can_list_orders()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        Order::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/orders');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'orders' => [
                            '*' => [
                                'id',
                                'user_id',
                                'total_amount',
                                'status',
                                'created_at'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_user_can_view_order_details()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'order' => [
                            'id',
                            'user_id',
                            'total_amount',
                            'status',
                            'items' => [
                                '*' => [
                                    'id',
                                    'product_id',
                                    'quantity',
                                    'price',
                                    'product'
                                ]
                            ]
                        ]
                    ]
                ]);
    }

    public function test_cannot_create_order_with_empty_cart()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        Cart::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/orders');

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'Sepet boş'
                ]);
    }

    public function test_cannot_create_order_with_insufficient_stock()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock_quantity' => 1
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 5
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/orders');

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'Yetersiz stok: ' . $product->name
                ]);
    }

    public function test_user_cannot_view_other_users_orders()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = auth()->login($user1);
        $order = Order::factory()->create(['user_id' => $user2->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/orders/{$order->id}");

        $response->assertStatus(403);
    }
}
