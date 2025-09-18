<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Lokasi::when($katakunci, function ($q, $k) {
                return $q->where('lokasi_arsip', 'like', "%$k%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('lokasi_arsip.viewlokasi', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    public function create()
    {
        return view('lokasi_arsip.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi_arsip' => 'required|string|max:150|unique:lokasi_arsip,lokasi_arsip',
        ], [
            'lokasi_arsip.required' => 'Lokasi wajib diisi.',
            'lokasi_arsip.unique' => 'Lokasi sudah ada.',
        ]);

        DB::beginTransaction();
        try {
            Lokasi::create([
                'lokasi_arsip' => $request->lokasi_arsip,
            ]);
            DB::commit();
            return redirect()->route('lokasi_arsip.viewlokasi')->with('success', 'Lokasi arsip berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Lokasi::findOrFail($id);
        return view('lokasi_arsip.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Lokasi::findOrFail($id);

        $request->validate([
            'lokasi_arsip' => 'required|string|max:150|unique:lokasi_arsip,lokasi_arsip,' . $id,
        ]);

        DB::beginTransaction();
        try {
            $item->update([
                'lokasi_arsip' => $request->lokasi_arsip,
            ]);
            DB::commit();
            return redirect()->route('lokasi_arsip.viewlokasi')->with('success', 'Lokasi arsip berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Lokasi::findOrFail($id);
        $item->delete();
        return redirect()->route('lokasi_arsip.viewlokasi')->with('success', 'Lokasi arsip berhasil dihapus.');
    }
}
