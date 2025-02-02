<?php

namespace App\Http\Controllers;

use App\Models\PengaturanWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManajemenPengaturanWebsiteController extends Controller
{
    public function index()
    {
        $pengaturanWebsite = PengaturanWebsite::first();

        return view('app.manajemen-pengaturan-website.index', [
            'pengaturanWebsite' => $pengaturanWebsite,
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'izinkan_warga_daftar' => 'nullable',
            'izinkan_polisi_daftar' => 'nullable',
            'radius_notifikasi' => 'nullable',
        ]);

        // Tambahkan default false jika tidak diisi
        $validatedData['izinkan_warga_daftar'] = $request->filled('izinkan_warga_daftar') ? (bool) $request->izinkan_warga_daftar : false;
        $validatedData['izinkan_polisi_daftar'] = $request->filled('izinkan_polisi_daftar') ? (bool) $request->izinkan_polisi_daftar : false;


        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('pengaturan-website', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $pengaturanWebsite = PengaturanWebsite::first();
        $pengaturanWebsite->update($validatedData);
        return redirect()->route('manajemenPengaturanWebsite')->with('success', 'Pengaturan website berhasil diperbarui');
    }
}
