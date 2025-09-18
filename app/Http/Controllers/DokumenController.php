<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Tampilkan daftar dokumen dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Dokumen::when($katakunci, function ($query, $katakunci) {
            return $query->where('nomor_arsip', 'like', "%$katakunci%")
                ->orWhere('nama_arsip', 'like', "%$katakunci%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // gunakan pagination untuk performa

        return view('dokumen.viewdokumen', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    /**
     * Form tambah dokumen baru dengan nomor arsip otomatis.
     */
    public function create()
    {
        // Cari nomor arsip terakhir
        $latest = Dokumen::orderBy('created_at', 'desc')->first();
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

        return view('dokumen.tambahdokumen', compact(
            'nextNumber',
            'unitPengolahan',
            'penciptaArsip',
            'lokasiArsip',
            'kodeKlasifikasi'
        ));
    }

    /**
     * Simpan dokumen baru ke database.
     */
    public function store(Request $request)
    {
        Session::flash('nama_arsip', $request->nama_arsip);

        $request->validate([
            'nomor_arsip' => 'required|string|max:50|unique:dokumen,nomor_arsip',
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

            Dokumen::create([
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
            return redirect()->route('dokumen.viewdokumen')->with('success', 'Dokumen berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit dokumen.
     */
    public function edit($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $unitPengolahan = ['Pendidikan', 'Keuangan', 'Umum'];
        $penciptaArsip = ['Budi', 'Siti', 'Andi'];
        $lokasiArsip = ['Ruang_A', 'Ruang_B', 'Ruang_C'];
        $kodeKlasifikasi = ['Pen.01', 'Adm.02', 'Keu.03'];

        return view('dokumen.editdokumen', compact(
            'dokumen',
            'unitPengolahan',
            'penciptaArsip',
            'lokasiArsip',
            'kodeKlasifikasi'
        ));
    }

    /**
     * Update dokumen.
     */
    public function update(Request $request, $id)
    {
        $dokumen = Dokumen::findOrFail($id);

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
                if ($dokumen->file && Storage::disk('public')->exists($dokumen->file)) {
                    Storage::disk('public')->delete($dokumen->file);
                }
                $dokumen->file = $request->file('file')->store('arsip', 'public');
            }

            $dokumen->update([
                'nama_arsip' => $request->nama_arsip,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'pencipta_arsip' => $request->pencipta_arsip,
                'unit_pengolahan' => $request->unit_pengolahan,
                'lokasi_arsip' => $request->lokasi_arsip,
                'nomor_box' => $request->nomor_box,
                'keterangan' => $request->keterangan,
                'file' => $dokumen->file, // tetap simpan path terbaru
            ]);

            DB::commit();
            return redirect()->route('dokumen.viewdokumen')->with('success', 'Dokumen berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus dokumen.
     */
    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        // Hapus file fisik
        if ($dokumen->file && Storage::disk('public')->exists($dokumen->file)) {
            Storage::disk('public')->delete($dokumen->file);
        }

        $dokumen->delete();

        return redirect()->route('dokumen.viewdokumen')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function restore($id)
    {
        // Cari dokumen yang sudah di-soft-delete
        $dokumen = Dokumen::withTrashed()->findOrFail($id);

        // Pulihkan dokumen
        $dokumen->restore();

        // Jika perlu memindahkan file dari folder 'trash' ke 'arsip', contoh:
        // Storage::move('trash/'.$dokumen->file, 'arsip/'.$dokumen->file);

        return redirect()->route('dokumen.viewrestore')->with('success', 'Dokumen berhasil dipulihkan.');
    }
}