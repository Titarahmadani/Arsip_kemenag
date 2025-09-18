<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlasifikasiController extends Controller
{
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Klasifikasi::when($katakunci, function ($q, $k) {
                return $q->where('kode_klasifikasi', 'like', "%$k%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kode_klasifikasi.viewklasifikasi', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    public function create()
    {
        return view('kode_klasifikasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_klasifikasi' => 'required|string|max:100|unique:kode_klasifikasi,kode_klasifikasi',
        ], [
            'kode_klasifikasi.required' => 'Kode klasifikasi wajib diisi.',
            'kode_klasifikasi.unique' => 'Kode klasifikasi sudah ada.',
        ]);

        DB::beginTransaction();
        try {
            Klasifikasi::create([
                'kode_klasifikasi' => $request->kode_klasifikasi,
            ]);
            DB::commit();
            return redirect()->route('kode_klasifikasi.index')->with('success', 'Kode klasifikasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Klasifikasi::findOrFail($id);
        return view('kode_klasifikasi.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Klasifikasi::findOrFail($id);

        $request->validate([
            'kode_klasifikasi' => 'required|string|max:100|unique:kode_klasifikasi,kode_klasifikasi,' . $id,
        ]);

        DB::beginTransaction();
        try {
            $item->update([
                'kode_klasifikasi' => $request->kode_klasifikasi,
            ]);
            DB::commit();
            return redirect()->route('kode_klasifikasi.viewklasifikasi')->with('success', 'Kode klasifikasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Klasifikasi::findOrFail($id);
        $item->delete();
        return redirect()->route('kode_klasifikasi.viewklasifikasi')->with('success', 'Kode klasifikasi berhasil dihapus.');
    }
}
