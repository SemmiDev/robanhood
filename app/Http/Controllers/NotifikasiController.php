<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{

    public function markAsRead()
    {
        DB::table('notifikasi')->where('user_id', auth()->user()->id)->update(['read' => 1]);
        return back()->with('success', 'Semua notifikasi berhasil dibaca');
    }

    public function store(Request $request)
    {
        $validated = $request->all();

        $kasusId = $validated['kasus_id'];
        $userId = $validated['user_id'];
        $jarak = $validated['jarak'];

        $existingNotifikasi = Notifikasi::where('kasus_id', $kasusId)
            ->where('user_id', $userId)
            ->first();

        if (!$existingNotifikasi) {
            Notifikasi::create([
                'kasus_id' => $kasusId,
                'user_id' => $userId,
                'push_notifikasi_terkirim' => false,
                // 'jenis' => 'penugasan',
                'jarak' => $jarak,
                'read' => false
            ]);
        }

        return response()->json([
            'message' => 'Notification processing complete',
        ], 200);
    }

    public function historiNotifikasi()
    {
        $now = now();
        $listNotifikasi = Notifikasi::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(100); // Pagination 100 per halaman

        $groupedNotifikasi = [
            'Sekarang' => [],
            'Hari Ini' => [],
            '7 Hari Terakhir' => [],
            '30 Hari Terakhir' => [],
            'Lama' => []
        ];

        foreach ($listNotifikasi as $notifikasi) {
            $createdAt = $notifikasi->created_at;

            // **Tentukan link & ikon notifikasi berdasarkan jenis**
            if ($notifikasi->jenis === 'chat') {
                $notifikasi->link = "/manajemen-kasus/{$notifikasi->kasus_id}/chat?source=notif&id_notif={$notifikasi->id}";
                $notifikasi->icon = "bx bx-chat";
            } elseif ($notifikasi->jenis === 'penugasan') {
                $notifikasi->link = "/manajemen-kasus/{$notifikasi->kasus_id}/show?source=notif&id_notif={$notifikasi->id}";
                $notifikasi->icon = "bx bx-task";
            } elseif ($notifikasi->jenis === 'kasus_sekitar') {
                $notifikasi->link = "/manajemen-kasus/{$notifikasi->kasus_id}/show?source=notif&id_notif={$notifikasi->id}";
                $notifikasi->icon = "bx bx-map-pin";
            } else {
                $notifikasi->link = "#"; // Default jika tidak ada jenis yang cocok
                $notifikasi->icon = "bx bx-bell";
            }

            // **Klasifikasikan ke kategori waktu**
            if ($createdAt->gt($now->subHour())) {
                $groupedNotifikasi['Sekarang'][] = $notifikasi;
            } elseif ($createdAt->isToday()) {
                $groupedNotifikasi['Hari Ini'][] = $notifikasi;
            } elseif ($createdAt->gt($now->subDays(7))) {
                $groupedNotifikasi['7 Hari Terakhir'][] = $notifikasi;
            } elseif ($createdAt->gt($now->subDays(30))) {
                $groupedNotifikasi['30 Hari Terakhir'][] = $notifikasi;
            } else {
                $groupedNotifikasi['Lama'][] = $notifikasi;
            }
        }

        return view('app.notifikasi', [
            'groupedNotifikasi' => $groupedNotifikasi,
            'listNotifikasi' => $listNotifikasi
        ]);
    }


    public function index()
    {
        $notifications = Notifikasi::where('user_id', '=', auth()->user()->id)->where('read', false)->limit(7)->latest()->get();
        // $notifications = Notifikasi::where('user_id', '=', auth()->user()->id)->where('read', false)->latest()->get();

        $result = [];
        foreach ($notifications as $notification) {
            $data = [
                'notif_id' => $notification->id,
                'message' => $notification->pesan,
                'timeAgo' => \Carbon\Carbon::parse($notification->created_at)->diffForHumans(),
                'jenis' => $notification->jenis,
                'kasus_id' => $notification->kasus_id,
            ];

            $result[] = $data;
        }

        $response = ["notifications" => $result];

        return response()->json($response);
    }
}
