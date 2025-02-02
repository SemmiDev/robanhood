<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\ProfilePhoto;
use App\Models\Kasu;
use Illuminate\Http\Request;

class RealtimeManajemenKasusController extends Controller
{
    public function index()
    {
        return view('app.dashboard.realtime-kasus');
    }

    public function peta()
    {
        return view('app.peta.kasus');
    }

    public function realtimeSekitar()
    {
        return view('app.dashboard.realtime-sekitar');
    }

    // return meter
    public static function haversine($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        // convert from degrees to radians
        $earthRadius = 6371000;
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $result = $angle * $earthRadius;
        return $result;
    }

    public function realtimeSekitarInfo(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');

        $pengaturanWebsite = \App\Models\PengaturanWebsite::first();
        $radiusMeter = $pengaturanWebsite->radius_notifikasi * 1000; // Konversi ke meter

        // Ambil hanya kategori_kasus_id yang sesuai dengan filter
        $kategoriUnik = Kasu::whereHas('kategori_kasu', function ($query) {
            $query->where('nama', 'like', '%kecelakaan%')
                ->orWhere('nama', 'like', '%pencurian%');
        })->distinct()->pluck('kategori_kasus_id');

        $data = [];

        foreach ($kategoriUnik as $kategoriId) {
            // Ambil semua kasus dalam kategori ini
            $kasusKategori = Kasu::where('kategori_kasus_id', $kategoriId)->get();

            $jumlahKasus = 0;
            foreach ($kasusKategori as $kasus) {
                $latitudeKasus = $kasus->latitude;
                $longitudeKasus = $kasus->longitude;

                $distance = self::haversine($latitude, $longitude, $latitudeKasus, $longitudeKasus);
                if ($distance <= $radiusMeter) {
                    $jumlahKasus++;
                }
            }

            if ($jumlahKasus > 0) {
                $kategori = $kasusKategori->first()->kategori_kasu; // Ambil informasi kategori dari salah satu kasus

                $data['rekomendasi'][] = [
                    'icon' => asset('storage/' . $kategori->simbol),
                    'saran' => "Rawan " . $kategori->nama,
                    'deskripsi' => "{$kategori->pengingat}",
                ];
            }
        }

        // Tambahkan metadata
        $data['metadata'] = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'timestamp' => now()->toIso8601String(),
            'radius_scan' => "{$radiusMeter}m",
        ];

        return response()->json($data);
    }
}
