<?php

namespace App\Http\Controllers;

use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        // First get all users ordered by total points to establish base rankings
        $allUsers = User::where('peran', '!=', 'WARGA')
            ->where('peran', '!=', 'ADMIN')
            ->orderBy('total_poin', 'DESC')
            ->get();

        // Create a mapping of user IDs to their original rankings
        $rankings = [];
        foreach ($allUsers as $index => $user) {
            $rankings[$user->id] = $index + 1;
        }

        // Build the filtered query
        $query = User::with(['anggota_penanganans'])
            ->where('peran', '!=', 'WARGA')
            ->where('peran', '!=', 'ADMIN');

        if (request()->has('q')) {
            $query->where('name', 'like', '%' . request('q') . '%');
        }

        // Get filtered results but maintain original order by total_poin
        $leaderboard = $query->orderBy('total_poin', 'DESC')->get();

        // Pass both the leaderboard and rankings to the view
        return view('app.leaderboard.index', [
            'leaderboard' => $leaderboard,
            'rankings' => $rankings
        ]);
    }
}
