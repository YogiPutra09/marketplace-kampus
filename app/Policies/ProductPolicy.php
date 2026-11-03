<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Tentukan apakah user boleh mengupdate produk.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    /**
     * Tentukan apakah user boleh menghapus produk.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }
}