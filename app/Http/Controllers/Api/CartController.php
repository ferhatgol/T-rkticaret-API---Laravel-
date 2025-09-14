<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Kullanıcının sepetini getir
     */
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart()->with(['cartItems.product.category'])->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
            $cart->load(['cartItems.product.category']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sepet bilgileri getirildi',
            'data' => [
                'cart' => $cart,
                'total_amount' => $cart->total_amount,
                'total_items' => $cart->total_items
            ],
            'errors' => []
        ]);
    }

    /**
     * Sepete ürün ekle
     */
    public function add(AddToCartRequest $request)
    {
        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // Stok durumunu kontrol et
        if (!$product->isInStock($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'Yeterli stok bulunmuyor',
                'data' => null,
                'errors' => ['insufficient_stock']
            ], 422);
        }

        // Sepeti getir veya oluştur
        $cart = $user->cart()->first();
        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        // Ürün sepette zaten var mı kontrol et
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Miktarı güncelle
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if (!$product->isInStock($newQuantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yeterli stok bulunmuyor',
                    'data' => null,
                    'errors' => ['insufficient_stock']
                ], 422);
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Yeni sepet öğesi oluştur
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        $cart->load(['cartItems.product.category']);

        return response()->json([
            'success' => true,
            'message' => 'Ürün sepete eklendi',
            'data' => [
                'cart' => $cart,
                'total_amount' => $cart->total_amount,
                'total_items' => $cart->total_items
            ],
            'errors' => []
        ]);
    }

    /**
     * Sepet öğesi miktarını güncelle
     */
    public function update(UpdateCartRequest $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Sepet bulunamadı',
                'data' => null,
                'errors' => ['cart_not_found']
            ], 404);
        }

        $cartItem = $cart->cartItems()->where('product_id', $request->product_id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Ürün sepette bulunamadı',
                'data' => null,
                'errors' => ['product_not_in_cart']
            ], 404);
        }

        $product = $cartItem->product;

        // Stok durumunu kontrol et
        if (!$product->isInStock($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'Yeterli stok bulunmuyor',
                'data' => null,
                'errors' => ['insufficient_stock']
            ], 422);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        $cart->load(['cartItems.product.category']);

        return response()->json([
            'success' => true,
            'message' => 'Sepet güncellendi',
            'data' => [
                'cart' => $cart,
                'total_amount' => $cart->total_amount,
                'total_items' => $cart->total_items
            ],
            'errors' => []
        ]);
    }

    /**
     * Sepetten ürün çıkar
     */
    public function remove($productId)
    {
        $user = Auth::user();
        $cart = $user->cart()->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Sepet bulunamadı',
                'data' => null,
                'errors' => ['cart_not_found']
            ], 404);
        }

        $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Ürün sepette bulunamadı',
                'data' => null,
                'errors' => ['product_not_in_cart']
            ], 404);
        }

        $cartItem->delete();
        $cart->load(['cartItems.product.category']);

        return response()->json([
            'success' => true,
            'message' => 'Ürün sepetten çıkarıldı',
            'data' => [
                'cart' => $cart,
                'total_amount' => $cart->total_amount,
                'total_items' => $cart->total_items
            ],
            'errors' => []
        ]);
    }

    /**
     * Sepeti temizle
     */
    public function clear()
    {
        $user = Auth::user();
        $cart = $user->cart()->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Sepet bulunamadı',
                'data' => null,
                'errors' => ['cart_not_found']
            ], 404);
        }

        $cart->clear();

        return response()->json([
            'success' => true,
            'message' => 'Sepet temizlendi',
            'data' => [
                'cart' => $cart,
                'total_amount' => 0,
                'total_items' => 0
            ],
            'errors' => []
        ]);
    }
}
