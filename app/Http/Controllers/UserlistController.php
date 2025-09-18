<?php

namespace App\Http\Controllers;

use App\Models\Userlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserlistController extends Controller
{
    /**
     * Tampilkan daftar user.
     */
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        $data = Userlist::when($katakunci, function ($query, $katakunci) {
            return $query->where('username', 'like', "%$katakunci%")
                ->orWhere('email', 'like', "%$katakunci%")
                ->orWhere('role', 'like', "%$katakunci%")
                ->orWhere('divisi', 'like', "%$katakunci%")
                ->orWhere('status', 'like', "%$katakunci%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('userlist.viewuser', ['Data' => $data, 'katakunci' => $katakunci]);
    }

    /**
     * Form tambah user baru.
     */
    public function create()
    {
        $roles = ['admin', 'user'];
        $divisi = ['Keuangan', 'SDM', 'IT', 'Umum'];

        return view('userlist.create', compact('roles', 'divisi'));
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:userlists,username',
            'email' => 'required|string|email|max:255|unique:userlists,email',
            'role' => 'required|string|max:50',
            'divisi' => 'required|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $path = null;
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            Userlist::create([
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'divisi' => $request->divisi,
                'profile_picture' => $path,
                'status' => 'aktif', // default saat buat user
            ]);

            DB::commit();
            return redirect()->route('userlist.viewuser')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit user.
     */
    public function edit($id)
    {
        $user = Userlist::findOrFail($id);
        $roles = ['admin', 'user'];
        $divisi = ['Keuangan', 'SDM', 'IT', 'Umum'];

        return view('userlist.edit', compact('user', 'roles', 'divisi'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, $id)
    {
        $user = Userlist::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:100|unique:userlists,username,' . $id,
            'email' => 'required|string|email|max:255|unique:userlists,email,' . $id,
            'role' => 'required|string|max:50',
            'divisi' => 'required|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('profile_picture')) {
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'divisi' => $request->divisi,
                'profile_picture' => $user->profile_picture,
            ]);

            DB::commit();
            return redirect()->route('userlist.viewuser')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus user.
     */
    public function destroy($id)
    {
        $user = Userlist::findOrFail($id);

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return redirect()->route('userlist.viewuser')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Ubah status user (aktif <-> non-aktif).
     */
    public function toggleStatus($id)
    {
        $user = Userlist::findOrFail($id);
        $user->status = $user->status === 'aktif' ? 'non-aktif' : 'aktif';
        $user->save();

        return redirect()->route('userlist.viewuser')->with('success', 'Status user diperbarui.');
    }
}
