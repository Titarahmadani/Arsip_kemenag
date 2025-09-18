<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    /**
     * Tampilkan daftar foto dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Foto::when($katakunci, function ($query, $katakunci) {
            return $query->where('nomor_arsip', 'like', "%$katakunci%")
                ->orWhere('nama_arsip', 'like', "%$katakunci%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // gunakan pagination untuk performa

        return view('foto.viewfoto', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    /**
     * Form tambah foto baru dengan nomor arsip otomatis.
     */
    public function create()
    {
        // Cari nomor arsip terakhir
        $latest = Foto::orderBy('created_at', 'desc')->first();
        $nextNumber = 'D01';
        if ($latest) {
            $lastNumber = (int) filter_var($latest->nomor_arsip, FILTER_SANITIZE_NUMBER_INT);
            $nextNumber = 'D' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        }

        // Dropdown untuk pilihan tetap
        $unitPengolahan = ['Pendidikan', 'Keuangan', 'Umum'];
        $penciptaArsip = ['Budi', 'Siti', 'Andi'];
        $lokasiArsip = ['Ruang_A', 'Ruang_B', 'Ruang_C'];
        $kodeKlasifikasi = ['Pen.01', 'Adm.02', 'Keu.03'];

        return view('foto.tambahfoto', compact(
            'nextNumber',
            'unitPengolahan',
            'penciptaArsip',
            'lokasiArsip',
            'kodeKlasifikasi'
        ));
    }

    /**
     * Simpan foto baru ke database.
     */
    public function store(Request $request)
    {
        Session::flash('nama_arsip', $request->nama_arsip);

        $request->validate([
            'nomor_arsip' => 'required|string|max:50|unique:foto,nomor_arsip',
            'nama_arsip' => 'required|string|max:255',
            'kode_klasifikasi' => 'required|string|max:50',
            'pencipta_arsip' => 'required|string|max:100',
            'unit_pengolahan' => 'required|string|max:100',
            'lokasi_arsip' => 'required|string|max:100',
            'nomor_box' => 'required|string|max:10',
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,jpg,png|max:2048',
            'keterangan' => 'nullable|string',
        ], [
            'nomor_arsip.unique' => 'Nomor arsip sudah terdaftar.',
            'file.mimes' => 'Format file harus PDF, DOC, DOCX, XLSX, XLS, JPG, atau PNG.',
        ]);

        DB::beginTransaction();
        try {
            // Simpan file ke folder public/storage/arsip
            $path = $request->file('file')->store('arsip', 'public');

            foto::create([
                'nomor_arsip' => $request->nomor_arsip,
                'nama_arsip' => $request->nama_arsip,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'pencipta_arsip' => $request->pencipta_arsip,
                'unit_pengolahan' => $request->unit_pengolahan,
                'lokasi_arsip' => $request->lokasi_arsip,
                'nomor_box' => $request->nomor_box,
                'keterangan' => $request->keterangan,
                'file' => $path,
                'user_id' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('foto.viewfoto')->with('success', 'Foto berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit foto.
     */
    public function edit($id)
    {
        $foto = foto::findOrFail($id);

        $unitPengolahan = ['Pendidikan', 'Keuangan', 'Umum'];
        $penciptaArsip = ['Budi', 'Siti', 'Andi'];
        $lokasiArsip = ['Ruang_A', 'Ruang_B', 'Ruang_C'];
        $kodeKlasifikasi = ['Pen.01', 'Adm.02', 'Keu.03'];

        return view('foto.editfoto', compact(
            'foto',
            'unitPengolahan',
            'penciptaArsip',
            'lokasiArsip',
            'kodeKlasifikasi'
        ));
    }

    /**
     * Update foto.
     */
    public function update(Request $request, $id)
    {
        $foto = foto::findOrFail($id);

        $request->validate([
            'nama_arsip' => 'required|string|max:255',
            'kode_klasifikasi' => 'required|string|max:50',
            'pencipta_arsip' => 'required|string|max:100',
            'unit_pengolahan' => 'required|string|max:100',
            'lokasi_arsip' => 'required|string|max:100',
            'nomor_box' => 'required|string|max:10',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($foto->file && Storage::disk('public')->exists($foto->file)) {
                    Storage::disk('public')->delete($foto->file);
                }
                $foto->file = $request->file('file')->store('arsip', 'public');
            }

            $foto->update([
                'nama_arsip' => $request->nama_arsip,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'pencipta_arsip' => $request->pencipta_arsip,
                'unit_pengolahan' => $request->unit_pengolahan,
                'lokasi_arsip' => $request->lokasi_arsip,
                'nomor_box' => $request->nomor_box,
                'keterangan' => $request->keterangan,
                'file' => $foto->file, // tetap simpan path terbaru
            ]);

            DB::commit();
            return redirect()->route('foto.viewfoto')->with('success', 'Foto berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus foto.
     */
    public function destroy($id)
    {
        $foto = foto::findOrFail($id);

        // Hapus file fisik
        if ($foto->file && Storage::disk('public')->exists($foto->file)) {
            Storage::disk('public')->delete($foto->file);
        }

        $foto->delete();

        return redirect()->route('foto.viewfoto')->with('success', 'Foto berhasil dihapus.');
    }

    public function restore($id)
    {
        // Cari foto yang sudah di-soft-delete
        $foto = foto::withTrashed()->findOrFail($id);

        // Pulihkan foto
        $foto->restore();

        // Jika perlu memindahkan file dari folder 'trash' ke 'arsip', contoh:
        // Storage::move('trash/'.$foto->file, 'arsip/'.$foto->file);

        return redirect()->route('foto.viewrestore')->with('success', 'Foto berhasil dipulihkan.');
    }
}