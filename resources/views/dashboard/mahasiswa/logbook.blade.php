@extends('layouts.app')

@section('title', 'Logbook Bimbingan')
@section('page-title', 'Logbook Bimbingan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header & Info Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">
                📖 Logbook & Kegiatan Bimbingan
            </h3>
            <p class="text-xs text-slate-505 dark:text-slate-400 mt-1 font-semibold leading-relaxed">
                Catat kemajuan tugas akhir Anda secara rutin dan pastikan divalidasi oleh dosen pembimbing.
            </p>
        </div>
        <div class="flex space-x-2 shrink-0">
            @if($tugasAkhir->status === 'disetujui')
                <button onclick="document.getElementById('logbookFormModal').classList.toggle('hidden')" 
                    class="px-4 py-2.5 bg-[#2E7D32] hover:bg-[#25632A] text-white rounded-xl text-xs font-bold transition shadow-sm">
                    + Catat Bimbingan
                </button>
            @endif
            <a href="{{ route('ta.print_logbook', $tugasAkhir->id) }}" target="_blank"
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold transition border border-slate-200 dark:border-slate-700">
                🖨️ Cetak Logbook
            </a>
        </div>
    </div>

    <!-- Modal Form Bimbingan -->
    <div id="logbookFormModal" class="hidden p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl space-y-4 shadow-sm">
        <h4 class="text-sm font-extrabold text-slate-805 dark:text-white flex items-center">
            <span class="mr-2">🖊️</span> Catat Logbook Baru
        </h4>
        <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal Bimbingan</label>
                    <input type="date" name="tanggal_bimbingan" required max="{{ date('Y-m-d') }}" 
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white text-sm focus:outline-none focus:border-slate-900 dark:focus:border-white">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Detail Kegiatan / Progress</label>
                <textarea name="kegiatan" rows="4" required placeholder="Sebutkan bab atau revisi yang dikonsultasikan serta hasil bimbingan..." 
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white text-sm focus:outline-none focus:border-slate-900 dark:focus:border-white"></textarea>
            </div>
            <div class="flex space-x-2 pt-2">
                <button type="submit" class="px-5 py-2.5 bg-slate-900 text-white hover:bg-black dark:bg-white dark:text-slate-900 rounded-xl text-xs font-bold transition">
                    Simpan Catatan
                </button>
                <button type="button" onclick="document.getElementById('logbookFormModal').classList.add('hidden')" 
                    class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 dark:text-slate-350 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl text-xs font-bold transition">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <!-- List Logbook -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] space-y-4">
        <h4 class="text-xs font-bold text-slate-400 dark:text-slate-555 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 pb-3">
            Daftar Riwayat Bimbingan
        </h4>

        @if($logbooks->count() > 0)
            <div class="space-y-4">
                @foreach($logbooks as $logbook)
                    <div class="p-5 bg-slate-50 dark:bg-slate-850/40 border border-slate-150 dark:border-slate-800/80 rounded-2xl space-y-3 relative hover:shadow-sm transition duration-250">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-455 dark:text-slate-500">📅 {{ date('d M Y', strtotime($logbook->tanggal_bimbingan)) }}</span>
                            @if($logbook->status_validasi)
                                <span class="px-3 py-0.5 bg-slate-900 text-white dark:bg-white dark:text-slate-900 text-[10px] font-extrabold uppercase rounded-full border border-slate-900 dark:border-white">
                                    ✓ Tervalidasi
                                </span>
                            @else
                                <span class="px-3 py-0.5 bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 text-[10px] font-extrabold uppercase rounded-full border border-slate-200 dark:border-slate-700">
                                    ⌛ Menunggu
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-750 dark:text-slate-350 leading-relaxed font-semibold">
                            <span class="text-slate-400 dark:text-slate-500 mr-1.5 font-normal">Progress:</span>{{ $logbook->kegiatan }}
                        </p>
                        @if($logbook->catatan_dosen)
                            <div class="mt-2 p-3.5 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl relative">
                                <div class="absolute right-3 top-3 text-slate-400 text-xs">💬</div>
                                <p class="text-xs text-slate-805 dark:text-slate-200 leading-relaxed">
                                    <strong class="font-bold text-slate-900 dark:text-white">Masukan Dosen:</strong> {{ $logbook->catatan_dosen }}
                                </p>
                            </div>
                        @endif

                        @if(!$logbook->status_validasi && $tugasAkhir->status === 'disetujui')
                            <!-- Delete Button -->
                            <div class="flex justify-end space-x-2 pt-1 border-t border-slate-150/40 dark:border-slate-800/40">
                                <form action="{{ route('mahasiswa.logbook.destroy', $logbook->id) }}" method="POST" onsubmit="return confirm('Hapus logbook ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-350 text-xs font-bold transition">
                                        Hapus Catatan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 space-y-2">
                <span class="text-3xl block">📖</span>
                <p class="text-sm text-slate-450 dark:text-slate-500 font-bold">Belum ada catatan bimbingan logbook.</p>
                <p class="text-xs text-slate-400">Silakan klik "+ Catat Bimbingan" di kanan atas untuk memulai.</p>
            </div>
        @endif
    </div>

</div>
@endsection
