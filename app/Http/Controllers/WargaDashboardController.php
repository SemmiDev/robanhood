<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Constant;
use App\Http\Controllers\Helpers\TimeBasedGreetingHelper;
use App\Models\Kasu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WargaDashboardController extends Controller
{
    public function index(Request $request)
    {

        $greeting = new TimeBasedGreetingHelper();
        $greeting = $greeting->getGreeting() . ', ' . auth()->user()->name . '!';


        $query = Kasu::with(
            [
                'kategori_kasu',
                'bukti_kasus',
                'anggota_penanganans',
                'anggota_penanganans.user',
            ]
        )
            ->where('pelapor_id', '=', auth()->user()->id)
            ->orderBy('waktu_kejadian', 'DESC');

        // Search filter
        if ($request->filled('q')) {
            $searchTerm = $request->get('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('judul', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Severity (Keparahan) filter
        if ($request->filled('keparahan')) {
            $query->where('tingkat_keparahan', $request->get('keparahan'));
        }

        // Period (Periode) filter
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

        $listKasus = $query->orderBy('waktu_kejadian')->paginate(Constant::PER_PAGE)
            ->appends($request->except('page'));

        $view = "app.dashboard-warga.dashboard";
        return view($view, [
            'greeting' => $greeting,
            'listKasus' => $listKasus,
            'totalAnggota' => $totalAnggota ?? 0,
            'totalKasus' => $totalKasus ?? 0,
            'totalKasusBelumDitangani' => $totalKasusBelumDitangani ?? 0,
            'responseMetrics' => $responseMetrics ?? 0,
        ]);
    }
}
