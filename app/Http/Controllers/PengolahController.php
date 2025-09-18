<?php

namespace App\Http\Controllers;

use App\Models\Pengolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengolahController extends Controller
{
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Pengolah::when($katakunci, function ($q, $k) {
                return $q->where('pengolah_arsip', 'like', "%$k%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pengolah_arsip.viewpengolah', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    public function create()
    {
        return view('pengolah_arsip.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengolah_arsip' => 'required|string|max:150|unique:pengolah_arsip,pengolah_arsip',
        ], [
            'pengolah_arsip.required' => 'Nama pengolah wajib diisi.',
            'pengolah_arsip.unique' => 'Nama pengolah sudah ada.',
        ]);

        DB::beginTransaction();
        try {
            Pengolah::create([
                'pengolah_arsip' => $request->pengolah_arsip,
            ]);
            DB::commit();
            return redirect()->route('pengolah_arsip.viewpengolah')->with('success', 'Pengolah arsip berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Pengolah::findOrFail($id);
        return view('pengolah_arsip.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Pengolah::findOrFail($id);

        $request->validate([
            'pengolah_arsip' => 'required|string|max:150|unique:pengolah_arsip,pengolah_arsip,' . $id,
        ]);

        DB::beginTransaction();
        try {
            $item->update([
                'pengolah_arsip' => $request->pengolah_arsip,
            ]);
            DB::commit();
            return redirect()->route('pengolah_arsip.viewpengolah')->with('success', 'Pengolah arsip berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Pengolah::findOrFail($id);
        $item->delete();
        return redirect()->route('pengolah_arsip.viewpengolah')->with('success', 'Pengolah arsip berhasil dihapus.');
    }
}
