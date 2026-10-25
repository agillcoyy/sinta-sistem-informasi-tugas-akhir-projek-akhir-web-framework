<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    /**
     * Menyimpan Pengumuman Baru (Admin / Dosen)
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,png,zip|max:5120', // Max 5MB
        ], [
            'judul.required' => 'Judul pengumuman wajib diisi.',
            'konten.required' => 'Konten pengumuman wajib diisi.',
            'file_lampiran.mimes' => 'Format file lampiran harus PDF, JPG, PNG, atau ZIP.',
            'file_lampiran.max' => 'Ukuran file lampiran maksimal adalah 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_lampiran') && $request->file('file_lampiran')->isValid()) {
            $filePath = $request->file('file_lampiran')->store('pengumuman_lampiran');
        }

        Pengumuman::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'konten' => $request->konten,
            'file_lampiran' => $filePath,
        ]);

        return back()->with('success', 'Pengumuman baru berhasil dipublikasikan.');
    }

    /**
     * Menghapus Pengumuman
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        // Hanya pembuat pengumuman atau admin yang bisa menghapus
        if (Auth::id() !== $pengumuman->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pengumuman->file_lampiran) {
            Storage::delete($pengumuman->file_lampiran);
        }

        $pengumuman->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
