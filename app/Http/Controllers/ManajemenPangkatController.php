<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Constant;
use App\Models\PangkatPolisi;
use Illuminate\Http\Request;

class ManajemenPangkatController extends Controller
{
    public function index()
    {
        if (request()->has('q')) {
            $listPangkatPolisi = PangkatPolisi::where('nama', 'like', '%' . request('q') . '%')
                ->orWhere('grup', 'like', '%' . request('q') . '%')
                ->orWhere('deskripsi', 'like', '%' . request('q') . '%')
                ->latest()
                ->paginate(Constant::PER_PAGE);
        } else {
            $listPangkatPolisi = PangkatPolisi::latest()->paginate(Constant::PER_PAGE);
        }

        return view('app.manajemen-pangkat.index', [
            'listPangkatPolisi' => $listPangkatPolisi,
        ]);
    }

    public function create()
    {
        $listNamaPangkatPolisi = PangkatPolisi::select('nama')->distinct()->get();
        $listGrupPangkatPolisi = PangkatPolisi::select('grup')->distinct()->get();
        return view('app.manajemen-pangkat.create', [
            'listNamaPangkatPolisi' => $listNamaPangkatPolisi,
            'listGrupPangkatPolisi' => $listGrupPangkatPolisi,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pangkat = PangkatPolisi::findOrFail($id);

        $validated = $request->validate([
            'grup' => 'sometimes|required|string|max:255',
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $pangkat->update($validated);

        return redirect(route('manajemenPangkat'))->with('success', 'Data berhasil disimpan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grup' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $pangkat = PangkatPolisi::create($validated);
        return redirect(route('manajemenPangkat'))->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $listNamaPangkatPolisi = PangkatPolisi::select('nama')->distinct()->get();
        $listGrupPangkatPolisi = PangkatPolisi::select('grup')->distinct()->get();

        $pangkat = PangkatPolisi::findOrFail($id);
        return view('app.manajemen-pangkat.edit', [
            'pangkat' => $pangkat,
            'listNamaPangkatPolisi' => $listNamaPangkatPolisi,
            'listGrupPangkatPolisi' => $listGrupPangkatPolisi,
        ]);
    }

    public function destroy($id)
    {
        $pangkatPolisi = PangkatPolisi::find($id);
        if ($pangkatPolisi) {
            $pangkatPolisi->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Data not found']);
    }
}
