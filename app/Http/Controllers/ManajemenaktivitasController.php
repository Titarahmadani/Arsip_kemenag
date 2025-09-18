<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManajemenaktivitasController extends Controller
{
    /**
     * Tampilkan daftar user dengan tanggal pembuatan, username, dan aktivitas
     */
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');

        // Ambil data user dengan filter pencarian
        $data = User::when($katakunci, function ($query, $katakunci) {
            return $query->where('username', 'like', "%$katakunci%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('userlist.viewactivity', [
            'Data' => $data,
            'katakunci' => $katakunci
        ]);
    }
}
