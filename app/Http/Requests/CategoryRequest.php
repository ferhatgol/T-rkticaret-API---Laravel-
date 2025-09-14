<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Doğrulayıcı hataları için özel mesajları al
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Kategori adı zorunludur',
            'name.min' => 'Kategori adı en az 2 karakter olmalıdır',
            'description.max' => 'Açıklama en fazla 1000 karakter olabilir',
        ];
    }
}
