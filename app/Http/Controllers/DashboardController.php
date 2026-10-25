<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\Logbook;
use App\Models\Sidang;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Berdasarkan Role
     */
    public function index()
    {
        $user = Auth::user();
        $announcements = Pengumuman::with('user')->latest()->take(5)->get();

        if ($user->isAdmin()) {
            // Data untuk Admin
            $totalMahasiswa = Mahasiswa::count();
            $totalDosen = Dosen::count();
            $totalTugasAkhir = TugasAkhir::count();
            $totalSidang = Sidang::count();

            // Statistik Status TA
            $taStatuses = TugasAkhir::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Default values
            $statuses = ['menunggu', 'disetujui', 'ditolak', 'sidang', 'lulus'];
            $chartData = [];
            foreach ($statuses as $status) {
                $chartData[$status] = $taStatuses[$status] ?? 0;
            }

            $recentTugasAkhir = TugasAkhir::with(['mahasiswa', 'dosen'])->latest()->take(5)->get();
            $recentSidang = Sidang::with(['tugasAkhir.mahasiswa', 'dosenPenguji'])->latest()->take(5)->get();

            return view('dashboard.admin', compact(
                'totalMahasiswa',
                'totalDosen',
                'totalTugasAkhir',
                'totalSidang',
                'chartData',
                'recentTugasAkhir',
                'recentSidang',
                'announcements'
            ));
        } 
        
        if ($user->isDosen()) {
            // Data untuk Dosen
            $dosen = $user->dosen;
            
            if (!$dosen) {
                return redirect()->route('login')->withErrors(['email' => 'Profil dosen tidak ditemukan. Hubungi admin.']);
            }

            $totalBimbingan = TugasAkhir::where('dosen_id', $dosen->id)->count();
            $bimbinganAktif = TugasAkhir::where('dosen_id', $dosen->id)
                ->whereIn('status', ['disetujui', 'sidang'])
                ->count();
            
            $logbookPending = Logbook::whereHas('tugasAkhir', function ($q) use ($dosen) {
                $q->where('dosen_id', $dosen->id);
            })->where('status_validasi', false)->count();

            $totalMenguji = Sidang::where('dosen_penguji_id', $dosen->id)->count();

            // Grafik Mahasiswa Bimbingan
            $taStatuses = TugasAkhir::where('dosen_id', $dosen->id)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $statuses = ['menunggu', 'disetujui', 'ditolak', 'sidang', 'lulus'];
            $chartData = [];
            foreach ($statuses as $status) {
                $chartData[$status] = $taStatuses[$status] ?? 0;
            }

            $mahasiswaBimbingan = TugasAkhir::where('dosen_id', $dosen->id)
                ->with('mahasiswa')
                ->latest()
                ->take(5)
                ->get();

            $jadwalSidang = Sidang::where('dosen_penguji_id', $dosen->id)
                ->with(['tugasAkhir.mahasiswa'])
                ->orderBy('tanggal_sidang', 'asc')
                ->get();

            return view('dashboard.dosen', compact(
                'dosen',
                'totalBimbingan',
                'bimbinganAktif',
                'logbookPending',
                'totalMenguji',
                'chartData',
                'mahasiswaBimbingan',
                'jadwalSidang',
                'announcements'
            ));
        } 
        
        if ($user->isMahasiswa()) {
            // Data untuk Mahasiswa
            $mahasiswa = $user->mahasiswa;

            if (!$mahasiswa) {
                return redirect()->route('login')->withErrors(['email' => 'Profil mahasiswa tidak ditemukan.']);
            }

            $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
                ->with('dosen')
                ->first();

            $logbooks = collect();
            $sidang = null;
            $progressPercent = 0;

            if ($tugasAkhir) {
                $logbooks = Logbook::where('tugas_akhir_id', $tugasAkhir->id)
                    ->orderBy('tanggal_bimbingan', 'desc')
                    ->get();
                
                $sidang = Sidang::where('tugas_akhir_id', $tugasAkhir->id)
                    ->with('dosenPenguji')
                    ->first();

                // Hitung persen progress berdasarkan status TA & jumlah logbook tervalidasi
                $validatedLogbooksCount = $logbooks->where('status_validasi', true)->count();
                
                if ($tugasAkhir->status === 'menunggu') {
                    $progressPercent = 10;
                } elseif ($tugasAkhir->status === 'disetujui') {
                    // Maksimal 70% dari bimbingan logbook (tiap logbook valid tambah 10%, max 60% + base 10%)
                    $progressPercent = min(70, 10 + ($validatedLogbooksCount * 10));
                } elseif ($tugasAkhir->status === 'sidang') {
                    $progressPercent = 85;
                } elseif ($tugasAkhir->status === 'lulus') {
                    $progressPercent = 100;
                } elseif ($tugasAkhir->status === 'ditolak') {
                    $progressPercent = 0;
                }
            }

            // List dosen untuk dropdown pengajuan TA
            $dosens = Dosen::all();

            return view('dashboard.mahasiswa', compact(
                'mahasiswa',
                'tugasAkhir',
                'logbooks',
                'sidang',
                'progressPercent',
                'dosens',
                'announcements'
            ));
        }

        abort(403, 'Role tidak valid.');
    }

    /**
     * Tampilkan Halaman Logbook Mahasiswa
     */
    public function logbookIndex()
    {
        $user = Auth::user();
        if (!$user->isMahasiswa()) {
            abort(403, 'Akses ditolak.');
        }

        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            abort(404, 'Profil mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->with('dosen')
            ->first();

        if (!$tugasAkhir || !in_array($tugasAkhir->status, ['disetujui', 'sidang', 'lulus'])) {
            return redirect()->route('dashboard')->with('error', 'Silakan ajukan judul tugas akhir terlebih dahulu dan tunggu persetujuan.');
        }

        $logbooks = Logbook::where('tugas_akhir_id', $tugasAkhir->id)
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        return view('dashboard.mahasiswa.logbook', compact('mahasiswa', 'tugasAkhir', 'logbooks'));
    }

    /**
     * Tampilkan Halaman Laporan Akhir & Sidang Mahasiswa
     */
    public function laporanIndex()
    {
        $user = Auth::user();
        if (!$user->isMahasiswa()) {
            abort(403, 'Akses ditolak.');
        }

        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            abort(404, 'Profil mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->with('dosen')
            ->first();

        if (!$tugasAkhir || !in_array($tugasAkhir->status, ['disetujui', 'sidang', 'lulus'])) {
            return redirect()->route('dashboard')->with('error', 'Silakan ajukan judul tugas akhir terlebih dahulu dan tunggu persetujuan.');
        }

        $logbooks = Logbook::where('tugas_akhir_id', $tugasAkhir->id)
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        $sidang = Sidang::where('tugas_akhir_id', $tugasAkhir->id)
            ->with('dosenPenguji')
            ->first();

        return view('dashboard.mahasiswa.laporan', compact('mahasiswa', 'tugasAkhir', 'logbooks', 'sidang'));
    }

    /**
     * Tampilkan Halaman Riwayat Kegiatan Mahasiswa
     */
    public function riwayatIndex()
    {
        $user = Auth::user();
        if (!$user->isMahasiswa()) {
            abort(403, 'Akses ditolak.');
        }

        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            abort(404, 'Profil mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->with('dosen')
            ->first();

        $logbooks = collect();
        $sidang = null;

        if ($tugasAkhir) {
            $logbooks = Logbook::where('tugas_akhir_id', $tugasAkhir->id)
                ->orderBy('tanggal_bimbingan', 'desc')
                ->get();

            $sidang = Sidang::where('tugas_akhir_id', $tugasAkhir->id)
                ->with('dosenPenguji')
                ->first();
        }

        return view('dashboard.mahasiswa.riwayat', compact('mahasiswa', 'tugasAkhir', 'logbooks', 'sidang'));
    }
}
