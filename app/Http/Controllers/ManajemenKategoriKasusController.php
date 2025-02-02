<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Constant;
use App\Models\AnggotaPenanganan;
use App\Models\Kasu;
use App\Models\KategoriKasu;
use App\Models\User;
use Illuminate\Http\Request;

class ManajemenKategoriKasusController extends Controller
{
    public function index()
    {
        if (request()->has('q')) {
            $listKategoriKasus = KategoriKasu::where('nama', 'like', '%' . request('q') . '%')
                ->orWhere('deskripsi', 'like', '%' . request('q') . '%')
                ->latest()
                ->paginate(Constant::PER_PAGE);
        } else {
            $listKategoriKasus = KategoriKasu::latest()->paginate(Constant::PER_PAGE);
        }

        return view('app.manajemen-kategori-kasus.index', [
            'listKategoriKasus' => $listKategoriKasus,
        ]);
    }

    public function create()
    {
        return view('app.manajemen-kategori-kasus.create', []);
    }

    public function update(Request $request, $id)
    {
        $kategoriKasus = KategoriKasu::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'simbol' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
            'pengingat' => 'nullable|string',
        ]);

        if ($request->hasFile('simbol')) {
            $simbolPath = $request->file('simbol')->store('kategori_kasus/simbol', 'public');
            $validated['simbol'] = $simbolPath;
        }

        $kategoriKasus->update($validated);

        return redirect(route('manajemenKategoriKasus'))->with('success', 'Data berhasil disimpan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'simbol' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
            'pengingat' => 'nullable|string',
        ]);

        if ($request->hasFile('simbol')) {
            $simbolPath = $request->file('simbol')->store('kategori_kasus/simbol', 'public');
            $validated['simbol'] = $simbolPath;
        }

        $kategoriKasus = KategoriKasu::create($validated);
        return redirect(route('manajemenKategoriKasus'))->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $kategoriKasus = KategoriKasu::findOrFail($id);
        return view('app.manajemen-kategori-kasus.edit', [
            'kategoriKasus' => $kategoriKasus,
        ]);
    }

    public function destroy($id)
    {
        $kategoriKasus = KategoriKasu::find($id);
        if ($kategoriKasus) {
            $kategoriKasus->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Data not found']);
    }
}
