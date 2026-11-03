<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Menampilkan katalog barang dengan filter lanjutan & pencarian.
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::with(['user', 'category'])
            ->where('status', 'available')
            // Pencarian judul barang
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            // Filter Kategori
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            // Filter Kondisi (like_new, good, fair)
            ->when($request->filled('condition'), function ($query) use ($request) {
                $query->where('condition', $request->condition);
            })
            // Filter Harga Minimum
            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('price', '>=', $request->min_price);
            })
            // Filter Harga Maksimum
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', $request->max_price);
            })
            // Pengurutan (Sorting)
            ->when($request->filled('sort'), function ($query) use ($request) {
                match ($request->sort) {
                    'price_asc'  => $query->orderBy('price', 'asc'),
                    'price_desc' => $query->orderBy('price', 'desc'),
                    'oldest'     => $query->oldest(),
                    default      => $query->latest(),
                };
            }, function ($query) {
                $query->latest(); // Default: barang terbaru
            })
            ->paginate(12)
            ->withQueryString(); // Mempertahankan semua query filter saat pindah halaman

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan form input barang.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Menyimpan barang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price'       => ['required', 'numeric', 'min:0'],
            'condition'   => ['required', 'in:like_new,good,fair'],
            'description' => ['required', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        $request->user()->products()->create([
            'category_id' => $validated['category_id'],
            'title'       => $validated['title'],
            'slug'        => $slug,
            'price'       => $validated['price'],
            'condition'   => $validated['condition'],
            'description' => $validated['description'],
            'status'      => 'available',
            'image'       => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Barang berhasil dipublikasikan!');
    }

    /**
     * Menampilkan detail satu barang.
     */
    /**
     * Menampilkan detail satu barang beserta rekomendasi produk terkait.
     */
    public function show(Product $product)
    {
        $product->load(['user', 'category']);

        // Ambil maksimal 4 produk sejenis dalam kategori yang sama
        $relatedProducts = Product::with(['category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Menampilkan form edit barang.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Menyimpan perubahan data barang ke database.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('update', $product);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price'       => ['required', 'numeric', 'min:0'],
            'condition'   => ['required', 'in:like_new,good,fair'],
            'description' => ['required', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Cek jika pengguna mengunggah foto baru
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Perbarui slug jika judul berubah
        if ($product->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        }

        $product->update($validated);

        return redirect()->route('products.my')->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus barang dari database & menghapus gambarnya di storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.my')->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Toggle status ketersediaan barang (available <-> sold).
     */
    public function toggleStatus(Product $product)
    {
        Gate::authorize('update', $product); // Ganti $this->authorize ke Gate::authorize

        $product->status = $product->status === 'available' ? 'sold' : 'available';
        $product->save();

        $pesan = $product->status === 'sold' 
            ? 'Barang berhasil ditandai sebagai terjual.' 
            : 'Barang kembali diaktifkan untuk dijual.';

        return back()->with('success', $pesan);
    }

    /**
     * Menampilkan daftar barang milik user yang login.
     */
    public function myProducts(Request $request)
    {
        $products = $request->user()
            ->products()
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('products.my-products', compact('products'));
    }

    /**
     * Menampilkan Landing Page beranda marketplace.
     */
    public function home()
    {
        $categories = Category::all();
        $latestProducts = Product::with(['user', 'category'])
            ->where('status', 'available')
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('categories', 'latestProducts'));
    }
}