<?php

namespace App\Http\Controllers;

use App\Models\Adminlist;
use App\Models\Division;
use App\Models\Role;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminlistController extends Controller
{
    /**
     * Tampilkan daftar admin.
     */
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Adminlist::when($katakunci, function ($query, $katakunci) {
            return $query->where('username', 'like', "%$katakunci%")
                ->orWhere('email', 'like', "%$katakunci%")
                ->orWhere('role', 'like', "%$katakunci%")
                ->orWhere('divisi', 'like', "%$katakunci%")
                ->orWhere('status', 'like', "%$katakunci%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('adminlist.viewadmin', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    /**
     * Form tambah admin baru.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');   // ambil data roles dari tabel roles
        $divisi = Division::pluck('name', 'id'); // ambil data divisi dari tabel divisis

        return view('adminlist.create', compact('roles', 'divisi'));
    }

    /**
     * Simpan admin baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:adminlists,username',
            'email' => 'required|string|email|max:255|unique:adminlists,email',
            'role' => 'required|exists:roles,id',
            'divisi' => 'required|exists:divisis,id',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $path = null;
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            Adminlist::create([
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,      // simpan ID role
                'divisi' => $request->divisi,  // simpan ID divisi
                'profile_picture' => $path,
                'status' => 'aktif',
                'last_login' => null,
                'last_logout' => null,
            ]);

            DB::commit();
            return redirect()->route('adminlist.viewadmin')->with('success', 'Admin berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit admin.
     */
    public function edit($id)
    {
        $admin = Adminlist::findOrFail($id);
        $roles = Role::pluck('name', 'id');
        $divisi = Division::pluck('name', 'id');

        return view('adminlist.edit', compact('admin', 'roles', 'divisi'));
    }

    /**
     * Update data admin.
     */
    public function update(Request $request, $id)
    {
        $admin = Adminlist::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:100|unique:adminlists,username,' . $id,
            'email' => 'required|string|email|max:255|unique:adminlists,email,' . $id,
            'role' => 'required|exists:roles,id',
            'divisi' => 'required|exists:divisis,id',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('profile_picture')) {
                if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
                    Storage::disk('public')->delete($admin->profile_picture);
                }
                $admin->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            $admin->update([
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'divisi' => $request->divisi,
                'profile_picture' => $admin->profile_picture,
            ]);

            DB::commit();
            return redirect()->route('adminlist.viewadmin')->with('success', 'Admin berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus admin.
     */
    public function destroy($id)
    {
        $admin = Adminlist::findOrFail($id);

        if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
            Storage::disk('public')->delete($admin->profile_picture);
        }

        $admin->delete();

        return redirect()->route('adminlist.viewadmin')->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * Ubah status admin.
     */
    public function toggleStatus($id)
    {
        $admin = Adminlist::findOrFail($id);
        $admin->status = $admin->status === 'aktif' ? 'non-aktif' : 'aktif';
        $admin->save();

        return redirect()->route('adminlist.viewadmin')->with('success', 'Status admin diperbarui.');
    }

    /**
     * Update terakhir login.
     */
    public function updateLoginTime($id)
    {
        $admin = Adminlist::findOrFail($id);
        $admin->last_login = now();
        $admin->save();
    }

    /**
     * Update terakhir logout.
     */
    public function updateLogoutTime($id)
    {
        $admin = Adminlist::findOrFail($id);
        $admin->last_logout = now();
        $admin->save();
    }
}