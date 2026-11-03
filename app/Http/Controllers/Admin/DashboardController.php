<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SellerReview;
use App\Models\User;
use App\Models\ProductReport;

class DashboardController extends Controller
{
    /**
     * Menampilkan metrik statistik utama dan daftar data terbaru untuk Administrator.
     */
    public function index()
    {
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'total_products'    => Product::count(),
            'active_products'   => Product::where('status', 'available')->count(),
            'sold_products'     => Product::where('status', 'sold')->count(),
            'total_reviews'     => SellerReview::count(),
        ];

        // 6 produk teranyar di marketplace
        $recentProducts = Product::with(['user', 'category'])->latest()->take(6)->get();

        // 5 mahasiswa yang baru bergabung
        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();

        $pendingReports = ProductReport::with(['reporter', 'product.user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'recentUsers', 'pendingReports'));

    }
}