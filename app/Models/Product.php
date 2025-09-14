<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Toplu atanabilir özellikler
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
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
     * Ürünün ait olduğu kategoriyi getir
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Ürünün sepet öğelerini getir
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Ürünün sipariş öğelerini getir
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Ürünün stokta olup olmadığını kontrol et
     */
    public function isInStock(int $quantity = 1): bool
    {
        return $this->stock_quantity >= $quantity;
    }

    /**
     * Stok miktarını azalt
     */
    public function decreaseStock(int $quantity): void
    {
        $this->stock_quantity -= $quantity;
        $this->save();
    }

    /**
     * Stok miktarını artır
     */
    public function increaseStock(int $quantity): void
    {
        $this->stock_quantity += $quantity;
        $this->save();
    }

}
