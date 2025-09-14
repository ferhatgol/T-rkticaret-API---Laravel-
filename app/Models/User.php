<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Toplu atanabilir özellikler
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Serileştirme için gizlenmesi gereken özellikler
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Dönüştürülmesi gereken özellikler
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * JWT'nin subject claim'inde saklanacak tanımlayıcıyı al
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT'ye eklenecek özel claim'leri içeren anahtar-değer dizisini döndür
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Kullanıcının admin olup olmadığını kontrol et
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Kullanıcının sepetini getir
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Kullanıcının siparişlerini getir
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
