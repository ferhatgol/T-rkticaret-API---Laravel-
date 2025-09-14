<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * Toplu atanabilir özellikler
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Sepetin sahibi olan kullanıcıyı getir
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sepetin sepet öğelerini getir
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Sepetin toplam tutarını getir
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    /**
     * Sepetteki toplam ürün sayısını getir
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->cartItems->sum('quantity');
    }

    /**
     * Sepetten tüm öğeleri temizle
     */
    public function clear(): void
    {
        $this->cartItems()->delete();
    }
}
