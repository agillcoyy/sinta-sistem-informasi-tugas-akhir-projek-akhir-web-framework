<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;

class AdminController extends Controller
{
    /**
     * Tampilkan Halaman Manajemen Mahasiswa
     */
    public function studentIndex()
    {
        $mahasiswas = Mahasiswa::with('user')->orderBy('nama_lengkap', 'asc')->get();
        return view('admin.mahasiswa', compact('mahasiswas'));
    }

    /**
     * Tambah Mahasiswa Baru
     */
    public function studentStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim|max:20',
            'email' => 'required|string|email|unique:users,email|max:255',
            'program_studi' => 'required|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        \DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'nama_lengkap' => $request->name,
                'program_studi' => $request->program_studi,
            ]);

            \DB::commit();
            return back()->with('success', 'Mahasiswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal menambahkan mahasiswa.');
        }
    }

    /**
     * Update Data Mahasiswa
     */
    public function studentUpdate(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $user = $mahasiswa->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id . '|max:20',
            'email' => 'required|string|email|unique:users,email,' . $user->id . '|max:255',
            'program_studi' => 'required|string|max:100',
            'password' => 'nullable|string|min:6',
        ]);

        \DB::beginTransaction();
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $mahasiswa->update([
                'nim' => $request->nim,
                'nama_lengkap' => $request->name,
                'program_studi' => $request->program_studi,
            ]);

            \DB::commit();
            return back()->with('success', 'Data mahasiswa berhasil diperbarui.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data mahasiswa.');
        }
    }

    /**
     * Hapus Mahasiswa
     */
    public function studentDestroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Hapus user (akan cascade menghapus mahasiswa)
        $user = $mahasiswa->user;
        $user->delete();

        return back()->with('success', 'Mahasiswa berhasil dihapus.');
    }

    /**
     * Tampilkan Halaman Manajemen Dosen
     */
    public function dosenIndex()
    {
        $dosens = Dosen::with('user')->orderBy('nama_lengkap', 'asc')->get();
        return view('admin.dosen', compact('dosens'));
    }

    /**
     * Tambah Dosen Baru
     */
    public function dosenStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:dosens,nip|max:20',
            'email' => 'required|string|email|unique:users,email|max:255',
            'bidang_keahlian' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        \DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'dosen',
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama_lengkap' => $request->name,
                'bidang_keahlian' => $request->bidang_keahlian,
            ]);

            \DB::commit();
            return back()->with('success', 'Dosen berhasil ditambahkan.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal menambahkan dosen.');
        }
    }

    /**
     * Update Data Dosen
     */
    public function dosenUpdate(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $user = $dosen->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:dosens,nip,' . $dosen->id . '|max:20',
            'email' => 'required|string|email|unique:users,email,' . $user->id . '|max:255',
            'bidang_keahlian' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        \DB::beginTransaction();
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $dosen->update([
                'nip' => $request->nip,
                'nama_lengkap' => $request->name,
                'bidang_keahlian' => $request->bidang_keahlian,
            ]);

            \DB::commit();
            return back()->with('success', 'Data dosen berhasil diperbarui.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data dosen.');
        }
    }

    /**
     * Hapus Dosen
     */
    public function dosenDestroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        
        $user = $dosen->user;
        $user->delete();

        return back()->with('success', 'Dosen berhasil dihapus.');
    }

    /**
     * Ekspor Data Rekapitulasi TA ke Excel/CSV
     */
    public function exportCSV()
    {
        $tugasAkhirs = TugasAkhir::with(['mahasiswa', 'dosen', 'sidang'])->get();

        $filename = 'rekap_tugas_akhir_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($tugasAkhirs) {
            $file = fopen('php://output', 'w');
            
            // Output UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($file, array('No', 'NIM', 'Nama Mahasiswa', 'Program Studi', 'Judul Tugas Akhir', 'Dosen Pembimbing', 'Status TA', 'Nilai Angka Sidang', 'Nilai Huruf', 'Status Kelulusan'));

            foreach ($tugasAkhirs as $index => $ta) {
                $statusKelulusan = '-';
                $nilaiAngka = '-';
                $nilaiHuruf = '-';

                if ($ta->sidang) {
                    $nilaiAngka = $ta->sidang->nilai_angka ?? '-';
                    $nilaiHuruf = $ta->sidang->nilai_huruf ?? '-';
                    $statusKelulusan = strtoupper($ta->sidang->status_kelulusan);
                }

                fputcsv($file, array(
                    $index + 1,
                    $ta->mahasiswa->nim,
                    $ta->mahasiswa->nama_lengkap,
                    $ta->mahasiswa->program_studi,
                    $ta->judul,
                    $ta->dosen->nama_lengkap,
                    strtoupper($ta->status),
                    $nilaiAngka,
                    $nilaiHuruf,
                    $statusKelulusan
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
