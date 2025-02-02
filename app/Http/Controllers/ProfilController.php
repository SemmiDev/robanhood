<?php

namespace App\Http\Controllers;

use App\Models\PangkatPolisi;
use App\Models\UnitPolisi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::with([
            'profil_polisis',
            'profil_polisis.unit_polisi',
            'profil_polisis.pangkat_polisi',
            'anggota_penanganans' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'anggota_penanganans.kasu',
            'anggota_penanganans.kasu.anggota_penanganans',
        ])
            ->where('id', '=', auth()->user()->id)
            ->first();

        return view('app.profil.index', [
            'user' => $user,
        ]);
    }

    public function edit()
    {
        $id = request()->get('id');
        if (!$id) {
            $id = auth()->user()->id;
        }

        $user = User::with([
            'profil_polisis',
            'profil_polisis.unit_polisi',
            'profil_polisis.pangkat_polisi'
        ])
            ->where('id', '=', $id)
            ->first();

        return view('app.profil.edit', [
            'user' => $user,
            'unitPolisi' => UnitPolisi::all(),
            'pangkatPolisi' => PangkatPolisi::all(),
        ]);
    }

    public function update()
    {
        $id = request()->get('id') ?? auth()->user()->id;
        $user = User::with([
            'profil_polisis',
            'profil_polisis.unit_polisi',
            'profil_polisis.pangkat_polisi'
        ])->find($id);

        if (!$user) {
            return back()->withErrors(['id' => 'User not found.']);
        }

        // Update data pengguna
        $user->name = request()->get('name');
        $user->no_whatsapp = request()->get('no_whatsapp');
        $user->no_telepon = request()->get('no_telepon');
        $user->nik = request()->get('nik');

        if ($user->peran == 'ADMIN' || $user->peran == 'POLISI') {
            $user->profil_polisis()->update([
                'unit_id' => request()->get('unit_id'),
                'pangkat_id' => request()->get('pangkat_id'),
                'nrp' => request()->get('nrp'),
                'tempat_lahir' => request()->get('tempat_lahir'),
                'tanggal_lahir' => request()->get('tanggal_lahir'),
                'jenis_kelamin' => request()->get('jenis_kelamin'),
                'golongan_darah' => request()->get('golongan_darah'),
                'agama' => request()->get('agama'),
                'status_pernikahan' => request()->get('status_pernikahan'),
                'spesialisasi' => request()->get('spesialisasi'),
                'jabatan' => request()->get('jabatan'),
            ]);
        }

        // Proses upload KTP
        if (request()->hasFile('foto_ktp')) {
            $ktp = request()->file('foto_ktp');
            if ($ktp->isValid() && $ktp->getSize() <= 2048 * 1024 && in_array($ktp->getClientOriginalExtension(), ['jpeg', 'png', 'jpg', 'pdf'])) {
                $ktpName = 'ktp_' . time() . '.' . $ktp->getClientOriginalExtension();
                $ktpPath = $ktp->storeAs('documents', $ktpName, 'public');
                $user->foto_ktp = $ktpPath;
            } else {
                return back()
                ->with('error', 'Format atau ukuran file KTP tidak valid (maks 2MB, jpg/png/pdf).')
                ->withErrors(['ktp' => 'Format atau ukuran file KTP tidak valid (maks 2MB, jpg/png/pdf).']);
            }
        }

        // Proses upload KK
        if (request()->hasFile('foto_kk')) {
            $kk = request()->file('foto_kk');
            if ($kk->isValid() && $kk->getSize() <= 2048 * 1024 && in_array($kk->getClientOriginalExtension(), ['jpeg', 'png', 'jpg', 'pdf'])) {
                $kkName = 'kk_' . time() . '.' . $kk->getClientOriginalExtension();
                $kkPath = $kk->storeAs('documents', $kkName, 'public');
                $user->foto_kk = $kkPath;
            } else {
                return back()
                ->with('error', 'Format atau ukuran file KK tidak valid (maks 2MB, jpg/png/pdf).')
                ->withErrors(['kk' => 'Format atau ukuran file KK tidak valid (maks 2MB, jpg/png/pdf).']);
            }
        }

        $user->save();

        return back()->with('success', 'Data berhasil diperbarui.');
    }


    public function updateFotoProfil()
    {
        $id = request()->get('id');
        if (!$id) {
            $id = auth()->user()->id;
        }

        $user = User::find($id);
        if (!$user) {
            return back()->withErrors(['id' => 'User not found.']);
        }

        $avatar = request()->file('avatar');

        if ($avatar) {
            // Buat nama file unik dengan timestamp dan ekstensi asli
            $imageName = time() . '.' . $avatar->getClientOriginalExtension();

            // Simpan file di folder 'foto_profil' dalam 'storage/app/public'
            $logoPath = $avatar->storeAs('foto_profil', $imageName, 'public');

            // Simpan path ke database
            $user->avatar = $logoPath;
            $user->save();
        }



        return back()->with('success', 'Foto profil berhasil diubah');
    }

    public function updatePassword()
    {
        $id = request()->get('id');
        if (!$id) {
            $id = auth()->user()->id;
        }

        $user = User::find($id);
        if (!$user) {
            return back()->withErrors(['id' => 'User not found.']);
        }

        $validatedData = request()->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required_with:password|same:password',
        ]);

        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return back()->with('success', 'Password berhasil diubah');
    }
}
