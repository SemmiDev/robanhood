<?php

namespace App\Http\Controllers;

use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Ambil semua pengguna dan hitung total poin dari anggota_penanganan
        $allUsers = User::where('peran', '!=', 'WARGA')
            ->where('peran', '!=', 'ADMIN')
            ->withSum('anggota_penanganans', 'poin_diperoleh') // Menggunakan Eloquent aggregation
            ->orderByDesc('anggota_penanganans_sum_poin_diperoleh')
            ->get();

        // Membuat ranking berdasarkan poin yang diperoleh
        $rankings = [];
        foreach ($allUsers as $index => $user) {
            $rankings[$user->id] = $index + 1;
        }

        // Query pengguna berdasarkan filter pencarian
        $query = User::with(['anggota_penanganans'])
            ->where('peran', '!=', 'WARGA')
            ->where('peran', '!=', 'ADMIN')
            ->withSum('anggota_penanganans', 'poin_diperoleh');

        if (request()->has('q')) {
            $query->where('name', 'like', '%' . request('q') . '%');
        }

        // Ambil hasil leaderboard yang difilter dengan mempertahankan urutan
        $leaderboard = $query->orderByDesc('anggota_penanganans_sum_poin_diperoleh')->get();

        return view('app.leaderboard.index', [
            'leaderboard' => $leaderboard,
            'rankings' => $rankings
        ]);
    }
}
