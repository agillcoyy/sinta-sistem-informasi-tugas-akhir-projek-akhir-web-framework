<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logbook;
use App\Models\TugasAkhir;

class LogbookController extends Controller
{
    /**
     * Mahasiswa mencatat logbook bimbingan baru
     */
    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();

        if (!$tugasAkhir || $tugasAkhir->status !== 'disetujui' && $tugasAkhir->status !== 'sidang') {
            return back()->with('error', 'Anda harus memiliki Tugas Akhir yang disetujui untuk mencatat logbook.');
        }

        $request->validate([
            'tanggal_bimbingan' => 'required|date|before_or_equal:today',
            'kegiatan' => 'required|string',
        ], [
            'tanggal_bimbingan.required' => 'Tanggal bimbingan wajib diisi.',
            'tanggal_bimbingan.before_or_equal' => 'Tanggal bimbingan tidak boleh melebihi tanggal hari ini.',
            'kegiatan.required' => 'Detail kegiatan bimbingan wajib diisi.',
        ]);

        Logbook::create([
            'tugas_akhir_id' => $tugasAkhir->id,
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'kegiatan' => $request->kegiatan,
            'status_validasi' => false,
        ]);

        return back()->with('success', 'Catatan logbook bimbingan berhasil ditambahkan.');
    }

    /**
     * Mahasiswa memperbarui catatan logbook (jika belum divalidasi)
     */
    public function update(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);
        $tugasAkhir = $logbook->tugasAkhir;

        // Otorisasi: hanya pemilik logbook
        if (Auth::user()->mahasiswa->id !== $tugasAkhir->mahasiswa_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($logbook->status_validasi) {
            return back()->with('error', 'Logbook yang sudah divalidasi dosen tidak dapat diubah.');
        }

        $request->validate([
            'tanggal_bimbingan' => 'required|date|before_or_equal:today',
            'kegiatan' => 'required|string',
        ]);

        $logbook->update([
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'kegiatan' => $request->kegiatan,
        ]);

        return back()->with('success', 'Catatan logbook berhasil diperbarui.');
    }

    /**
     * Mahasiswa menghapus logbook (jika belum divalidasi)
     */
    public function destroy($id)
    {
        $logbook = Logbook::findOrFail($id);
        $tugasAkhir = $logbook->tugasAkhir;

        if (Auth::user()->mahasiswa->id !== $tugasAkhir->mahasiswa_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($logbook->status_validasi) {
            return back()->with('error', 'Logbook yang sudah divalidasi dosen tidak dapat dihapus.');
        }

        $logbook->delete();

        return back()->with('success', 'Catatan logbook berhasil dihapus.');
    }

    /**
     * Dosen memvalidasi bimbingan logbook mahasiswa
     */
    public function validateLogbook(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);
        $tugasAkhir = $logbook->tugasAkhir;

        // Otorisasi: hanya dosen pembimbing bersangkutan
        if (Auth::user()->dosen->id !== $tugasAkhir->dosen_id) {
            abort(403, 'Unauthorized action. Anda bukan dosen pembimbing mahasiswa ini.');
        }

        $request->validate([
            'catatan_dosen' => 'nullable|string',
            'status_validasi' => 'required|boolean',
        ]);

        $logbook->update([
            'catatan_dosen' => $request->catatan_dosen,
            'status_validasi' => $request->status_validasi,
        ]);

        return back()->with('success', $request->status_validasi ? 'Logbook bimbingan berhasil disetujui/divalidasi.' : 'Status validasi logbook berhasil diperbarui.');
    }

    /**
     * Print PDF/Tampilan Print untuk Logbook Card (Kartu Bimbingan)
     */
    public function print($id)
    {
        $tugasAkhir = TugasAkhir::with(['mahasiswa', 'dosen', 'logbooks' => function($query) {
            $query->orderBy('tanggal_bimbingan', 'asc');
        }])->findOrFail($id);

        // Otorisasi: mahasiswa bersangkutan, dosen pembimbingnya, atau admin
        $user = Auth::user();
        if ($user->isMahasiswa() && $user->mahasiswa->id !== $tugasAkhir->mahasiswa_id) {
            abort(403);
        }
        if ($user->isDosen() && $user->dosen->id !== $tugasAkhir->dosen_id) {
            abort(403);
        }

        return view('dashboard.print-logbook', compact('tugasAkhir'));
    }
}
