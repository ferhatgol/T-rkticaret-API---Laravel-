<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Doğrulayıcı hataları için özel mesajları al
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ad alanı zorunludur',
            'name.min' => 'Ad en az 2 karakter olmalıdır',
            'email.required' => 'Email alanı zorunludur',
            'email.email' => 'Geçerli bir email adresi giriniz',
            'email.unique' => 'Bu email adresi zaten kullanılıyor',
            'password.required' => 'Şifre alanı zorunludur',
            'password.min' => 'Şifre en az 8 karakter olmalıdır',
            'password.confirmed' => 'Şifre onayı eşleşmiyor',
        ];
    }
}
