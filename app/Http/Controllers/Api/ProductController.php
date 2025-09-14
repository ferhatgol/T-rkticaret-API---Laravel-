<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Filtreleme ve sayfalama ile tüm ürünleri getir
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filtreleri uygula
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sayfalama
        $perPage = $request->get('limit', 20);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Ürünler başarıyla getirildi',
            'data' => [
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem()
                ]
            ],
            'errors' => []
        ]);
    }

    /**
     * Tek ürün detayı getir
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Ürün detayları getirildi',
            'data' => [
                'product' => $product
            ],
            'errors' => []
        ]);
    }

    /**
     * Yeni ürün oluştur (Sadece Admin)
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id
        ]);

        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Ürün başarıyla oluşturuldu',
            'data' => [
                'product' => $product
            ],
            'errors' => []
        ], 201);
    }

    /**
     * Ürün güncelle (Sadece Admin)
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id
        ]);

        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Ürün başarıyla güncellendi',
            'data' => [
                'product' => $product
            ],
            'errors' => []
        ]);
    }

    /**
     * Ürün sil (Sadece Admin)
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ürün başarıyla silindi',
            'data' => null,
            'errors' => []
        ]);
    }
}
