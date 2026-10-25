<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sidang;
use App\Models\TugasAkhir;
use App\Models\Dosen;

class SidangController extends Controller
{
    /**
     * Mahasiswa mengajukan pendaftaran Sidang TA
     */
    public function apply($tugas_akhir_id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($tugas_akhir_id);

        if (Auth::user()->mahasiswa->id !== $tugasAkhir->mahasiswa_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($tugasAkhir->status !== 'disetujui') {
            return back()->with('error', 'Tugas Akhir Anda belum disetujui atau sudah masuk ke tahap sidang/lulus.');
        }

        if (empty($tugasAkhir->file_laporan)) {
            return back()->with('error', 'Anda harus mengunggah file laporan akhir (PDF) sebelum mendaftar sidang.');
        }

        // Cek apakah mahasiswa minimal memiliki 3 logbook yang sudah divalidasi
        $validatedLogbooks = $tugasAkhir->logbooks()->where('status_validasi', true)->count();
        if ($validatedLogbooks < 3) {
            return back()->with('error', 'Anda harus memiliki minimal 3 logbook bimbingan yang tervalidasi dosen pembimbing.');
        }

        // Update status TA menjadi 'sidang'
        $tugasAkhir->update([
            'status' => 'sidang'
        ]);

        return back()->with('success', 'Pendaftaran sidang berhasil diajukan. Admin akan segera menjadwalkan.');
    }

    /**
     * Admin menjadwalkan Sidang (Plotting ruang, waktu, dan penguji)
     */
    public function schedule(Request $request)
    {
        $request->validate([
            'tugas_akhir_id' => 'required|exists:tugas_akhirs,id',
            'dosen_penguji_id' => 'required|exists:dosens,id',
            'tanggal_sidang' => 'required|date|after:today',
            'ruangan' => 'required|string|max:100',
        ], [
            'tanggal_sidang.after' => 'Tanggal sidang harus di masa depan.',
            'dosen_penguji_id.required' => 'Dosen penguji wajib dipilih.',
        ]);

        $tugasAkhir = TugasAkhir::findOrFail($request->tugas_akhir_id);
        
        // Dosen pembimbing tidak boleh merangkap dosen penguji demi keadilan akademik
        if ($tugasAkhir->dosen_id == $request->dosen_penguji_id) {
            return back()->withInput()->withErrors(['dosen_penguji_id' => 'Dosen Pembimbing tidak boleh ditunjuk sebagai Dosen Penguji.']);
        }

        // Simpan atau update jadwal sidang
        Sidang::updateOrCreate(
            ['tugas_akhir_id' => $request->tugas_akhir_id],
            [
                'dosen_penguji_id' => $request->dosen_penguji_id,
                'tanggal_sidang' => $request->tanggal_sidang,
                'ruangan' => $request->ruangan,
                'status_kelulusan' => 'menunggu',
                'nilai_angka' => null,
                'nilai_huruf' => null,
                'catatan' => null
            ]
        );

        return back()->with('success', 'Jadwal sidang berhasil dipublikasikan.');
    }

    /**
     * Dosen Penguji menginput nilai & status kelulusan sidang
     */
    public function score(Request $request, $id)
    {
        $sidang = Sidang::findOrFail($id);
        $tugasAkhir = $sidang->tugasAkhir;

        // Otorisasi: hanya dosen penguji yang bersangkutan
        if (Auth::user()->dosen->id !== $sidang->dosen_penguji_id) {
            abort(403, 'Unauthorized action. Anda bukan dosen penguji untuk sidang ini.');
        }

        $request->validate([
            'nilai_angka' => 'required|integer|between:0,100',
            'status_kelulusan' => 'required|in:lulus,tidak_lulus',
            'catatan' => 'nullable|string',
        ], [
            'nilai_angka.required' => 'Nilai angka wajib diisi.',
            'nilai_angka.between' => 'Nilai angka harus berada di antara 0 sampai 100.',
        ]);

        // Hitung nilai huruf secara otomatis
        $nilaiAngka = $request->nilai_angka;
        if ($nilaiAngka >= 85) {
            $nilaiHuruf = 'A';
        } elseif ($nilaiAngka >= 75) {
            $nilaiHuruf = 'B';
        } elseif ($nilaiAngka >= 60) {
            $nilaiHuruf = 'C';
        } elseif ($nilaiAngka >= 50) {
            $nilaiHuruf = 'D';
        } else {
            $nilaiHuruf = 'E';
        }

        $sidang->update([
            'nilai_angka' => $nilaiAngka,
            'nilai_huruf' => $nilaiHuruf,
            'status_kelulusan' => $request->status_kelulusan,
            'catatan' => $request->catatan,
        ]);

        // Update status Tugas Akhir mahasiswa
        if ($request->status_kelulusan === 'lulus') {
            $tugasAkhir->update(['status' => 'lulus']);
        } else {
            // Jika tidak lulus, kembalikan ke status disetujui (dapat bimbingan lagi & sidang ulang)
            $tugasAkhir->update(['status' => 'disetujui']);
        }

        return back()->with('success', 'Nilai sidang berhasil diunggah.');
    }

    /**
     * Cetak Berita Acara Kelulusan Sidang (PDF/Print View)
     */
    public function printBeritaAcara($id)
    {
        $sidang = Sidang::with(['tugasAkhir.mahasiswa', 'tugasAkhir.dosen', 'dosenPenguji'])->findOrFail($id);

        $user = Auth::user();
        if ($user->isMahasiswa() && $user->mahasiswa->id !== $sidang->tugasAkhir->mahasiswa_id) {
            abort(403);
        }
        if ($user->isDosen() && $user->dosen->id !== $sidang->dosen_penguji_id && $user->dosen->id !== $sidang->tugasAkhir->dosen_id) {
            abort(403);
        }

        return view('dashboard.print-sidang', compact('sidang'));
    }
}
