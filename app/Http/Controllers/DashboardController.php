<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use Carbon\Carbon;
use DB;
use Auth;

class DashboardController extends Controller
{
    public function grafik()
    {
        $tahun = Carbon::now()->year;
        $user = Auth::user();

        // Jika role admin -> semua data, jika user -> filter by user_id
        $query = Dashboard::select(
                    DB::raw('MONTH(created_at) as bulan'),
                    'jenis',
                    DB::raw('count(*) as total')
                )
                ->whereYear('created_at', $tahun);

        if ($user->userlist->role->name === 'user') {
            $query->where('user_id', $user->id);
        }

        $data = $query->groupBy('bulan', 'jenis')->get();

        // Format data untuk chart
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[$i] = [
                'bulan' => Carbon::create()->month($i)->format('F'),
                'file' => 0,
                'foto' => 0,
                'video' => 0,
            ];
        }

        foreach ($data as $item) {
            $chartData[$item->bulan][$item->jenis] = $item->total;
        }

        return view('dashboard.viewdashboard', [
            'chartData' => $chartData,
            'tahun' => $tahun,
            'role' => $user->userlist->role->name,
        ]);
    }
}
