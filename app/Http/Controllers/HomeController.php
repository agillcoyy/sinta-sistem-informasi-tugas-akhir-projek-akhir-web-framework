<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TugasAkhir;
use App\Models\Pengumuman;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class HomeController extends Controller
{
    /**
     * Halaman Dashboard Umum (Public Landing Page)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = TugasAkhir::with(['mahasiswa', 'dosen'])
            ->whereIn('status', ['disetujui', 'sidang', 'lulus']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhereHas('mahasiswa', function($m) use ($search) {
                      $m->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('nim', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('dosen', function($d) use ($search) {
                      $d->where('nama_lengkap', 'like', '%' . $search . '%');
                  });
            });
        }

        $tugasAkhirs = $query->latest()->paginate(10);
        $announcements = Pengumuman::with('user')->latest()->take(5)->get();

        // Statistik Dashboard Umum
        $stats = [
            'mahasiswa' => Mahasiswa::count(),
            'dosen' => Dosen::count(),
            'ta' => TugasAkhir::count(),
            'lulus' => TugasAkhir::where('status', 'lulus')->count(),
        ];

        return view('home', compact('tugasAkhirs', 'announcements', 'stats', 'search'));
    }
}
