<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tüm kategorileri getir
     */
    public function index()
    {
        $categories = Category::with('products')->get();

        return response()->json([
            'success' => true,
            'message' => 'Kategoriler başarıyla getirildi',
            'data' => [
                'categories' => $categories
            ],
            'errors' => []
        ]);
    }

    /**
     * Yeni kategori oluştur (Sadece Admin)
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla oluşturuldu',
            'data' => [
                'category' => $category
            ],
            'errors' => []
        ], 201);
    }

    /**
     * Kategori güncelle (Sadece Admin)
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla güncellendi',
            'data' => [
                'category' => $category
            ],
            'errors' => []
        ]);
    }

    /**
     * Kategori sil (Sadece Admin)
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Kategoride ürün var mı kontrol et
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kategoride ürünler bulunduğu için silinemez',
                'data' => null,
                'errors' => ['category_has_products']
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla silindi',
            'data' => null,
            'errors' => []
        ]);
    }
}
