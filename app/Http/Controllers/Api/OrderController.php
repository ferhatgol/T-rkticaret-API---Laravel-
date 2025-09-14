<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Yeni sipariş oluştur
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->with(['cartItems.product'])->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Sepetiniz boş',
                'data' => null,
                'errors' => ['empty_cart']
            ], 422);
        }

        // Tüm ürünler için stok durumunu kontrol et
        foreach ($cart->cartItems as $cartItem) {
            if (!$cartItem->product->isInStock($cartItem->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => $cartItem->product->name . ' ürünü için yeterli stok bulunmuyor',
                    'data' => null,
                    'errors' => ['insufficient_stock']
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            // Sipariş oluştur
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $cart->total_amount,
                'status' => 'pending'
            ]);

            // Sipariş öğelerini oluştur ve stok güncelle
            foreach ($cart->cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price
                ]);

                // Stok azalt
                $cartItem->product->decreaseStock($cartItem->quantity);
            }

            // Sepeti temizle
            $cart->clear();

            DB::commit();

            $order->load(['orderItems.product']);

            return response()->json([
                'success' => true,
                'message' => 'Sipariş başarıyla oluşturuldu',
                'data' => [
                    'order' => $order
                ],
                'errors' => []
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Sipariş oluşturulurken bir hata oluştu',
                'data' => null,
                'errors' => ['order_creation_failed']
            ], 500);
        }
    }

    /**
     * Kullanıcının siparişlerini getir
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['orderItems.product'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Siparişler başarıyla getirildi',
            'data' => [
                'orders' => $orders
            ],
            'errors' => []
        ]);
    }

    /**
     * Tek sipariş detayı getir
     */
    public function show($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with(['orderItems.product'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Sipariş detayları getirildi',
            'data' => [
                'order' => $order
            ],
            'errors' => []
        ]);
    }
}
