<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Kullanıcının bu isteği yapmaya yetkili olup olmadığını belirle
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * İsteğe uygulanacak doğrulama kurallarını al
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    /**
     * Doğrulayıcı hataları için özel mesajları al
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ürün adı zorunludur',
            'name.min' => 'Ürün adı en az 3 karakter olmalıdır',
            'description.max' => 'Açıklama en fazla 2000 karakter olabilir',
            'price.required' => 'Fiyat zorunludur',
            'price.numeric' => 'Fiyat sayısal bir değer olmalıdır',
            'price.min' => 'Fiyat 0 veya daha büyük olmalıdır',
            'stock_quantity.required' => 'Stok miktarı zorunludur',
            'stock_quantity.integer' => 'Stok miktarı tam sayı olmalıdır',
            'stock_quantity.min' => 'Stok miktarı 0 veya daha büyük olmalıdır',
            'category_id.required' => 'Kategori seçimi zorunludur',
            'category_id.exists' => 'Seçilen kategori bulunamadı',
        ];
    }
}
