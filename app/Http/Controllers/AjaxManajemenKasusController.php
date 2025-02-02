<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Constant;
use App\Models\Kasu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AjaxManajemenKasusController extends Controller
{
    public function index(Request $request)
    {
        $query = Kasu::with(['kategori_kasu', 'user'])
            ->where('status', '!=', 'SELESAI')
            ->where('status', '!=', 'DITUTUP')
            ->orderByRaw("
            CASE
                WHEN jenis = 'sos' THEN 0
                ELSE 1
            END, waktu_kejadian DESC
        ")
            ->limit(50);

        $listKasus = $query->get();
        foreach ($listKasus as $kasus) {
            if ($kasus->jenis == "sos") {
                $kasus->latitude = $kasus->user->latitude_terakhir ?? 0;
                $kasus->longitde = $kasus->user->longitude_terakhir ?? 0;
            }

            if ($kasus->jenis == "sos") {
                $kasus->rute = "/manajemen-kasus/{$kasus->id}/rute-sos";
                $kasus->show = "/manajemen-kasus/{$kasus->id}/show";
            } else {
                $kasus->rute = "/manajemen-kasus/{$kasus->id}/rute";
                $kasus->show = "/manajemen-kasus/{$kasus->id}/show";
            }
        }


        return response()->json($listKasus);
    }
}
