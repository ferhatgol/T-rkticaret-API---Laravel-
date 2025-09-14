<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Toplu atanabilir özellikler
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Dönüştürülmesi gereken özellikler
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Sipariş öğesinin ait olduğu siparişi getir
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Sipariş öğesinin ait olduğu ürünü getir
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Bu sipariş öğesi için toplam fiyatı getir
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->quantity * $this->price;
    }
}
