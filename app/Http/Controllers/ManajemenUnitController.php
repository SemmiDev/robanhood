<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Constant;
use App\Models\UnitPolisi;
use Illuminate\Http\Request;

class ManajemenUnitController extends Controller
{
    public function index()
    {
        if (request()->has('q')) {
            $listUnitPolisi = UnitPolisi::where('nama', 'like', '%' . request('q') . '%')
                ->orWhere('deskripsi', 'like', '%' . request('q') . '%')
                ->latest()
                ->paginate(Constant::PER_PAGE);
        } else {
            $listUnitPolisi = UnitPolisi::latest()->paginate(Constant::PER_PAGE);
        }

        return view('app.manajemen-unit.index', [
            'listUnitPolisi' => $listUnitPolisi,
        ]);
    }

    public function create()
    {
        $listNamaUnitPolisi = UnitPolisi::select('nama')->distinct()->get();
        return view('app.manajemen-unit.create', [
            'listNamaUnitPolisi' => $listNamaUnitPolisi,
        ]);
    }

    public function update(Request $request, $id)
    {
        $unit = UnitPolisi::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect(route('manajemenUnit'))->with('success', 'Data berhasil disimpan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $unit = UnitPolisi::create($validated);
        return redirect(route('manajemenUnit'))->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $listNamaUnitPolisi = UnitPolisi::select('nama')->distinct()->get();

        $unit = UnitPolisi::findOrFail($id);
        return view('app.manajemen-unit.edit', [
            'unit' => $unit,
            'listNamaUnitPolisi' => $listNamaUnitPolisi,
        ]);
    }

    public function destroy($id)
    {
        $unitPolisi = UnitPolisi::find($id);
        if ($unitPolisi) {
            $unitPolisi->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Data not found']);
    }
}
