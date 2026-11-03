<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua mahasiswa dengan pencarian & filter.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('faculty', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Mengubah status blokir (Ban / Unban) akun.
     */
    public function toggleBan(User $user)
    {
        // Mencegah admin memblokir sesama admin
        if ($user->isAdmin()) {
            return back()->with('error', 'Akun Administrator tidak dapat diblokir.');
        }

        $user->is_banned = ! $user->is_banned;
        $user->save();

        $statusText = $user->is_banned ? 'diblokir' : 'diaktifkan kembali';

        return back()->with('success', "Akun mahasiswa {$user->name} berhasil {$statusText}.");
    }
}
