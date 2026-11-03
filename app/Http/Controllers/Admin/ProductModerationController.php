<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductModerationController extends Controller
{
    /**
     * Menghapus produk yang melanggar aturan beserta file fotonya.
     */
    public function destroy(Product $product)
    {
        // Hapus file gambar fisik jika ada
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', "Barang '{$product->title}' berhasil di-takedown oleh Admin.");
    }
}