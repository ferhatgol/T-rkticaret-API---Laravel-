<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Doğrulayıcı hataları için özel mesajları al
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email alanı zorunludur',
            'email.email' => 'Geçerli bir email adresi giriniz',
            'password.required' => 'Şifre alanı zorunludur',
        ];
    }
}
