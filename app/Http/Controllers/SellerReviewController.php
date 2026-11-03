<?php
namespace App\Http\Controllers;

use App\Models\SellerReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerReviewController extends Controller
{
    public function store(Request $request, User $seller)
    {
        // 1. Larang penjual menilai dirinya sendiri
        if (Auth::id() === $seller->id) {
            return back()->with('error', 'Kamu tidak bisa memberi ulasan untuk tokomu sendiri.');
        }

        // 2. Validasi input
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        // 3. Simpan atau perbarui ulasan jika sebelumnya sudah pernah review
        SellerReview::updateOrCreate(
            [
                'reviewer_id' => Auth::id(),
                'seller_id'   => $seller->id,
            ],
            [
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Ulasan untuk penjual berhasil dikirim!');
    }
}
