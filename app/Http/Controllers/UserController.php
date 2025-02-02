<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\PushNotification;
use App\Models\Kasu;
use App\Models\Notifikasi;
use App\Models\PengaturanWebsite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestStatus\Notice;

class UserController extends Controller
{
    public function changeStatus(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->status = $request->get('status');
        $user->save();

        return redirect(route('root'))->with('success', 'Berhasil mengubah status');
    }

    public function getLatestUserCoordinates()
    {
        $coordinates = [
            'latitude' =>  auth()->user()->latitude_terakhir,
            'longitde' => auth()->user()->longitude_terakhir
        ];
        return response()->json($coordinates);
    }

    public function updateLatestUserCoordinates(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->latitude_terakhir = $request->get('latitude');
        $user->longitude_terakhir = $request->get('longitude');
        $user->update_lokasi_terakhir = now();
        $user->save();

        $coordinates = [
            'latitude' => $user->latitude_terakhir,
            'longitude' => $user->longitude_terakhir, // ✅ Perbaiki typo "longitde"
        ];

        // Ambil radius notifikasi dari database
        $pengaturanWebsite = PengaturanWebsite::select('radius_notifikasi')->first();
        $radiusMeter = $pengaturanWebsite->radius_notifikasi * 1000; // Konversi ke meter

        $latitude = $user->latitude_terakhir;
        $longitude = $user->longitude_terakhir;

        if (auth()->user()->peran == "POLISI") {
            // Query dengan Haversine untuk mendapatkan kasus dalam radius tertentu
            $listKasus = DB::select("
        SELECT id, judul, latitude, longitude,
        (6371000 * acos(cos(radians(?)) * cos(radians(latitude))
        * cos(radians(longitude) - radians(?)) + sin(radians(?))
        * sin(radians(latitude)))) AS distance
        FROM kasus
        WHERE (status = 'MENUNGGU' OR status = 'DALAM_PROSES')
        HAVING distance <= ?
        ORDER BY distance ASC
    ", [$latitude, $longitude, $latitude, $radiusMeter]);

            // Kirim notifikasi untuk setiap kasus yang ditemukan

            foreach ($listKasus as $kasus) {
                $notifikasi = Notifikasi::where('user_id', $user->id)
                    ->where('kasus_id', $kasus->id)
                    ->first();

                $kasus->distance = number_format($kasus->distance, 2);
                $pesanSingkat = "Ada kasus $kasus->judul dalam jarak $kasus->distance meter dari lokasi Anda"; // ✅ Perbaiki cara akses distance

                if (!$notifikasi) {
                    Notifikasi::create([
                        'kasus_id' => $kasus->id,
                        'user_id' => $user->id,
                        'push_notifikasi_terkirim' => true,
                        'pesan' => $pesanSingkat, // ✅ Tambahkan koma setelah ini
                        'jenis' => 'kasus_sekitar',
                        'read' => false,
                    ]);

                    $title = "Kasus Terdekat";
                    $body = $pesanSingkat;
                    $url = "/manajemen-kasus/$kasus->id/show";

                    $push[] = $user->onesignal_id;

                    PushNotification::SendOneSignalNotification($push, $title, $body, $url = $url);
                }
            }
        }

        return response()->json($coordinates);
    }
}
