<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TugasAkhir;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class TugasAkhirController extends Controller
{
    /**
     * Tampilkan Halaman Pengajuan Tugas Akhir
     */
    public function create()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('dashboard')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->with('dosen')
            ->first();

        // Jika judul sudah disetujui, sedang sidang, atau lulus, tidak boleh mengajukan lagi
        if ($tugasAkhir && in_array($tugasAkhir->status, ['disetujui', 'sidang', 'lulus'])) {
            return redirect()->route('dashboard')->with('error', 'Judul Tugas Akhir Anda sudah disetujui.');
        }

        $dosens = Dosen::all();

        return view('mahasiswa.pengajuan', compact('mahasiswa', 'tugasAkhir', 'dosens'));
    }

    /**
     * Mahasiswa mengajukan Tugas Akhir baru
     */
    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // Cek jika mahasiswa sudah memiliki Tugas Akhir
        $existingTA = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();
        if ($existingTA) {
            return back()->with('error', 'Anda sudah mengajukan Tugas Akhir sebelumnya.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'dosen_id' => 'required|exists:dosens,id',
        ], [
            'judul.required' => 'Judul Tugas Akhir wajib diisi.',
            'dosen_id.required' => 'Dosen Pembimbing wajib dipilih.',
            'dosen_id.exists' => 'Dosen Pembimbing tidak valid.',
        ]);

        TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $request->dosen_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'menunggu',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengajuan Tugas Akhir berhasil dikirim. Menunggu persetujuan Dosen/Admin.');
    }

    /**
     * Mahasiswa mengupdate judul Tugas Akhir (jika belum disetujui atau jika ditolak)
     */
    public function update(Request $request, $id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);

        // Otorisasi: hanya pemilik TA
        if (Auth::user()->mahasiswa->id !== $tugasAkhir->mahasiswa_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($tugasAkhir->status !== 'menunggu' && $tugasAkhir->status !== 'ditolak') {
            return back()->with('error', 'Judul Tugas Akhir sudah disetujui dan tidak dapat diubah.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'dosen_id' => 'required|exists:dosens,id',
        ]);

        $tugasAkhir->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'dosen_id' => $request->dosen_id,
            'status' => 'menunggu', // Kembalikan ke status menunggu
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengajuan Tugas Akhir berhasil diperbarui.');
    }

    /**
     * Admin/Dosen menyetujui pengajuan judul Tugas Akhir
     */
    public function approve(Request $request, $id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);

        // Hanya Admin atau Dosen pembimbing bersangkutan yang dapat memproses
        if (!Auth::user()->isAdmin() && Auth::user()->dosen->id !== $tugasAkhir->dosen_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|in:setujui,tolak',
        ]);

        $status = $request->action === 'setujui' ? 'disetujui' : 'ditolak';
        $tugasAkhir->update(['status' => $status]);

        return back()->with('success', 'Status Tugas Akhir berhasil diubah menjadi: ' . strtoupper($status));
    }

    /**
     * Mahasiswa mengunggah file laporan akhir (PDF)
     */
    public function uploadReport(Request $request, $id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);

        if (Auth::user()->mahasiswa->id !== $tugasAkhir->mahasiswa_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file_laporan' => 'required|file|mimes:pdf|max:10240', // Max 10MB PDF
        ], [
            'file_laporan.required' => 'File laporan wajib diunggah.',
            'file_laporan.mimes' => 'Format file laporan harus PDF.',
            'file_laporan.max' => 'Ukuran file laporan maksimal adalah 10MB.',
        ]);

        if ($request->file('file_laporan')->isValid()) {
            // Hapus file lama jika ada
            if ($tugasAkhir->file_laporan) {
                Storage::delete($tugasAkhir->file_laporan);
            }

            // Simpan file baru
            $path = $request->file('file_laporan')->store('laporan_ta');
            
            $tugasAkhir->update([
                'file_laporan' => $path
            ]);

            return back()->with('success', 'File laporan Tugas Akhir berhasil diunggah.');
        }

        return back()->with('error', 'Gagal mengunggah file. Silakan coba lagi.');
    }
}
