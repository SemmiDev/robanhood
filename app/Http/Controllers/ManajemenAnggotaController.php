<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Constant;
use App\Models\PangkatPolisi;
use App\Models\UnitPolisi;
use App\Models\User;
use Illuminate\Http\Request;

class ManajemenAnggotaController extends Controller
{
    public function index()
    {
        $query = User::with([
            'profil_polisis',
            'profil_polisis.unit_polisi',
            'profil_polisis.pangkat_polisi'
        ]);

        $query->when(request()->has('q'), function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('q') . '%')
                    ->orWhereHas('profil_polisis', function ($subQuery) {
                        $subQuery->where('nrp', 'like', '%' . request('q') . '%');
                    });
            });
        });

        if (request()->has('status') && request('status') != '') {
            $query->where('aktif', request('status'));
        }

        if (request()->has('acc') && request('acc') != '') {
            $query->where('acc', request('acc'));
        }

        if (request()->has('peran') && request('peran') != '') {
            $query->where('peran', request('peran'));
        }

        if (request()->has('order_by') && request()->has('order_type')) {
            $orderBy = request('order_by');
            $orderType = request('order_type', 'ASC');

            if ($orderBy == 'nrp' || $orderBy == 'jenis_kelamin') {
                $query->join('profil_polisi', 'users.id', '=', 'profil_polisi.user_id')
                    ->orderBy('profil_polisi.' . $orderBy, $orderType)
                    ->select('users.*');
            } else if ($orderBy == 'name') {
                $query->orderBy('name', $orderType);
            }
        }

        $users = $query->orderBy('peran')->paginate(Constant::PER_PAGE);

        return view('app.manajemen-anggota.index', [
            'users' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::with([
            'profil_polisis',
            'profil_polisis.unit_polisi',
            'profil_polisis.pangkat_polisi',
            'anggota_penanganans' => function ($query) {
                $query->where('aktif', true) // Hanya ambil yang aktif
                    ->orderBy('created_at', 'desc');
            },
            'anggota_penanganans.kasu',
            'anggota_penanganans.kasu.anggota_penanganans',
        ])
            ->where('id', '=', $id)
            ->first();

        if (!$user) {
            return redirect(route('manajemenAnggota'))->with('error', 'Anggota tidak ditemukan');
        }

        return view('app.manajemen-anggota.show', [
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        // Cari user berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Hapus user
        $user->delete();

        return redirect(route('manajemenAnggota'))->with('success', $user->name . ' Berhasil dihapus');
    }


    public function edit($id)
    {
        $user = User::with([
            'profil_polisis',
            'profil_polisis.unit_polisi',
            'profil_polisis.pangkat_polisi'
        ])
            ->where('id', '=', $id)
            ->first();

        if (!$user) {
            return redirect(route('manajemenAnggota'))->with('error', 'Anggota tidak ditemukan');
        }

        return view('app.manajemen-anggota.edit', [
            'user' => $user,
            'unitPolisi' => UnitPolisi::all(),
            'pangkatPolisi' => PangkatPolisi::all(),
        ]);
    }
}
