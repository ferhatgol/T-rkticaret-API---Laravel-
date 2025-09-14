<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Doğrulayıcı hataları için özel mesajları al
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Ürün seçimi zorunludur',
            'product_id.exists' => 'Seçilen ürün bulunamadı',
            'quantity.required' => 'Miktar zorunludur',
            'quantity.integer' => 'Miktar tam sayı olmalıdır',
            'quantity.min' => 'Miktar en az 1 olmalıdır',
        ];
    }
}
