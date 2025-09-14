<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
    ];

    /**
     * Kategorinin ürünlerini getir
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
