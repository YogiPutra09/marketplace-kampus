<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Menampilkan daftar semua barang favorit milik pengguna yang sedang login.
     */
    public function index()
    {
        $wishlists = Auth::user()
            ->wishlists()
            ->with(['category', 'user'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Menambah atau menghapus barang dari wishlist (Toggle).
     */
    public function toggle(Product $product)
    {
        $user = Auth::user();

        // Larang user memfavoritkan barang miliknya sendiri
        if ($user->id === $product->user_id) {
            return back()->with('error', 'Kamu tidak bisa menambahkan barang jualanmu sendiri ke favorit.');
        }

        // Method toggle otomatis attach jika belum ada, atau detach jika sudah ada
        $status = $user->wishlists()->toggle($product->id);

        $isAdded = count($status['attached']) > 0;
        $message = $isAdded 
            ? 'Barang berhasil disimpan ke wishlist.' 
            : 'Barang dihapus dari wishlist.';

        return back()->with('success', $message);
    }
}