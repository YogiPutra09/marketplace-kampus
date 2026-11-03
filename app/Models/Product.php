<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'condition',
        'status',
        'image',
    ];

    /**
     * Relasi ke User: Produk dimiliki oleh penjual tertentu.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Category: Produk terdaftar di bawah satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reports()
    {
        return $this->hasMany(ProductReport::class);
    }
}