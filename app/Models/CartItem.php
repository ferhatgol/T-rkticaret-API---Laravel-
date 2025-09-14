<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * Toplu atanabilir özellikler
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    /**
     * Sepet öğesinin ait olduğu sepeti getir
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Sepet öğesinin ait olduğu ürünü getir
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Bu sepet öğesi için toplam fiyatı getir
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->quantity * $this->product->price;
    }
}
