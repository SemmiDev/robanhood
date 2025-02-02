<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\TimeBasedGreetingHelper;
use App\Models\AnggotaPenanganan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolisiDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $filter = compact('startDate', 'endDate');

        $greeting = new TimeBasedGreetingHelper();
        $greeting = $greeting->getGreeting() . ', ' . auth()->user()->name . '!';

        $cards = $this->getCardsData($filter);
        // dd($cards);

        return view('app.dashboard-polisi.dashboard', compact('cards', 'filter', 'greeting'));
    }

    private function getCardsData(array $filter)
    {
        return [
            'total_terlibat' => $this->getTotalKasusTerlibat($filter),
            'total_selesai' => $this->getTotalKasusSelesai($filter),
            'total_dalam_proses' => $this->getTotalKasusDalamProses($filter),
            'total_poin' => $this->getTotalPoinDiperoleh($filter),
            'kasus_keparahan' => $this->getKeparahanData($filter),
            'kasus_status' => $this->getStatusData($filter),
            'total_total_kasus_berdasarkan_kategori' => $this->getTotalKasusBerdasarkanKategori($filter),
        ];
    }

    private function getTotalKasusBerdasarkanKategori(array $filter)
    {
        $query = AnggotaPenanganan::select('kategori_kasus.nama', DB::raw('COUNT(kasus.id) as total'))
            ->join('kasus', 'kasus.id', '=', 'anggota_penanganan.kasus_id')
            ->join('kategori_kasus', 'kategori_kasus.id', '=', 'kasus.kategori_kasus_id')
            ->where('anggota_penanganan.user_id', auth()->user()->id);

        if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
            $query->whereBetween('anggota_penanganan.created_at', [$filter['startDate'], $filter['endDate']]);
        }

        $results = $query->groupBy('kategori_kasus.nama')
            ->pluck('total', 'kategori_kasus.nama')
            ->toArray();

        // Normalize data to include all categories, if desired
        return $results;
    }

    private function getWaktuResponRataRataKasusDalamMenit(array $filter)
    {
        $query = AnggotaPenanganan::join('kasus', 'kasus.id', '=', 'anggota_penanganan.kasus_id')
            ->where('anggota_penanganan.user_id', auth()->user()->id)
            ->whereNotNull('kasus.waktu_respon'); // Filter only records with a valid response time

        if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
            $query->whereBetween('anggota_penanganan.created_at', [$filter['startDate'], $filter['endDate']]);
        }

        $averageResponseTime = $query->avg(DB::raw('TIME_TO_SEC(kasus.waktu_respon)')) ?: 0;

        // Convert seconds to minutes
        return round($averageResponseTime / 60, 2);
    }


    private function getKeparahanData(array $filter)
    {
        $keparahanEnum = ['RINGAN', 'SEDANG', 'BERAT', 'LAINNYA'];

        $query = AnggotaPenanganan::select('kasus.tingkat_keparahan', DB::raw('COUNT(kasus.id) as total'))
            ->join('kasus', 'kasus.id', '=', 'anggota_penanganan.kasus_id')
            ->where('anggota_penanganan.user_id', auth()->user()->id);

        if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
            $query->whereBetween('anggota_penanganan.created_at', [$filter['startDate'], $filter['endDate']]);
        }

        $results = $query->groupBy('kasus.tingkat_keparahan')
            ->pluck('total', 'kasus.tingkat_keparahan')
            ->toArray();

        // Normalize data
        return collect($keparahanEnum)->mapWithKeys(function ($level) use ($results) {
            return [$level => $results[$level] ?? 0];
        })->toArray();
    }

    private function getStatusData(array $filter)
    {
        $statusEnum = ['MENUNGGU', 'DALAM_PROSES', 'SELESAI', 'DITUTUP'];

        $query = AnggotaPenanganan::select('kasus.status', DB::raw('COUNT(kasus.id) as total'))
            ->join('kasus', 'kasus.id', '=', 'anggota_penanganan.kasus_id')
            ->where('anggota_penanganan.user_id', auth()->user()->id);

        if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
            $query->whereBetween('anggota_penanganan.created_at', [$filter['startDate'], $filter['endDate']]);
        }

        $results = $query->groupBy('kasus.status')
            ->pluck('total', 'kasus.status')
            ->toArray();

        // Normalize data
        return collect($statusEnum)->mapWithKeys(function ($status) use ($results) {
            return [$status => $results[$status] ?? 0];
        })->toArray();
    }

    private function getTotalKasusTerlibat(array $filter)
    {
        $query = AnggotaPenanganan::where('user_id', auth()->user()->id);

        if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
            $query->whereBetween('created_at', [$filter['startDate'], $filter['endDate']]);
        }

        return $query->count();
    }

    private function getTotalKasusDalamProses(array $filter)
    {
        $query = AnggotaPenanganan::join('kasus', 'kasus.id', '=', 'anggota_penanganan.kasus_id')
            ->where('anggota_penanganan.user_id', auth()->user()->id)
            ->where('anggota_penanganan.selesai', false);

        if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
            $query->whereBetween('anggota_penanganan.created_at', [$filter['startDate'], $filter['endDate']]);
        }

        return $query->count();
    }

    private function getTotalKasusSelesai(array $filter)
    {
        $query = AnggotaPenanganan::join('kasus', 'kasus.id', '=', 'anggota_penanganan.kasus_id')
            ->where('anggota_penanganan.user_id', auth()->user()->id)
            ->where('anggota_penanganan.selesai', true);

        if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
            $query->whereBetween('anggota_penanganan.created_at', [$filter['startDate'], $filter['endDate']]);
        }

        return $query->count();
    }


    private function getTotalPoinDiperoleh(array $filter)
    {
        $query = AnggotaPenanganan::where('user_id', auth()->user()->id);

        if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
            $query->whereBetween('created_at', [$filter['startDate'], $filter['endDate']]);
        }

        return $query->sum('poin_diperoleh');
    }
}
