<?php

namespace App\Http\Controllers;

use App\Models\Pencipta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenciptaController extends Controller
{
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Pencipta::when($katakunci, function ($q, $k) {
                return $q->where('pencipta_arsip', 'like', "%$k%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pencipta_arsip.viewpencipta', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    public function create()
    {
        return view('pencipta_arsip.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pencipta_arsip' => 'required|string|max:150|unique:pencipta_arsip,pencipta_arsip',
        ], [
            'pencipta_arsip.required' => 'Nama pencipta wajib diisi.',
            'pencipta_arsip.unique' => 'Nama pencipta sudah ada.',
        ]);

        DB::beginTransaction();
        try {
            Pencipta::create([
                'pencipta_arsip' => $request->pencipta_arsip,
            ]);
            DB::commit();
            return redirect()->route('pencipta_arsip.viewpencipta')->with('success', 'Pencipta arsip berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Pencipta::findOrFail($id);
        return view('pencipta_arsip.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Pencipta::findOrFail($id);

        $request->validate([
            'pencipta_arsip' => 'required|string|max:150|unique:pencipta_arsip,pencipta_arsip,' . $id,
        ]);

        DB::beginTransaction();
        try {
            $item->update([
                'pencipta_arsip' => $request->pencipta_arsip,
            ]);
            DB::commit();
            return redirect()->route('pencipta_arsip.viewpencipta')->with('success', 'Pencipta arsip berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Pencipta::findOrFail($id);
        $item->delete();
        return redirect()->route('pencipta_arsip.viewpencipta')->with('success', 'Pencipta arsip berhasil dihapus.');
    }
}
