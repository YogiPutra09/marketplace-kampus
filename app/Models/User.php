<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'whatsapp_number',
        'avatar',
        'faculty',
        'role',       // <-- DITAMBAHKAN
        'is_banned',   // <-- DITAMBAHKAN
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts tipe data kolom.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean', // <-- DITAMBAHKAN
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relasi ke Product: Satu user punya banyak barang jualan.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Ulasan yang diterima oleh user ini sebagai penjual
    public function receivedReviews()
    {
        return $this->hasMany(SellerReview::class, 'seller_id');
    }

    // Helper menghitung rata-rata rating (contoh: 4.8)
    public function averageRating()
    {
        return round($this->receivedReviews()->avg('rating') ?? 0, 1);
    }
}