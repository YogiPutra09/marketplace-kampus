<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReportController extends Controller
{
    /**
     * Menyimpan laporan pelanggaran dari mahasiswa.
     */
    public function store(Request $request, Product $product)
    {
        // 1. Larang penjual melaporkan barangnya sendiri
        if (Auth::id() === $product->user_id) {
            return back()->with('error', 'Kamu tidak bisa melaporkan barang jualanmu sendiri.');
        }

        // 2. Cek apakah user sudah pernah melaporkan barang ini
        $alreadyReported = ProductReport::where('reporter_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', 'Kamu sudah pernah mengirimkan laporan untuk barang ini.');
        }

        // 3. Validasi input
        $request->validate([
            'reason'  => ['required', 'string', 'max:100'],
            'details' => ['nullable', 'string', 'max:500'],
        ]);

        // 4. Simpan laporan
        ProductReport::create([
            'reporter_id' => Auth::id(),
            'product_id'  => $product->id,
            'reason'      => $request->reason,
            'details'     => $request->details,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Laporan berhasil dikirim. Tim admin akan segera meninjau postingan ini.');
    }

    /**
     * Mengubah status laporan (khusus admin: Dismiss / Abaikan).
     */
    public function dismiss(ProductReport $report)
    {
        $report->update(['status' => 'dismissed']);

        return back()->with('success', 'Laporan telah diabaikan.');
    }
}