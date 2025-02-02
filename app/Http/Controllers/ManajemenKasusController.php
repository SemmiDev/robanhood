<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Constant;
use App\Http\Controllers\Helpers\PushNotification;
use App\Models\AnggotaPenanganan;
use App\Models\BuktiPenyelesaianKasu;
use App\Models\Chat;
use App\Models\Kasu;
use App\Models\KategoriKasu;
use App\Models\Notifikasi;
use App\Models\PengaturanWebsite;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManajemenKasusController extends Controller
{
    public function index(Request $request)
    {
        $pengaturanWebsite = PengaturanWebsite::select('radius_notifikasi')->first();
        $user = auth()->user();

        $query = Kasu::with([
            'kategori_kasu',
            'bukti_kasus',
            'anggota_penanganans',
            'anggota_penanganans.user',
        ])
            ->orderByRaw("CASE WHEN jenis = 'sos' THEN 1 ELSE 2 END") // Prioritaskan jenis SOS
            ->orderBy('waktu_kejadian', 'DESC'); // Urutkan berdasarkan waktu kejadian

        // 🔍 Search filter
        if ($request->filled('q')) {
            $searchTerm = $request->get('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('judul', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
            });
        }

        // 📌 Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // 🚨 Severity (Keparahan) filter
        if ($request->filled('keparahan')) {
            $query->where('tingkat_keparahan', $request->get('keparahan'));
        }

        // 📅 Period (Periode) filter
        if ($request->filled('periode')) {
            switch ($request->get('periode')) {
                case 'today':
                    $query->whereDate('waktu_kejadian', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('waktu_kejadian', Carbon::yesterday());
                    break;
                case 'last_7_days':
                    $query->whereBetween('waktu_kejadian', [Carbon::now()->subDays(7), Carbon::now()]);
                    break;
                case 'last_30_days':
                    $query->whereBetween('waktu_kejadian', [Carbon::now()->subDays(30), Carbon::now()]);
                    break;
                case 'this_month':
                    $query->whereMonth('waktu_kejadian', Carbon::now()->month)
                        ->whereYear('waktu_kejadian', Carbon::now()->year);
                    break;
                case 'last_year':
                    $query->whereYear('waktu_kejadian', Carbon::now()->subYear()->year);
                    break;
            }
        }

        // 🏢 **Jika role POLISI, filter kasus berdasarkan penanganan & radius**
        if ($user->peran === 'POLISI') {
            $query->where(function ($q) use ($user, $pengaturanWebsite) {
                // 1️⃣ **Kasus yang ditangani oleh polisi ini**
                $q->whereHas('anggota_penanganans', function ($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id);
                });

                // 2️⃣ **Kasus yang berada dalam radius polisi**
                if ($user->latitude_terakhir && $user->longitude_terakhir) {
                    $radiusMeter = $pengaturanWebsite->radius_notifikasi * 1000; // Konversi ke meter
                    $q->orWhereRaw(
                        "
                        (6371000 * acos(cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                        sin(radians(latitude)))) <= ?",
                        [
                            $user->latitude_terakhir,
                            $user->longitude_terakhir,
                            $user->latitude_terakhir,
                            $radiusMeter
                        ]
                    );
                }
            });
        }

        $listKasus = $query->paginate(Constant::PER_PAGE)
            ->appends($request->except('page'));

        return view('app.manajemen-kasus.index', [
            'listKasus' => $listKasus,
        ]);
    }

    public function show($id)
    {
        if (request()->get('source') == "notif") {
            Notifikasi::where('kasus_id', '=', $id)
                ->where('user_id', '=', auth()->user()->id)
                ->update(['read' => 1]);
        }

        $kasus = Kasu::with(['chats', 'kategori_kasu', 'user', 'anggota_penanganans', 'anggota_penanganans.user', 'anggota_penanganans.bukti_penyelesaian_kasus', 'bukti_kasus'])->find($id);
        $totalChat = $kasus->chats->count();
        $listAnggotaPolisiSedangBertugas = User::where('peran', 'POLISI')->where('status', 'SEDANG_BERTUGAS')->get();
        $pengaturanWebsite = PengaturanWebsite::select('radius_notifikasi')->first();

        $listAnggotaPolisiSedangBertugasArray = [];

        // Limit 10 posisi yang terdekat dari kasus
        $total = 0;
        foreach ($listAnggotaPolisiSedangBertugas as $anggota) {
            if ($total == 10) {
                break;
            }

            // Calculate the distance
            $jarakDalamMeter = RealtimeManajemenKasusController::haversine($kasus->latitude, $kasus->longitude, $anggota->latitude_terakhir, $anggota->longitude_terakhir);
            $radiusMeter = $pengaturanWebsite->radius_notifikasi * 1000; // Convert to meters

            if ($jarakDalamMeter < $radiusMeter) {
                // Add the distance to the anggota object
                $anggota->jarak = $jarakDalamMeter;

                $anggota->jarak = number_format($jarakDalamMeter, 2);

                // Add anggota to the array
                $listAnggotaPolisiSedangBertugasArray[] = $anggota;
            }

            $total += 1;
        }


        // Sort by distance (jarak) in ascending order
        usort($listAnggotaPolisiSedangBertugasArray, function ($a, $b) {
            return $a->jarak <=> $b->jarak;  // Compare distances
        });

        // Calculate the distance
        $jarakDalamMeter = RealtimeManajemenKasusController::haversine($kasus->latitude, $kasus->longitude, auth()->user()->latitude_terakhir, auth()->user()->longitude_terakhir);
        $jarakDalamMeter = number_format($jarakDalamMeter, 2);

        return view('app.manajemen-kasus.show', [
            'kasus' => $kasus,
            'jarakRelatif' => $jarakDalamMeter,
            'totalChat' => $totalChat,
            'listAnggotaPolisiSedangBertugas' => $listAnggotaPolisiSedangBertugasArray,  // Pass the array with distance
        ]);
    }

    public function destroy($id)
    {
        Kasu::where('id', $id)->delete();
        return redirect(route('manajemenKasus'))->with('success', 'Kasus berhasil dihapus');
    }

    public function unhandle($id)
    {
        // hapus sbg anggota
        $anggotaPenanganan = AnggotaPenanganan::where('user_id', '=', auth()->user()->id)->where('kasus_id', '=', $id)->first();
        // $anggotaPenanganan->delete();

        $anggotaPenanganan->aktif = 0;
        $anggotaPenanganan->selesai = 0;
        $anggotaPenanganan->poin_diperoleh = 0;
        $anggotaPenanganan->selesai_pada = null;
        $anggotaPenanganan->alasan_dibatalkan = request()->get('keterangan');
        $anggotaPenanganan->save();

        return back()->with('success', 'Anda berhasil membatalkan menangani kasus ini');
    }

    public function handle($id)
    {
        // cek kesediaan
        if (auth()->user()->status == "SEDANG_TIDAK_BERTUGAS") {
            return back()->with('error', 'Anda sedang tidak bertugas.');
        }

        // if (auth()->user()->status == 'SEDANG_MENANGANI_KASUS') {
        //     return back()->with('error', 'Anda sedang menangani kasus lain, silahkan selesaikan kasus tersebut terlebih dahulu.');
        // }

        // cek jika belum ada anggota sebelumnya, set user jadi KETUA
        $peran = 'ANGGOTA';
        $totalAnggotaPenanganan = AnggotaPenanganan::where('kasus_id', '=', $id)->count();
        if ($totalAnggotaPenanganan == 0) {
            $peran = "KETUA";
        }

        // tambah ke anggota penanganan
        AnggotaPenanganan::create([
            'user_id' => auth()->user()->id,
            'kasus_id' => $id,
            'peran' => $peran,
            'selesai' => false,
            'selesai_pada' => null,
            'poin_diperoleh' => 0
        ]);

        // ubah status kasus dan waktu response
        $kasus = Kasu::find($id);
        if (!$kasus->waktu_respon) {
            $kasus->waktu_respon = now();
        }
        $kasus->status = 'DALAM_PROSES';
        $kasus->save();

        // ubah kesediaan
        $user = User::find(auth()->user()->id);
        $user->status = "SEDANG_MENANGANI_KASUS";
        $user->save();

        return back()->with('success', 'Anda berhasil mengambil kasus ini');
    }

    public function selesaikan($id)
    {

        $anggotaPenanganan = AnggotaPenanganan::where('user_id', '=', auth()->user()->id)->where('kasus_id', '=', $id)->first();

        $anggotaPenanganan->update([
            'selesai' => true,
            'poin_diperoleh' => 1,
            'selesai_pada' => now()
        ]);

        // hitung ulang poin user
        $totalPoint = AnggotaPenanganan::where('user_id', '=', auth()->user()->id)->sum('poin_diperoleh');

        // ubah kesediaan dan poin
        $user = User::find(auth()->user()->id);
        $user->status = "SEDANG_BERTUGAS";
        $user->total_poin = $totalPoint;
        $user->save();

        if ($anggotaPenanganan->peran == "KETUA") {
            // ubah status kasus dan waktu response
            $kasus = Kasu::find($id);
            if (!$kasus->waktu_respon) {
                $kasus->waktu_respon = now();
            }

            $kasus->status = 'SELESAI';
            $kasus->save();

            return back()->with('success', 'Pekerjaan dan kasus berhasil di selesaikan, anda mendapatkan 1 poin');
        }

        return back()->with('success', 'Pekerjaan anda berhasil di selesaikan, anda mendapatkan 1 poin');
    }

    public function tutup($id)
    {
        // ubah status kasus dan waktu response
        $kasus = Kasu::find($id);
        if (!$kasus->waktu_respon) {
            $kasus->waktu_respon = now();
        }
        $kasus->status = 'DITUTUP';
        $kasus->save();

        foreach ($kasus->anggota_penanganans as $anggota) {
            $anggota->update([
                'selesai' => true,
                'poin_diperoleh' => 1,
                'selesai_pada' => now()
            ]);
        }

        return back()->with('success', 'Kasus berhasil di tutup');
    }

    public function update(Request $request, $id)
    {
        $kasus = Kasu::find($id);
        if (!$kasus) {
            return redirect()->back()->with('error', 'Kasus tidak ditemukan.');
        }

        $kasus->judul = $request->get('judul');
        $kasus->deskripsi = $request->get('deskripsi');
        $kasus->alamat = $request->get('alamat');
        $kasus->kategori_kasus_id = $request->get('kategori_kasus_id');
        $kasus->tingkat_keparahan = $request->get('tingkat_keparahan');
        $kasus->latitude = $request->get('latitude');
        $kasus->longitude = $request->get('longitude');

        $kasus->save();

        return redirect()->route('manajemenKasus.show', $kasus->id)->with('success', 'Data berhasil diperbarui');
    }

    public function edit($id)
    {
        $kasus = Kasu::with([
            'kategori_kasu',
            'bukti_kasus',
        ])->find($id);


        $kategoriKasus = KategoriKasu::all();

        return view('app.manajemen-kasus.edit', [
            'kasus' => $kasus,
            'kategoriKasus' => $kategoriKasus,
        ]);
    }

    public function switchStatus(Request $request, $id)
    {
        // Ubah status kasus dan waktu respon
        $kasus = Kasu::find($id);
        if (!$kasus->waktu_respon) {
            $kasus->waktu_respon = now();
        }
        if ($request->status == "SELESAI") {
            $kasus->status = 'SELESAI';
            $kasus->selesai_pada = now();

            $anggotaPenanganan = AnggotaPenanganan::with(['user'])->where('kasus_id', '=', $id)->get();

            foreach ($anggotaPenanganan as $anggota) {
                // hitung ulang poin user
                $totalPoint = AnggotaPenanganan::where('user_id', '=', $anggota->user_id)->sum('poin_diperoleh');

                // ubah kesediaan dan poin
                $user = User::find($anggota->user_id);
                $user->status = "SEDANG_BERTUGAS";
                $user->total_poin = $totalPoint;
                $user->save();

                // ubah status jadi selesai
                $anggota->update([
                    'selesai' => true,
                    'poin_diperoleh' => 1,
                    'selesai_pada' => now()
                ]);
            }
        } else {
            $kasus->status = $request->status;
        }

        $kasus->save();
        return back()->with('success', 'Status berhasil diubah');
    }

    public function assign(Request $request, $id)
    {
        // Ubah status kasus dan waktu respon
        $kasus = Kasu::find($id);
        if (!$kasus->waktu_respon) {
            $kasus->waktu_respon = now();
        }
        $kasus->status = 'DALAM_PROSES';
        $kasus->save();

        // Tangani logika ketua
        if ($request->has('ketua')) {
            $currentKetua = AnggotaPenanganan::where('kasus_id', $id)
                ->where('peran', 'KETUA')
                ->first();

            if ($currentKetua) {
                // Jika ketua sudah ada, ubah jadi anggota
                $currentKetua->peran = 'ANGGOTA';
                $currentKetua->save();
            }

            // Perbarui atau buat ketua baru
            $newKetua = AnggotaPenanganan::updateOrCreate(
                ['user_id' => $request->get('ketua'), 'kasus_id' => $id],
                ['peran' => 'KETUA', 'selesai' => false, 'selesai_pada' => null, 'poin_diperoleh' => 0]
            );

            // Ubah status user ketua
            $userKetua = User::find($request->get('ketua'));
            $userKetua->status = "SEDANG_BERTUGAS";
            $userKetua->save();

            // Batasi 2 kata
            $pesanSingkat = implode(' ', array_slice(explode(' ', $kasus->judul), 0, 2));

            // buat notifikasi
            Notifikasi::create([
                'kasus_id' => $id,
                'user_id' => $userKetua->id,
                'push_notifikasi_terkirim' => true,
                'pesan' => 'Admin menugaskan anda untuk menangani kasus ' . $pesanSingkat,
                'jenis' => 'penugasan',
                'read' => false,
            ]);
        }

        // Tangani logika anggota
        if ($request->has('anggota') && is_array($request->anggota)) {
            foreach ($request->anggota as $anggotaId) {
                // Periksa apakah anggota sudah ada, jika tidak buat baru
                AnggotaPenanganan::firstOrCreate(
                    ['user_id' => $anggotaId, 'kasus_id' => $id],
                    ['peran' => 'ANGGOTA', 'selesai' => false, 'selesai_pada' => null, 'poin_diperoleh' => 0]
                );

                // Ubah status user anggota
                $userAnggota = User::find($anggotaId);
                if ($userAnggota->status !== "SEDANG_BERTUGAS") {
                    $userAnggota->status = "SEDANG_BERTUGAS";
                    $userAnggota->save();
                }

                // Batasi 2 kata
                $pesanSingkat = implode(' ', array_slice(explode(' ', $kasus->judul), 0, 2));

                // buat notifikasi
                Notifikasi::create([
                    'kasus_id' => $id,
                    'user_id' => $anggotaId,
                    'push_notifikasi_terkirim' => true,
                    'pesan' => 'Admin menugaskan anda untuk menangani kasus ' . $pesanSingkat,
                    'jenis' => 'penugasan',
                    'read' => false,
                ]);
            }
        }

        return back()->with('success', 'Anggota berhasil ditugaskan');
    }

    public function rute($id)
    {
        $kasus = Kasu::with(['kategori_kasu'])->find($id);
        if (!$kasus) {
            return redirect(route('manajemenKasus'))->with('error', 'Maaf, Kasus tidak ditemukan');
        }

        return view('app.peta.rute', [
            'kasus' => $kasus,
        ]);
    }

    public function ruteSos($id)
    {
        $kasus = Kasu::with(['user'])->find($id);
        if (!$kasus) {
            return redirect(route('manajemenKasus'))->with('error', 'Maaf, Kasus tidak ditemukan');
        }

        return view('app.peta.rute-sos', [
            'kasus' => $kasus,
        ]);
    }


    public function feedback(Request $request, $id)
    {
        $kasus = Kasu::find($id);
        if (!$kasus) {
            return redirect(route('manajemenKasus'))->with('error', 'Maaf, Kasus tidak ditemukan');
        }

        $kasus->feedback = $request->get('feedback');
        $kasus->save();

        return redirect()->back()->with('success', 'Terimakasih, feedback anda telah dikirim');
    }


    public function chat(Request $request, $id)
    {
        if ($request->get('source') == "notif") {
            Notifikasi::where('kasus_id', '=', $id)
                ->where('user_id', '=', auth()->user()->id)
                ->update(['read' => 1]);
        }

        $kasus = Kasu::find($id);
        if (!$kasus) {
            return redirect(route('manajemenKasus'))->with('error', 'Maaf, Kasus tidak ditemukan');
        }


        $chats = Chat::with(['user'])->where('kasus_id', '=', $id)->get();

        return view('app.manajemen-kasus.chat', [
            'chats' => $chats,
            'kasus' => $kasus,
        ]);
    }


    public function sendChat(Request $request, $id)
    {
        $kasus = Kasu::with(['user','anggota_penanganans', 'anggota_penanganans.user'])->where('id', '=', $id)->first();
        $listAdmin = User::where('peran', '=', 'ADMIN')->get();

        // Menyimpan pesan
        $pesan = $request->get('pesan');
        Chat::create([
            'kasus_id' => $id,
            'pengirim_id' => auth()->user()->id,
            'pesan' => $pesan
        ]);

        // Batasi 2 kata
        $pesanSingkat = implode(' ', array_slice(explode(' ', $pesan), 0, 2));

        $push = [];

        // Kirim notifikasi untuk anggota penanganan, kecuali pengirim pesan (dirinya sendiri)
        foreach ($kasus->anggota_penanganans as $anggota) {
            if ($anggota->user_id !== auth()->user()->id) {  // Cek apakah anggota bukan pengirim pesan
                Notifikasi::create([
                    'kasus_id' => $id,
                    'user_id' => $anggota->user_id,
                    'push_notifikasi_terkirim' => true,
                    'pesan' => 'Ada pesan baru dari ' . auth()->user()->name . ': ' . $pesanSingkat,
                    'jenis' => 'chat',
                    'read' => false,
                ]);

                if ($anggota->user->onesignal_id) {
                    $push[] = $anggota->user->onesignal_id;
                }
            }
        }

        // Kirim notifikasi untuk admin, kecuali pengirim pesan (dirinya sendiri)
        foreach ($listAdmin as $adm) {
            if ($adm->id !== auth()->user()->id) {  // Cek apakah admin bukan pengirim pesan
                Notifikasi::create([
                    'kasus_id' => $id,
                    'user_id' => $adm->id,
                    'pesan' => 'Ada pesan baru dari ' . auth()->user()->name . ': ' . $pesanSingkat,
                    'push_notifikasi_terkirim' => true,
                    'jenis' => 'chat',
                    'read' => false,
                ]);

                if ($adm->onesignal_id) {
                    $push[] = $adm->onesignal_id;
                }
            }
        }

        // kirim notifikasi ke pembuat kasus, kecuali pengirim pesan (dirinya sendiri)
        if ($kasus->pelapor_id !== auth()->user()->id) {
            Notifikasi::create([
                'kasus_id' => $id,
                'user_id' => $kasus->pelapor_id,
                'pesan' => 'Ada pesan baru dari ' . auth()->user()->name . ': ' . $pesanSingkat,
                'push_notifikasi_terkirim' => true,
                'jenis' => 'chat',
                'read' => false,
            ]);

            if ($kasus->user->onesignal_id) {
                $push[] = $kasus->user->onesignal_id;
            }
        }

        if (count($push) > 0) {
            $title = "Pesan Baru";
            $body = 'Ada pesan baru dari ' . auth()->user()->name . ': ' . $pesanSingkat;
            $url = "/manajemen-kasus/$id/chat";

            PushNotification::SendOneSignalNotification($push, $title, $body, $url = $url);
        }

        return back()->with('success', 'Pesan berhasil dikirim');
    }

    public function verifikasiBuktiPekerjaan(Request $request, $kasus_id, $user_id)
    {
        // Validasi form
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:diterima,tidak_diterima',
            'keterangan' => 'nullable|string',
        ]);

        // Cari anggota penanganan terkait
        $anggotaPenanganan = AnggotaPenanganan::where('kasus_id', $kasus_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$anggotaPenanganan) {
            return back()->with('error', 'Anggota penanganan tidak ditemukan.');
        }

        // Simpan status verifikasi dan keterangan
        if ($validated['status_verifikasi'] == "diterima") {
            $anggotaPenanganan->selesai = 1;
            $anggotaPenanganan->poin_diperoleh = 1;
            $anggotaPenanganan->selesai_pada = now();
            $anggotaPenanganan->keterangan_admin = $validated['keterangan'];
            $anggotaPenanganan->save();

            // hitung ulang poin user
            $totalPoint = AnggotaPenanganan::where('user_id', '=', $user_id)->sum('poin_diperoleh');
            // ubah kesediaan dan poin
            $user = User::find($anggotaPenanganan->user_id);
            $user->total_poin = $totalPoint;
            $user->save();

            Notifikasi::create([
                'kasus_id' => $kasus_id,
                'user_id' => $user_id,
                'push_notifikasi_terkirim' => true,
                'pesan' => 'Selamat, Admin baru saja menerima bukti pengerjaan anda, cek sekarang',
                'jenis' => 'penugasan',
                'read' => false,
            ]);
        } else {
            $anggotaPenanganan->selesai = 0;
            $anggotaPenanganan->poin_diperoleh = 0;
            $anggotaPenanganan->selesai_pada = null;
            $anggotaPenanganan->keterangan_admin = $validated['keterangan'];
            $anggotaPenanganan->save();

            // hitung ulang poin user
            $totalPoint = AnggotaPenanganan::where('user_id', '=', $user_id)->sum('poin_diperoleh');
            // ubah kesediaan dan poin
            $user = User::find($anggotaPenanganan->user_id);
            $user->total_poin = $totalPoint;
            $user->save();

            Notifikasi::create([
                'kasus_id' => $kasus_id,
                'user_id' => $user_id,
                'push_notifikasi_terkirim' => true,
                'pesan' => 'Mohon maaf, Admin menolak pengerjaan anda, cek sekarang',
                'jenis' => 'penugasan',
                'read' => false,
            ]);
        }

        return back()->with('success', 'Verifikasi pekerjaan berhasil disimpan.');
    }

    public function hapusAnggota($kasus_id, $user_id)
    {
        // Cari anggota penanganan terkait
        $anggotaPenanganan = AnggotaPenanganan::where('kasus_id', $kasus_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$anggotaPenanganan) {
            return back()->with('error', 'Anggota penanganan tidak ditemukan.');
        }

        // Hapus anggota dari penanganan kasus
        $anggotaPenanganan->delete();

        return back()->with('success', 'Anggota telah berhasil dihapus.');
    }

    public function resetBuktiPekerjaan(Request $request, $kasus_id, $user_id)
    {
        // Ambil anggota penanganan berdasarkan kasus dan user
        $anggotaPenanganan = AnggotaPenanganan::where('kasus_id', $kasus_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$anggotaPenanganan) {
            return back()->with('error', 'Anggota penanganan tidak ditemukan.');
        }

        // Ambil semua bukti yang terkait dengan anggota penanganan ini
        $buktiPekerjaan = BuktiPenyelesaianKasu::where('anggota_penanganan_id', $anggotaPenanganan->id)->get();

        if ($buktiPekerjaan->isEmpty()) {
            return back()->with('error', 'Tidak ada bukti pekerjaan yang dapat direset.');
        }

        // Hapus file yang ada di storage
        foreach ($buktiPekerjaan as $bukti) {
            // Hapus file dari storage
            Storage::disk('public')->delete($bukti->path);

            // Hapus data bukti pekerjaan dari database
            $bukti->delete();
        }

        return back()->with('success', 'Bukti pekerjaan berhasil direset.');
    }


    public function storeBuktiPekerjaan(Request $request, $id)
    {
        $anggotaPenanganan = AnggotaPenanganan::where('kasus_id', $id)->where('user_id', auth()->user()->id)->first();
        if (!$anggotaPenanganan) {
            return back()->with('error', 'Anda tidak terdaftar sebagai anggota penanganan');
        }

        // cek jika ada sebelumnya
        $listBukti = BuktiPenyelesaianKasu::where('anggota_penanganan_id', $anggotaPenanganan->id)->get();
        if (count($listBukti) > 0) {
            return back()->with('warning', 'Pekerjaan anda sedang di proses oleh Admin, silahkan ditunggu ya');
        }

        // simpan
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate unique filename
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Nama asli tanpa ekstensi
                $timestamp = now()->format('YmdHis'); // Timestamp saat ini
                $extension = $file->getClientOriginalExtension(); // Ekstensi file
                $filename = $originalFileName . '-' . $timestamp . '.' . $extension;

                // Define storage path
                $path = 'bukti_pekerjaan/' . $filename;

                // Store file
                Storage::disk('public')->put($path, file_get_contents($file));

                // Get file MIME type and size
                $mime = $file->getMimeType();
                $size = round($file->getSize() / 1024, 2); // Convert size to KB

                // Create bukti_kasus record
                BuktiPenyelesaianKasu::create([
                    'anggota_penanganan_id' => $anggotaPenanganan->id,
                    'path' => $path,
                    'mime' => $mime,
                    'size' => $size, // in MB
                    'keterangan' => 'Bukti pekerjaan'
                ]);
            }
        }

        $listAdmin = User::where('peran', '=', 'ADMIN')->get();
        foreach ($listAdmin as $adm) {
            Notifikasi::create([
                'kasus_id' => $id,
                'user_id' => $adm->id,
                'push_notifikasi_terkirim' => true,
                'pesan' => auth()->user()->name . ' baru saja mengirim bukti pengerjaan, cek sekarang',
                'jenis' => 'penugasan',
                'read' => false,
            ]);
        }

        return back()->with('success', 'Terima kasih, Pekerjaan anda akan di proses oleh Admin, silahkan ditunggu ya');
    }
}
