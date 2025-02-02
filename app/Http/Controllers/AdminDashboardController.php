<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\TimeBasedGreetingHelper;
use App\Models\Kasu;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfYear()->toDateString());  // Awal tahun ini
        $endDate = $request->query('end_date', now()->endOfYear()->toDateString());      // Akhir tahun ini

        $filter = compact('startDate', 'endDate');

        // Tidak lagi mengandalkan auth() untuk mendapatkan nama user
        $greeting = new TimeBasedGreetingHelper();
        $greeting = $greeting->getGreeting() . ', Admin!';  // Menampilkan "Admin" sebagai sapaan global

        // Ambil data kartu secara global tanpa mengaitkan dengan user
        $cards = $this->getCardsData($filter);
        // dd($cards);

        return view('index', compact('cards', 'filter', 'greeting'));
    }

    private function getCardsData(array $filter)
    {
        return [
            'total_anggota_polisi' => $this->getTotalAnggotaPolisi($filter),
            'total_anggota_warga' => $this->getTotalAnggotaWarga($filter),

            'total_kasus' => $this->getTotalKasus($filter),
            'total_menunggu' => $this->getTotalKasusMenunggu($filter),
            'total_dalam_proses' => $this->getTotalKasusDalamProses($filter),
            'total_selesai' => $this->getTotalKasusSelesai($filter),
            'total_ditutup' => $this->getTotalKasusDitutup($filter),

            'kasus_keparahan' => $this->getKeparahanData($filter),
            'kasus_status' => $this->getStatusData($filter),
            'total_total_kasus_berdasarkan_kategori' => $this->getTotalKasusBerdasarkanKategori($filter),
        ];
    }

    // Fungsi-fungsi untuk mengambil data berdasarkan filter (misal start_date, end_date)
    private function getTotalAnggotaPolisi(array $filter)
    {
        // Query untuk mendapatkan data anggota polisi berdasarkan filter
        return User::where('peran', 'POLISI')->whereBetween('created_at', [$filter['startDate'], $filter['endDate']])->count();
    }

    private function getTotalAnggotaWarga(array $filter)
    {
        // Query untuk mendapatkan data anggota warga berdasarkan filter
        return User::where('peran', 'WARGA')->whereBetween('created_at', [$filter['startDate'], $filter['endDate']])->count();
    }

    private function getTotalKasus(array $filter)
    {
        // Query untuk mendapatkan data total kasus yang terlibat
        return Kasu::whereBetween('created_at', [$filter['startDate'], $filter['endDate']])->count();
    }

    private function getTotalKasusSelesai(array $filter)
    {
        // Query untuk mendapatkan data kasus yang selesai
        return Kasu::where('status', 'SELESAI')->whereBetween('created_at', [$filter['startDate'], $filter['endDate']])->count();
    }

    private function getTotalKasusDitutup(array $filter)
    {
        // Query untuk mendapatkan data kasus yang selesai
        return Kasu::where('status', 'DITUTUP')->whereBetween('created_at', [$filter['startDate'], $filter['endDate']])->count();
    }


    private function getTotalKasusMenunggu(array $filter)
    {
        // Query untuk mendapatkan data kasus dalam proses
        return Kasu::where('status', 'MENUNGGU')->whereBetween('created_at', [$filter['startDate'], $filter['endDate']])->count();
    }

    private function getTotalKasusDalamProses(array $filter)
    {
        // Query untuk mendapatkan data kasus dalam proses
        return Kasu::where('status', 'DALAM_PROSES')->whereBetween('created_at', [$filter['startDate'], $filter['endDate']])->count();
    }

    private function getKeparahanData(array $filter)
    {
        // Ambil data tingkat keparahan kasus
        return Kasu::selectRaw('tingkat_keparahan, COUNT(*) as count')
            ->whereBetween('created_at', [$filter['startDate'], $filter['endDate']])
            ->groupBy('tingkat_keparahan')
            ->pluck('count', 'tingkat_keparahan')
            ->toArray();
    }

    private function getStatusData(array $filter)
    {
        // Ambil data status kasus
        return Kasu::selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$filter['startDate'], $filter['endDate']])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    private function getTotalKasusBerdasarkanKategori(array $filter)
    {
        // Ambil data total kasus berdasarkan kategori dengan mengakses nama kategori
        return Kasu::selectRaw('kategori_kasus.nama as kategori, COUNT(*) as count')
            ->join('kategori_kasus', 'kategori_kasus.id', '=', 'kasus.kategori_kasus_id') // Ganti dengan nama kolom yang sesuai
            ->whereBetween('kasus.created_at', [$filter['startDate'], $filter['endDate']])
            ->groupBy('kategori_kasus.nama') // Mengelompokkan berdasarkan nama kategori
            ->pluck('count', 'kategori')
            ->toArray();
    }

    public function updateStatusPolisi(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'acc' => 'required|boolean',
        ]);

        $user = User::find($request->user_id);
        $user->acc = $request->acc;
        $user->save();

        return back()->with('status', 'Status berhasil diperbarui!');
    }

    public function updateAktifAnggota(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'aktif' => 'required|boolean',
        ]);

        $user = User::find($request->user_id);
        $user->aktif = $request->aktif;
        $user->save();

        return back()->with('status', 'Status berhasil diperbarui!');
    }
}

