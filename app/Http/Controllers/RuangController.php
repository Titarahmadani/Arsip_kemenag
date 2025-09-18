<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuangController extends Controller
{
    /**
     * Tampilkan daftar ruang.
     */
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Ruang::when($katakunci, function ($query, $katakunci) {
            return $query->where('nama_ruangan', 'like', "%$katakunci%")
                         ->orWhere('keterangan', 'like', "%$katakunci%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('ruang.viewruang', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    /**
     * Form tambah ruang.
     */
    public function create()
    {
        return view('ruang.create');
    }

    /**
     * Simpan ruang baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100|unique:ruang,nama_ruangan',
            'keterangan'   => 'nullable|string|max:255',
        ], [
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.unique'   => 'Nama ruangan sudah ada.',
        ]);

        DB::beginTransaction();
        try {
            Ruang::create([
                'nama_ruangan' => $request->nama_ruangan,
                'keterangan'   => $request->keterangan,
            ]);

            DB::commit();
            return redirect()->route('ruang.viewruang')->with('success', 'Ruangan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Form edit ruang.
     */
    public function edit($id)
    {
        $ruang = Ruang::findOrFail($id);
        return view('ruang.edit', compact('ruang'));
    }

    /**
     * Update ruang.
     */
    public function update(Request $request, $id)
    {
        $ruang = Ruang::findOrFail($id);

        $request->validate([
            'nama_ruangan' => 'required|string|max:100|unique:ruang,nama_ruangan,'.$ruang->id,
            'keterangan'   => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $ruang->update([
                'nama_ruangan' => $request->nama_ruangan,
                'keterangan'   => $request->keterangan,
            ]);

            DB::commit();
            return redirect()->route('ruang.viewruang')->with('success', 'Ruangan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Hapus ruang.
     */
    public function destroy($id)
    {
        $ruang = Ruang::findOrFail($id);
        $ruang->delete();

        return redirect()->route('ruang.viewruang')->with('success', 'Ruangan berhasil dihapus.');
    }
}
