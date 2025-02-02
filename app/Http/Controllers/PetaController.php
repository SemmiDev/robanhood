<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\ProfilePhoto;
use App\Models\AnggotaPenanganan;
use App\Models\Kasu;
use App\Models\Notifikasi;
use App\Models\PengaturanWebsite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profile;

class PetaController extends Controller
{
    public function petaKasusAssign($id)
    {
        // cek polisi yang tersedia
        $polisi = User::where('peran', 'POLISI')->where('status', '!=', 'SEDANG_TIDAK_BERTUGAS')->get();
        if (count($polisi) == 0) {
            return back()->with('error', 'Saat ini, belum ada anggota polisi yang bisa ditugaskan');
        }

        return view('app.peta.kasus-assign', [
            'id' => $id,
        ]);
    }

    public function petaKasus()
    {
        return view('app.peta.kasus');
    }

    public function getLatestKasusAssign($id)
    {
        $listKasus = Kasu::with(['kategori_kasu', 'bukti_kasus', 'user'])->where('id', '=', $id)->get();

        foreach ($listKasus as $kasus) {
            if ($kasus->jenis == "sos") {
                $kasus->latitude = $kasus->user->latitude_terakhir ?? 0;
                $kasus->longitude = $kasus->user->longitude_terakhir ?? 0;
            }
        }

        $kasusCoords = [];

        foreach ($listKasus as $k) {
            $html = '
    <div style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; width: 250px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
        <h5 style="margin: 0 0 5px 0; font-size: 16px; color: #333;">' . htmlspecialchars($k->judul) . '</h5>
        <p style="margin: 0 0 10px 0; font-size: 14px; color: #555;">' . htmlspecialchars(substr($k->deskripsi, 0, 20)) . '...</p>';

            if ($k->bukti_kasus && $k->bukti_kasus->count() > 0) {
                $html .= '<div style="display: flex; gap: 5px; margin-bottom: 10px;">';
                foreach ($k->bukti_kasus as $fk) {
                    // Get MIME type of the file
                    // $mimeType = mime_content_type($fk->path);
                    $mimeType = $k->mime;

                    // Check if MIME type starts with "image/"
                    if (strpos($mimeType, 'image/') === 0) {
                        $html .= '<img src="' . asset($fk->path) . '" alt="Kasus Image" style="width: 50px; height: 50px; border-radius: 5px;">';
                    }
                }
                $html .= '</div>';
            }

            $html .= '
        <p style="margin: 0; font-size: 14px; color: #666;"><strong>Status:</strong> ' . htmlspecialchars($k->status) . '</p>
        <p style="margin: 0; font-size: 14px; color: #666;"><strong>Tingkat Keparahan:</strong> ' . htmlspecialchars($k->tingkat_keparahan) . '</p>
        <a target="_blank" href="/manajemen-kasus/' . $k->id . '/show"
            style="display: inline-block; margin-top: 10px; padding: 8px 12px; background-color: #007BFF; color: white; text-decoration: none; font-size: 14px; border-radius: 4px; text-align: center;">
            Lihat Detail
        </a>
    </div>';

            $simbol = '/sos.png';
            if ($k->jenis == "kasus") {
                $simbol = asset('/storage/' . $k->kategori_kasu->simbol);
            }

            $kasusCoords[] = [
                "id" => $k->id,
                "judul" => $k->judul ?? 'SOS',
                "deskripsi" => $k->deskripsi ?? 'SOS',
                'status' => $k->status,
                "simbol" => $simbol,
                "html" => $html,
                "coordinate" => [$k->latitude, $k->longitude],
            ];
        }

        return response()->json($kasusCoords);
    }

    public function getLatestKasus()
    {
        $listKasus = Kasu::with(['kategori_kasu', 'bukti_kasus', 'user'])
            ->whereNotIn('status', ['SELESAI', 'DITUTUP'])
            ->get();

        foreach ($listKasus as $kasus) {
            if ($kasus->jenis == "sos") {
                $kasus->latitude = $kasus->user->latitude_terakhir ?? 0;
                $kasus->longitude = $kasus->user->longitude_terakhir ?? 0;
            }
        }

        $kasusCoords = [];

        foreach ($listKasus as $k) {
            $html = '
        <div style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; width: 250px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
            <h5 style="margin: 0 0 5px 0; font-size: 16px; color: #333;">' . htmlspecialchars($k->judul) . '</h5>
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #555;">' . htmlspecialchars(substr($k->deskripsi, 0, 20)) . '...</p>';

            if ($k->bukti_kasus && $k->bukti_kasus->count() > 0) {
                $html .= '<div style="display: flex; gap: 5px; margin-bottom: 10px;">';
                foreach ($k->bukti_kasus as $fk) {
                    // Get MIME type of the file
                    // $mimeType = mime_content_type($fk->path);
                    $mimeType = $k->mime;

                    // Check if MIME type starts with "image/"
                    if (strpos($mimeType, 'image/') === 0) {
                        $html .= '<img src="' . asset($fk->path) . '" alt="Kasus Image" style="width: 50px; height: 50px; border-radius: 5px;">';
                    }
                }
                $html .= '</div>';
            }

            $html .= '
            <p style="margin: 0; font-size: 14px; color: #666;"><strong>Status:</strong> ' . htmlspecialchars($k->status) . '</p>
            <p style="margin: 0; font-size: 14px; color: #666;"><strong>Tingkat Keparahan:</strong> ' . htmlspecialchars($k->tingkat_keparahan) . '</p>
            <a target="_blank" href="/manajemen-kasus/' . $k->id . '/show"
                style="display: inline-block; margin-top: 10px; padding: 8px 12px; background-color: #007BFF; color: white; text-decoration: none; font-size: 14px; border-radius: 4px; text-align: center;">
                Lihat Detail
            </a>
        </div>';

            $simbol = '/sos.png';
            if ($k->jenis == "kasus") {
                $simbol = asset('/storage/' . $k->kategori_kasu->simbol);
            }

            $kasusCoords[] = [
                "id" => $k->id,
                "judul" => $k->judul ?? 'SOS',
                "deskripsi" => $k->deskripsi ?? 'SOS',
                'status' => $k->status,
                "simbol" => $simbol,
                "html" => $html,
                "coordinate" => [$k->latitude, $k->longitude],
            ];
        }

        return response()->json($kasusCoords);
    }

    public function getLatestCoordPolisiAssignPolisi()
    {
        DB::beginTransaction();

        try {
            $kasusId = request()->get('kasus_id');
            $userId = request()->get('user_id');

            $kasus = Kasu::findOrFail($kasusId);
            if (!$kasus->waktu_respon) {
                $kasus->waktu_respon = now();
            }
            $kasus->status = 'DALAM_PROSES';
            $kasus->save();

            AnggotaPenanganan::firstOrCreate(
                ['user_id' => $userId, 'kasus_id' => $kasusId],
                ['peran' => 'ANGGOTA', 'selesai' => false, 'selesai_pada' => null, 'poin_diperoleh' => 0]
            );

            $userAnggota = User::findOrFail($userId);
            if ($userAnggota->status == "SEDANG_TIDAK_BERTUGAS") {
                DB::rollBack(); // Membatalkan semua perubahan
                return redirect(route('manajemenUnit.show', ['id' => $kasus->id]))->with('error', 'User sedang tidak aktif / libur.');
            }

            $userAnggota->status = "SEDANG_MENANGANI_KASUS";
            $userAnggota->save();

            // Batasi 2 kata
            $pesanSingkat = implode(' ', array_slice(explode(' ', $kasus->judul), 0, 2));

            // buat notifikasi
            Notifikasi::create([
                'kasus_id' => $kasus->id,
                'user_id' => $userAnggota->id,
                'push_notifikasi_terkirim' => true,
                'pesan' => 'Admin menugaskan anda untuk menangani kasus ' . $pesanSingkat,
                'jenis' => 'penugasan',
                'read' => false,
            ]);

            DB::commit();
            return redirect(route('manajemenKasus.show', ['id' => $kasusId]))
                ->with('success', 'Berhasil ditugaskan');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error lain
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getLatestCoordPolisiAssign($id)
    {

        $kasus = Kasu::with('user')->find($id);
        if (!$kasus) {
            return redirect('/dashboard')->with('error', 'Kasus tidak ditemukan');
        }

        if ($kasus->jenis == "sos") {
            $kasus->latitude = $kasus->user->latitude_terakhir;
            $kasus->longitude = $kasus->user->longitude_terakhir;
        }

        $users = User::with(
            [
                'profil_polisis',
                'profil_polisis.pangkat_polisi',
            ]
        )
            ->where('peran', 'POLISI')
            ->get();

        $usersDetails = [];

        foreach ($users as $user) {
            $profilPolisi = $user->profil_polisis->first();
            $pangkat = $profilPolisi->pangkat_polisi->nama ?? '';

            $lastLocation = $user->update_lokasi_terakhir;
            $lastLocationHuman = \Carbon\Carbon::parse($lastLocation)->diffForHumans();

            $profilPhotoURL = ProfilePhoto::get($user->avatar, $user->name);
            $nama = $pangkat . ' ' . $user->name;
            $status = $user->status;
            $marker = $status == "SEDANG_BERTUGAS" ? "policeIcon" : "policeAreOnDutyIcon";
            $assignURL = "/get-latest-coord-polisi-assign-polisi?user_id=" . $user->id . "&kasus_id=" . $kasus->id;

            // Calculate the distance
            $jarakDalamMeter = RealtimeManajemenKasusController::haversine($kasus->latitude, $kasus->longitude, $user->latitude_terakhir, $user->longitude_terakhir);
            $jarakDalamMeter = number_format($jarakDalamMeter, 2);

            $bindPopUp = '
            <div style="max-width: 300px; font-family: Arial, sans-serif; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden; background: #fff;">
                <div style="display: flex; align-items: center; padding: 10px 15px; background: #f4f4f4;">
                    <img src="' . htmlspecialchars($profilPhotoURL) . '" alt="Profile Photo" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
                    <div>
                        <h4 style="margin: 0; font-size: 16px; color: #2c3e50; font-weight: bold;">' . htmlspecialchars($nama) . '</h4>
                        <p style="margin: 5px 0 0 0; font-size: 14px; color: #7f8c8d;">' . htmlspecialchars($status) . '</p>
                    </div>
                </div>
                <div style="padding: 15px;">
                    <p style="margin: 0 0 5px 0; font-size: 14px; color: #7f8c8d;"><strong>Update Lokasi Terakhir:</strong></p>
                    <p style="margin: 0; font-size: 14px; color: #34495e;">' . htmlspecialchars($lastLocation) . ' = ' . $lastLocationHuman . '</p>
                    <p style="margin: 0; font-size: 14px; color: #34495e;">' . 'Jarak = ' . $jarakDalamMeter . ' Meter dari lokasi kejadian</p>
                    <a href="' . htmlspecialchars($assignURL) . '" class="btn mt-3 btn-sm btn-info text-white">Tugaskan</a>
                </div>
            </div>';

            $usersDetails[] = [
                "id" => $user->id,
                "user" => $user,
                "maker" => $marker,
                "bindPopup" => $bindPopUp,
                "formatName" => $pangkat . ' ' . $user->name,
                "lastCoordinate" => [$user->latitude_terakhir, $user->longitude_terakhir]
            ];
        }

        return response()->json($usersDetails);
    }

    public function getLatestCoordPolisi()
    {
        $users = User::with(
            [
                'profil_polisis',
                'profil_polisis.pangkat_polisi',
            ]
        )
            ->where('peran', 'POLISI')
            ->get();

        $usersDetails = [];

        foreach ($users as $user) {
            $profilPolisi = $user->profil_polisis->first();
            $pangkat = $profilPolisi->pangkat_polisi->nama ?? '';

            $lastLocation = $user->update_lokasi_terakhir;
            $lastLocationHuman = \Carbon\Carbon::parse($lastLocation)->diffForHumans();

            $profilPhotoURL = ProfilePhoto::get($user->avatar, $user->name);
            $nama = $pangkat . ' ' . $user->name;
            $status = $user->status;
            $marker = $status == "SEDANG_BERTUGAS" ? "policeIcon" : "policeAreOnDutyIcon";

            $bindPopUp = '
            <div style="max-width: 300px; font-family: Arial, sans-serif; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden; background: #fff;">
                <div style="display: flex; align-items: center; padding: 10px 15px; background: #f4f4f4;">
                    <img src="' . htmlspecialchars($profilPhotoURL) . '" alt="Profile Photo" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
                    <div>
                        <h4 style="margin: 0; font-size: 16px; color: #2c3e50; font-weight: bold;">' . htmlspecialchars($nama) . '</h4>
                        <p style="margin: 5px 0 0 0; font-size: 14px; color: #7f8c8d;">' . htmlspecialchars($status) . '</p>
                    </div>
                </div>
                <div style="padding: 15px;">
                    <p style="margin: 0 0 5px 0; font-size: 14px; color: #7f8c8d;"><strong>Update Lokasi Terakhir:</strong></p>
                    <p style="margin: 0; font-size: 14px; color: #34495e;">' . htmlspecialchars($lastLocation) . ' = ' . $lastLocationHuman . '</p>
                </div>
            </div>';

            $usersDetails[] = [
                "id" => $user->id,
                "user" => $user,
                "maker" => $marker,
                "bindPopup" => $bindPopUp,
                "formatName" => $pangkat . ' ' . $user->name,
                "lastCoordinate" => [$user->latitude_terakhir, $user->longitude_terakhir]
            ];
        }

        return response()->json($usersDetails);
    }

    public function scanKasusTerdekat()
    {
        return view('app.peta.scan-kasus-terdekat');
    }

    public function getKasusTerdekat()
    {
        $user = User::find(auth()->user()->id);

        // Ambil radius notifikasi dari database
        $pengaturanWebsite = PengaturanWebsite::select('radius_notifikasi')->first();
        $radiusMeter = $pengaturanWebsite->radius_notifikasi * 1000; // Konversi ke meter

        $latitude = $user->latitude_terakhir;
        $longitude = $user->longitude_terakhir;

        // Query menggunakan Eloquent + Haversine
        $listKasus = Kasu::selectRaw("
        id, judul, deskripsi, latitude, longitude, status, tingkat_keparahan, kategori_kasus_id,
        (6371000 * acos(cos(radians(?)) * cos(radians(latitude))
        * cos(radians(longitude) - radians(?)) + sin(radians(?))
        * sin(radians(latitude)))) AS distance
    ", [$latitude, $longitude, $latitude])
            ->with(['kategori_kasu', 'bukti_kasus', 'user']) // Load relasi
            ->whereIn('status', ['MENUNGGU', 'DALAM_PROSES']) // Status yang valid
            ->having('distance', '<=', $radiusMeter) // Filter berdasarkan radius
            ->orderBy('distance', 'ASC') // Urutkan berdasarkan jarak
            ->get();

        $kasusCoords = [];

        foreach ($listKasus as $k) {
            $html = '
        <div style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; width: 250px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
            <h5 style="margin: 0 0 5px 0; font-size: 16px; color: #333;">' . htmlspecialchars($k->judul) . '</h5>
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #555;">' . htmlspecialchars(substr($k->deskripsi, 0, 20)) . '...</p>';

            // Tampilkan bukti kasus (gambar)
            if ($k->bukti_kasus->count() > 0) {
                $html .= '<div style="display: flex; gap: 5px; margin-bottom: 10px;">';
                foreach ($k->bukti_kasus as $fk) {
                    if (strpos($fk->mime, 'image/') === 0) {
                        $html .= '<img src="' . asset($fk->path) . '" alt="Kasus Image" style="width: 50px; height: 50px; border-radius: 5px;">';
                    }
                }
                $html .= '</div>';
            }

            $html .= '
            <p style="margin: 0; font-size: 14px; color: #666;"><strong>Status:</strong> ' . htmlspecialchars($k->status) . '</p>
            <p style="margin: 0; font-size: 14px; color: #666;"><strong>Tingkat Keparahan:</strong> ' . htmlspecialchars($k->tingkat_keparahan) . '</p>
            <a target="_blank" href="/manajemen-kasus/' . $k->id . '/show"
                style="display: inline-block; margin-top: 10px; padding: 8px 12px; background-color: #007BFF; color: white; text-decoration: none; font-size: 14px; border-radius: 4px; text-align: center;">
                Lihat Detail
            </a>
        </div>';

            // Ambil ikon simbol kategori kasus
            $simbol = asset('/sos.png');
            if ($k->kategori_kasu) {
                $simbol = asset('/storage/' . $k->kategori_kasu->simbol);
            }

            $kasusCoords[] = [
                "id" => $k->id,
                "judul" => $k->judul ?? 'SOS',
                "deskripsi" => $k->deskripsi ?? 'SOS',
                'status' => $k->status,
                "simbol" => $simbol,
                "html" => $html,
                "coordinate" => [$k->latitude, $k->longitude],
            ];
        }

        return response()->json($kasusCoords);
    }
}
