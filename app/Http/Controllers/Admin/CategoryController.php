<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori beserta jumlah barang di dalamnya.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:categories,name'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', "Kategori '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Memperbarui nama dan slug kategori.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:categories,name,' . $category->id],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', "Kategori berhasil diperbarui menjadi '{$validated['name']}'.");
    }

    /**
     * Menghapus kategori (hanya diizinkan jika tidak ada barang yang memakainya).
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', "Kategori '{$category->name}' tidak bisa dihapus karena masih ada barang yang terdaftar di dalamnya.");
        }

        $category->delete();

        return back()->with('success', "Kategori '{$category->name}' berhasil dihapus.");
    }
}