@extends('layouts.app')

@section('title', 'Laporan Akhir & Sidang')
@section('page-title', 'Laporan Akhir & Sidang')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- 1. PENGUMPULAN & PENDAFTARAN (Jika disetujui, belum sidang/lulus) -->
    @if($tugasAkhir->status === 'disetujui')
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] space-y-5">
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center">
                <span class="mr-2">📄</span> Laporan Akhir & Pengajuan Sidang
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Upload PDF -->
                <div class="space-y-4">
                    <h4 class="text-sm font-bold text-slate-700 dark:text-slate-350">1. Unggah File Laporan Akhir (PDF)</h4>
                    <form action="{{ route('mahasiswa.ta.upload', $tugasAkhir->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="p-4 bg-slate-50 dark:bg-slate-855 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <span class="text-3xl mb-1">📄</span>
                            <input type="file" name="file_laporan" required accept=".pdf" 
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-slate-800 dark:file:bg-slate-800 dark:file:text-white cursor-pointer">
                        </div>
                        <button type="submit" class="px-5 py-2.5 bg-slate-900 text-white hover:bg-black dark:bg-white dark:text-slate-900 rounded-xl text-xs font-bold transition">
                            Unggah Laporan PDF
                        </button>
                    </form>
                    @if($tugasAkhir->file_laporan)
                        <p class="text-xs text-[#2E7D32] dark:text-[#A5D6A7] font-bold flex items-center">
                            <span class="mr-1.5">✓</span> File Laporan Terunggah: 
                            <a href="{{ Storage::url($tugasAkhir->file_laporan) }}" target="_blank" class="underline ml-1 font-extrabold hover:text-black dark:hover:text-white">Lihat File</a>
                        </p>
                    @else
                        <p class="text-xs text-slate-455 dark:text-slate-500 font-bold flex items-center">
                            <span class="mr-1.5">⚠</span> Belum mengunggah file laporan akhir.
                        </p>
                    @endif
                </div>

                <!-- Apply Sidang Button -->
                <div class="p-5 bg-slate-50 dark:bg-slate-850 border border-slate-150 dark:border-slate-800 rounded-2xl flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-350">2. Daftar Sidang Ujian Akhir</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-450 mt-2 leading-relaxed font-normal">
                            Persyaratan: File laporan akhir (PDF) sudah terunggah dan minimal memiliki 3 catatan bimbingan logbook yang disetujui dosen.
                        </p>
                    </div>
                    
                    <form action="{{ route('mahasiswa.ta.apply_sidang', $tugasAkhir->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl text-xs font-bold text-center transition {{ ($tugasAkhir->file_laporan && $logbooks->where('status_validasi', true)->count() >= 3) ? 'bg-slate-900 text-white hover:bg-black dark:bg-white dark:text-slate-900 shadow-sm hover:-translate-y-0.5' : 'bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-650 cursor-not-allowed' }}"
                            {{ !($tugasAkhir->file_laporan && $logbooks->where('status_validasi', true)->count() >= 3) ? 'disabled' : '' }}>
                            Daftar Sidang TA Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. DETAIL JADWAL SIDANG MAHASISWA -->
    @if($tugasAkhir->status === 'sidang' && $sidang)
        <div class="bg-slate-900 dark:bg-slate-950 border border-slate-800 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 text-9xl opacity-5 pointer-events-none">📅</div>
            <h3 class="text-lg font-extrabold flex items-center mb-5">
                <span class="mr-2">📅</span> Jadwal Sidang Tugas Akhir Anda
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm font-semibold">
                <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                    <span class="text-slate-350 text-xxs uppercase tracking-wider block">Dosen Penguji</span>
                    <span class="text-base font-extrabold block mt-0.5">{{ $sidang->dosenPenguji->nama_lengkap }}</span>
                </div>
                <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                    <span class="text-slate-350 text-xxs uppercase tracking-wider block">Ruang Sidang</span>
                    <span class="text-base font-extrabold block mt-0.5">{{ $sidang->ruangan }}</span>
                </div>
                <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                    <span class="text-slate-350 text-xxs uppercase tracking-wider block">Waktu Sidang</span>
                    <span class="text-base font-extrabold block mt-0.5">{{ date('d M Y, H:i', strtotime($sidang->tanggal_sidang)) }} WIB</span>
                </div>
                <div class="p-3 bg-white/5 rounded-xl border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="text-slate-350 text-xxs uppercase tracking-wider block">Status Jadwal</span>
                        <span class="text-xs font-black block mt-0.5 tracking-wider uppercase text-white">JADWAL TERBIT</span>
                    </div>
                    <span class="text-2xl">⚡</span>
                </div>
            </div>
        </div>
    @elseif($tugasAkhir->status === 'sidang' && !$sidang)
        <div class="bg-slate-50 dark:bg-slate-855 border border-slate-205 dark:border-slate-800 rounded-3xl p-6 text-slate-805 dark:text-slate-200 relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 text-9xl opacity-5 pointer-events-none">⏱️</div>
            <div class="flex items-start">
                <span class="text-3xl mr-4">⏱️</span>
                <div>
                    <h4 class="font-extrabold text-base">Pendaftaran Sidang Berhasil</h4>
                    <p class="text-sm mt-1 text-slate-600 dark:text-slate-400 leading-relaxed font-semibold">
                        Anda sudah terdaftar untuk ujian sidang. Admin sedang menjadwalkan tanggal, ruangan, dan dosen penguji Anda.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. DETAIL KELULUSAN & TRANSKRIP NILAI -->
    @if($tugasAkhir->status === 'lulus' && $sidang)
        <div class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 border border-slate-900 dark:border-slate-100 rounded-3xl p-6 shadow-xl space-y-5 relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 text-9xl opacity-5 pointer-events-none">🏆</div>
            <div class="flex items-center space-x-4">
                <span class="text-4xl">🎉</span>
                <div>
                    <h3 class="text-xl font-black">Selamat! Anda Dinyatakan LULUS</h3>
                    <p class="text-xs text-slate-300 dark:text-slate-600 font-semibold">Ujian Sidang Tugas Akhir Anda telah dinilai dan divalidasi oleh dewan penguji.</p>
                </div>
            </div>
            
            <hr class="border-white/10 dark:border-black/10">
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm font-semibold">
                <div class="p-3 bg-white/5 dark:bg-black/5 rounded-xl border border-white/10 dark:border-black/10">
                    <span class="text-slate-300 dark:text-slate-600 text-xxs block uppercase tracking-wider font-bold">Nilai Angka</span>
                    <span class="text-3xl font-black block mt-1 tracking-wide">{{ $sidang->nilai_angka }}</span>
                </div>
                <div class="p-3 bg-white/5 dark:bg-black/5 rounded-xl border border-white/10 dark:border-black/10">
                    <span class="text-slate-300 dark:text-slate-600 text-xxs block uppercase tracking-wider font-bold">Nilai Huruf</span>
                    <span class="text-3xl font-black block mt-1 tracking-wide">{{ $sidang->nilai_huruf }}</span>
                </div>
                <div class="p-3 bg-white/5 dark:bg-black/5 rounded-xl border border-white/10 dark:border-black/10">
                    <span class="text-slate-300 dark:text-slate-600 text-xxs block uppercase tracking-wider font-bold">Dewan Penguji</span>
                    <span class="text-xs font-extrabold truncate block mt-2" title="{{ $sidang->dosenPenguji->nama_lengkap }}">{{ $sidang->dosenPenguji->nama_lengkap }}</span>
                </div>
                <div class="flex items-center justify-center p-1">
                    <a href="{{ route('sidang.print_berita_acara', $sidang->id) }}" target="_blank" 
                        class="w-full py-3 px-3 bg-white text-slate-900 dark:bg-slate-900 dark:text-white font-black text-xs text-center rounded-xl hover:bg-slate-105 transition shadow hover:-translate-y-0.5">
                        Cetak Berita Acara
                    </a>
                </div>
            </div>

            @if($sidang->catatan)
                <div class="p-3.5 bg-white/10 dark:bg-black/5 rounded-xl text-xs font-normal leading-relaxed">
                    <strong class="font-bold">Catatan Penguji:</strong> {{ $sidang->catatan }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
