@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard Mahasiswa')

@section('content')
@php
    $nimPrefix = substr($mahasiswa->nim, 0, 2);
    $angkatan = is_numeric($nimPrefix) ? '20' . $nimPrefix : '2024';
    $tahunAjaran = date('Y') . '/' . (date('Y') + 1);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Kolom Utama (Kiri/Tengah) -->
    <div class="lg:col-span-2 space-y-10">
        <!-- 1. HEADER & PROFIL SINGKAT (White Card style) -->
        <div class="mb-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-4 text-center sm:text-left">
                    <div class="w-14 h-14 bg-[#E8F5E9] dark:bg-slate-800 border border-[#C8E6C9] rounded-2xl flex items-center justify-center font-bold text-[#2E7D32] dark:text-white text-2xl shadow-sm shrink-0">
                        👤
                    </div>
                    <div>
                        <span class="inline-flex px-2.5 py-0.5 bg-[#E8F5E9] dark:bg-slate-800 border border-[#C8E6C9] dark:border-slate-700 text-[#2E7D32] dark:text-white rounded-full text-[9px] font-bold uppercase tracking-wider mb-1">
                            PROFIL MAHASISWA
                        </span>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white leading-tight">Selamat datang, {{ $mahasiswa->nama_lengkap }}</h2>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-3 gap-y-0.5 text-slate-500 dark:text-slate-400 text-xs mt-1.5 font-semibold font-sans">
                            <span>NIM: <span class="text-slate-900 dark:text-white font-mono tracking-wider font-bold">{{ $mahasiswa->nim }}</span></span>
                            <span class="text-slate-300 dark:text-slate-700">•</span>
                            <span>Program Studi: <span class="text-slate-900 dark:text-white font-black">{{ $mahasiswa->program_studi }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="text-center sm:text-right shrink-0 bg-[#FAF6F0] dark:bg-slate-855 border border-[#EBE3D5] dark:border-slate-800 rounded-2xl px-5 py-3 self-stretch sm:self-center flex sm:flex-col justify-between items-center sm:items-end gap-1">
                    <span class="text-slate-400 dark:text-slate-550 text-[10px] font-bold uppercase tracking-wider">TAHUN AJARAN 2026/2027</span>
                    <span class="text-slate-900 dark:text-white text-sm font-extrabold">{{ $tahunAjaran }}</span>
                    <span class="text-slate-500 dark:text-slate-450 text-[10px] font-semibold">Angkatan {{ $angkatan }}</span>
                </div>
            </div>
        </div>

        <!-- 2. PANEL STATUS UTAMA (3 CARDS GRID - Pastel Theme Colors) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <!-- Card 1: Status Judul -->
            <div class="bg-[#E8F5E9] dark:bg-slate-900 border border-[#C8E6C9] dark:border-rose-950 rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] hover:shadow-md transition-all duration-300 flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-white text-[#2E7D32] flex items-center justify-center shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0" style="width: 24px; height: 24px; min-width: 24px; min-height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H9A3.75 3.75 0 0 0 5.25 6v12A3.75 3.75 0 0 0 9 21.75h1.5a1.125 1.125 0 0 0 1.125-1.125v-1.5a3.375 3.375 0 0 1 3.375-3.375h-1.5a3.375 3.375 0 0 0-3.375-3.375Z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-[#2E7D32] dark:text-slate-555 text-[10px] font-bold uppercase tracking-wider block">Status Judul</span>
                    <span class="text-sm font-black text-slate-800 dark:text-white mt-1 block truncate">
                        @if(!$tugasAkhir)
                            Belum Mengajukan
                        @elseif($tugasAkhir->status === 'menunggu')
                            Menunggu Review
                        @elseif($tugasAkhir->status === 'ditolak')
                            Revisi / Ditolak
                        @elseif($tugasAkhir->status === 'disetujui')
                            Judul Disetujui
                        @elseif($tugasAkhir->status === 'sidang')
                            Proses Sidang
                        @elseif($tugasAkhir->status === 'lulus')
                            Lulus
                        @endif
                    </span>
                    @if(!$tugasAkhir || $tugasAkhir->status === 'ditolak')
                        <a href="{{ route('mahasiswa.ta.create') }}" class="mt-2.5 px-4 py-1.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-[11px] font-bold rounded-full shadow-sm hover:shadow transition inline-block text-center select-none">
                            Ajukan Sekarang
                        </a>
                    @else
                        <span class="mt-2.5 px-4 py-1.5 bg-white/60 text-[#2E7D32] border border-[#C8E6C9]/60 text-[11px] font-bold rounded-full inline-block text-center select-none">
                            Terkirim
                        </span>
                    @endif
                </div>
            </div>

            <!-- Card 2: Dosen Pembimbing -->
            <div class="bg-[#FFEBEE] dark:bg-slate-900 border border-[#FFCDD2] dark:border-slate-800 rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] hover:shadow-md transition-all duration-300 flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-white text-[#C62828] flex items-center justify-center shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0" style="width: 24px; height: 24px; min-width: 24px; min-height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84a50.58 50.58 0 0 0-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M12 2.25V4.5m0 12.895V21.75" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-[#C62828] dark:text-slate-555 text-[10px] font-bold uppercase tracking-wider block">Dosen Pembimbing</span>
                    @if($tugasAkhir)
                        <span class="text-sm font-black text-slate-800 dark:text-white mt-1 block truncate" title="{{ $tugasAkhir->dosen->nama_lengkap }}">
                            {{ $tugasAkhir->dosen->nama_lengkap }}
                        </span>
                        <span class="text-xxs text-slate-500 block mt-1 font-mono">NIP. {{ $tugasAkhir->dosen->nip }}</span>
                    @else
                        <span class="text-sm font-black text-[#C62828] mt-1 block">Belum Ditentukan</span>
                        <button disabled class="mt-2.5 px-4 py-1.5 bg-white text-slate-400 border border-slate-200 text-[11px] font-bold rounded-full inline-block text-center cursor-not-allowed select-none">
                            Pilih Dosen
                        </button>
                    @endif
                </div>
            </div>

            <!-- Card 3: Jumlah Bimbingan -->
            <div class="bg-[#E3F2FD] dark:bg-slate-900 border border-[#BBDEFB] rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] hover:shadow-md transition-all duration-300 flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-white text-[#1565C0] flex items-center justify-center shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0" style="width: 24px; height: 24px; min-width: 24px; min-height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.375M9 18h3.375m-6.75 3h12a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3h-12a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3Zm3-16.5h.008v.008H12V4.5ZM12 6h.008v.008H12V6Zm0 2.25h.008v.008H12V8.25Z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-[#1565C0] dark:text-slate-555 text-[10px] font-bold uppercase tracking-wider block">Pertemuan Logbook</span>
                    @php
                        $validatedCount = $tugasAkhir ? $logbooks->where('status_validasi', true)->count() : 0;
                        $totalCount = $tugasAkhir ? $logbooks->count() : 0;
                    @endphp
                    <span class="text-sm font-black text-slate-800 dark:text-white mt-1 block">
                        {{ $validatedCount }} / 8 Valid
                    </span>
                    <!-- Progress Bar -->
                    <div class="mt-2 space-y-1">
                        <div class="w-full bg-white h-1.5 rounded-full overflow-hidden">
                            <div class="bg-[#2196F3] h-full rounded-full transition-all duration-500" style="width: {{ min(100, ($validatedCount / 8) * 100) }}%"></div>
                        </div>
                        <span class="text-slate-500 text-[9px] block font-semibold leading-none">
                            Total input: {{ $totalCount }} bimbingan
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. PROGRESS TRACKER (GRAFIK PERSENTASE & ROADMAP DETAIL - Monochrome design) -->
        <div class="mb-10 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
            <h3 class="text-xs font-bold text-slate-855 dark:text-white uppercase tracking-wider mb-8 flex items-center">
                <span class="mr-2.5">📊</span> Grafik Progres Tugas Akhir
            </h3>

            @php
                $step1 = $tugasAkhir ? true : false;
                $step2 = $tugasAkhir && in_array($tugasAkhir->status, ['disetujui', 'sidang', 'lulus']);
                $step3 = $step2 && ($logbooks->where('status_validasi', true)->count() >= 1);
                $step4 = $step2 && ($logbooks->where('status_validasi', true)->count() >= 3);
                $step5 = $tugasAkhir && in_array($tugasAkhir->status, ['sidang', 'lulus']);
                $step6 = $tugasAkhir && ($tugasAkhir->status === 'lulus');
            @endphp

            <div class="flex flex-col md:flex-row items-center gap-10">
                <!-- Graphical Circular Progress Ring (Left) -->
                 <div class="relative w-36 h-36 flex items-center justify-center shrink-0" style="width: 144px; height: 144px;">
                    <svg width="144" height="144" class="w-36 h-36 transform -rotate-90" style="width: 144px; height: 144px;">
                        <!-- Background Circle -->
                        <circle cx="72" cy="72" r="58" stroke="#e2e8f0" stroke-width="8" fill="transparent" class="dark:stroke-slate-800" />
                        <!-- Foreground Circle (Progress Ring - Soft Gray) -->
                        <circle cx="72" cy="72" r="58" stroke="#94a3b8" stroke-width="8" fill="transparent"
                                stroke-dasharray="364.42"
                                stroke-dashoffset="{{ 364.42 - (364.42 * ($progressPercent / 100)) }}"
                                stroke-linecap="round"
                                class="transition-all duration-700 ease-out" />
                    </svg>
                    <!-- Inner Label -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                        <span class="text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-normal">{{ $progressPercent }}%</span>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-2">Selesai</span>
                    </div>
                </div>
                                   <!-- Roadmap Steps Timeline (Right - Connected vertical list) -->
                <div class="flex-1 w-full relative md:pl-6">
                    <!-- Vertical Line connecting circles -->
                    <div class="absolute left-7 top-4 bottom-4 w-0.5 bg-slate-200 dark:bg-slate-800"></div>

                    <div class="space-y-4 relative">
                        <!-- Step 1 -->
                        <div class="flex items-center space-x-4 p-3 px-4 {{ $step1 ? 'bg-[#E8F5E9] border border-[#C8E6C9] text-[#2E7D32]' : 'bg-white border border-slate-200 text-slate-400' }} rounded-xl relative z-10">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 font-extrabold text-xs {{ $step1 ? 'bg-[#FAFDFB] text-[#2E7D32] border border-[#C8E6C9]' : 'bg-slate-100 text-slate-450 border border-slate-200' }} shadow-sm">
                                1
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs font-bold block">Pengajuan Judul</span>
                                <span class="text-[9px] opacity-70 font-medium">Disimpan oleh mahasiswa</span>
                            </div>
                            @if($step1)
                                <span class="text-sm font-bold">✓</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            @endif
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-center space-x-4 p-3 px-4 {{ $step2 ? 'bg-[#E3F2FD] border border-[#BBDEFB] text-[#1565C0]' : 'bg-white border border-slate-200 text-slate-455' }} rounded-xl relative z-10">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 font-extrabold text-xs {{ $step2 ? 'bg-[#F0F4FF] text-[#1565C0] border border-[#BBDEFB]' : 'bg-slate-100 text-slate-455 border border-slate-200' }} shadow-sm">
                                2
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs font-bold block">Persetujuan Pembimbing</span>
                                <span class="text-[9px] opacity-70 font-medium">Persetujuan Judul</span>
                            </div>
                            @if($step2)
                                <div class="flex items-center space-x-1">
                                    <!-- Gear/Tools icon -->
                                    <span class="text-xs">⚙️</span>
                                </div>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                </svg>
                            @endif
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-center space-x-4 p-3 px-4 {{ $step3 ? 'bg-[#FFF3E0] border border-[#FFE0B2] text-[#E65100]' : 'bg-white border border-slate-200 text-slate-455' }} rounded-xl relative z-10">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 font-extrabold text-xs {{ $step3 ? 'bg-[#FFF8F0] text-[#E65100] border border-[#FFE0B2]' : 'bg-slate-100 text-slate-455 border border-slate-200' }} shadow-sm">
                                3
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs font-bold block">Seminar Proposal</span>
                                <span class="text-[9px] opacity-70 font-medium">Bimbingan Bab 1-3</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex items-center space-x-4 p-3 px-4 {{ $step4 ? 'bg-[#FFF3E0] border border-[#FFE0B2] text-[#E65100]' : 'bg-white border border-slate-200 text-slate-455' }} rounded-xl relative z-10">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 font-extrabold text-xs {{ $step4 ? 'bg-[#FFF8F0] text-[#E65100] border border-[#FFE0B2]' : 'bg-slate-100 text-slate-455 border border-slate-200' }} shadow-sm">
                                4
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs font-bold block">Bimbingan TA</span>
                                <span class="text-[9px] opacity-70 font-medium">Progres & Validasi</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 0 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H9A3.75 3.75 0 0 0 5.25 6v12A3.75 3.75 0 0 0 9 21.75h1.5a1.125 1.125 0 0 0 1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9" />
                            </svg>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex items-center space-x-4 p-3 px-4 {{ $step5 ? 'bg-[#E8EAF6] border border-[#C5CAE9] text-[#283593]' : 'bg-white border border-slate-200 text-slate-455' }} rounded-xl relative z-10">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 font-extrabold text-xs {{ $step5 ? 'bg-[#EEF0FC] text-[#283593] border border-[#C5CAE9]' : 'bg-slate-100 text-slate-455 border border-slate-200' }} shadow-sm">
                                5
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs font-bold block">Pendaftaran Sidang</span>
                                <span class="text-[9px] opacity-70 font-medium">Upload File PDF</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376A8.965 8.965 0 0 0 12 12.75c-.137 0-.274.01-.408.03m3.75 4.5V12a1.125 1.125 0 0 0-1.125-1.125h-3.375M12 18.75c.621 0 1.125-.504 1.125-1.125V12M12 18.75a1.125 1.125 0 0 1-1.125-1.125V12m5.25 0h-3.75m3.75 0V7.5" />
                            </svg>
                        </div>

                        <!-- Step 6 -->
                        <div class="flex items-center space-x-4 p-3 px-4 {{ $step6 ? 'bg-[#E8F5E9] border border-[#C8E6C9] text-[#2E7D32]' : 'bg-white border border-slate-200 text-slate-455' }} rounded-xl relative z-10">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 font-extrabold text-xs {{ $step6 ? 'bg-[#FAFDFB] text-[#2E7D32] border border-[#C8E6C9]' : 'bg-slate-100 text-slate-455 border border-slate-200' }} shadow-sm">
                                6
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs font-bold block">Revisi & Selesai</span>
                                <span class="text-[9px] opacity-70 font-medium">Lulus Ujian Akhir</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Kolom Kanan (Pengumuman, Quick Actions & Catatan Peringatan) -->
    <div class="space-y-6">
        
        <!-- 1. Pengumuman Kampus (At the Top) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] space-y-4">
            <h4 class="text-xs font-bold text-slate-400 dark:text-slate-555 uppercase tracking-widest flex items-center">
                <span class="mr-2">📢</span> TAHUN AJARAN KAMPUS
            </h4>
            
            @if($announcements->count() > 0)
                <div class="divide-y divide-slate-100 dark:divide-slate-850 max-h-[250px] overflow-y-auto pr-1">
                    @foreach($announcements as $announcement)
                        <div class="py-3.5 first:pt-0 last:pb-0 space-y-1.5">
                            <h5 class="text-sm font-extrabold text-slate-855 dark:text-white leading-snug hover:text-black dark:hover:text-white transition-colors cursor-pointer">{{ $announcement->judul }}</h5>
                            <p class="text-xs text-slate-505 dark:text-slate-400 font-semibold line-clamp-3 leading-relaxed">{{ $announcement->konten }}</p>
                            
                            <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-550 pt-1 font-semibold">
                                <span>Oleh: {{ $announcement->user->name }}</span>
                                <span>{{ $announcement->created_at->diffForHumans() }}</span>
                            </div>

                            @if($announcement->file_lampiran)
                                <a href="{{ Storage::url($announcement->file_lampiran) }}" target="_blank" 
                                    class="inline-flex items-center text-[10px] font-bold text-slate-800 dark:text-slate-200 hover:underline mt-1.5">
                                    📎 Unduh Lampiran
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 px-4 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50/50">
                    <span class="text-2xl mb-2">📢</span>
                    <p class="text-[10px] text-slate-450 dark:text-slate-550 text-center font-medium leading-relaxed">Belum ada pengumuman terbaru.</p>
                </div>
            @endif
        </div>

        <!-- 2. Tombol Aksi Cepat (2x2 Grid) -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Action 1: Ajukan Judul TA -->
            @if(!$tugasAkhir || $tugasAkhir->status === 'ditolak')
                <a href="{{ route('mahasiswa.ta.create') }}" class="p-4 bg-[#FEEAD9] hover:bg-[#FDDFC4] border border-[#FCD4B2] hover:border-[#F6B381] rounded-2xl transition-all duration-300 text-center flex flex-col items-center justify-center group shadow-sm hover:shadow">
                    <span class="text-2xl mb-1.5 transition-transform duration-350 group-hover:scale-115">📝</span>
                    <span class="text-[11px] font-bold text-[#7A3E1B] transition-colors leading-tight">Ajukan Judul TA</span>
                    <span class="text-[9px] text-[#A66137] mt-0.5 font-medium leading-none">Mulai pengajuan baru</span>
                </a>
            @else
                <button disabled class="p-4 bg-[#FEEAD9]/40 border border-[#FCD4B2]/30 opacity-60 cursor-not-allowed rounded-2xl text-center flex flex-col items-center justify-center">
                    <span class="text-2xl mb-1.5">📝</span>
                    <span class="text-[11px] font-bold text-[#A66137]/80 leading-tight">Ajukan Judul TA</span>
                    <span class="text-[9px] text-[#A66137]/70 mt-0.5 font-medium leading-none">Sudah Mengajukan</span>
                </button>
            @endif

            <!-- Action 2: Tambah Catatan Logbook -->
            @if($tugasAkhir && $tugasAkhir->status === 'disetujui')
                <button onclick="document.getElementById('logbookFormModal').classList.toggle('hidden'); document.getElementById('logbookFormModal').scrollIntoView({ behavior: 'smooth' });" class="p-4 bg-[#E3F2FD] hover:bg-[#D0E8FD] border border-[#BBDEFB] hover:border-[#90CAF9] rounded-2xl transition-all duration-300 text-center flex flex-col items-center justify-center group shadow-sm hover:shadow">
                    <span class="text-2xl mb-1.5 transition-transform duration-350 group-hover:scale-115">📖</span>
                    <span class="text-[11px] font-bold text-[#1565C0] transition-colors leading-tight">Catat Bimbingan</span>
                    <span class="text-[9px] text-[#478ECC] mt-0.5 font-medium leading-none">Catat progress kemajuan</span>
                </button>
            @else
                <button disabled class="p-4 bg-slate-50/50 dark:bg-slate-855 border border-slate-150/50 dark:border-slate-800/30 opacity-40 cursor-not-allowed rounded-2xl text-center flex flex-col items-center justify-center">
                    <span class="text-2xl mb-1.5">📖</span>
                    <span class="text-[11px] font-bold text-slate-400 dark:text-slate-650 leading-tight">Catat Bimbingan</span>
                    <span class="text-[9px] text-slate-405 mt-0.5 font-medium leading-none">Logbook nonaktif</span>
                </button>
            @endif

            <!-- Action 3: Daftar Sidang -->
            @if($tugasAkhir && $tugasAkhir->status === 'disetujui')
                <a href="#pendaftaranSidangSection" class="p-4 bg-[#E5F2EB] hover:bg-[#D5EAE0] border border-[#C8E6C9] hover:border-[#A5D6A7] rounded-2xl transition-all duration-300 text-center flex flex-col items-center justify-center group shadow-sm hover:shadow">
                    <span class="text-2xl mb-1.5 transition-transform duration-350 group-hover:scale-115">🎓</span>
                    <span class="text-[11px] font-bold text-[#2E7D32] transition-colors leading-tight">Daftar Sidang TA</span>
                    <span class="text-[9px] text-[#5C9A78] mt-0.5 font-medium leading-none">Unggah PDF laporan</span>
                </a>
            @else
                <button disabled class="p-4 bg-slate-50/50 dark:bg-slate-850/40 border border-slate-150/50 dark:border-slate-800/30 opacity-40 cursor-not-allowed rounded-2xl text-center flex flex-col items-center justify-center">
                    <span class="text-2xl mb-1.5">🎓</span>
                    <span class="text-[11px] font-bold text-slate-400 dark:text-slate-650 leading-tight">Daftar Sidang TA</span>
                    <span class="text-[9px] text-slate-405 mt-0.5 font-medium leading-none">Sidang nonaktif</span>
                </button>
            @endif

            <!-- Action 4: Cetak Kartu Logbook -->
            @if($tugasAkhir && in_array($tugasAkhir->status, ['disetujui', 'sidang', 'lulus']))
                <a href="{{ route('ta.print_logbook', $tugasAkhir->id) }}" target="_blank" class="p-4 bg-[#FDF2F4] hover:bg-[#FBE4E8] border border-[#FCE4EC] hover:border-[#F8BBD0] rounded-2xl transition-all duration-300 text-center flex flex-col items-center justify-center group shadow-sm hover:shadow">
                    <span class="text-2xl mb-1.5 transition-transform duration-350 group-hover:scale-115">🖨️</span>
                    <span class="text-[11px] font-bold text-[#C62828] transition-colors leading-tight">Cetak Logbook</span>
                    <span class="text-[9px] text-[#D47087] mt-0.5 font-medium leading-none">Unduh kartu kendali</span>
                </a>
            @else
                <button disabled class="p-4 bg-slate-50/50 dark:bg-[#FAF6F0] border border-slate-150/50 dark:border-slate-800/30 opacity-40 cursor-not-allowed rounded-2xl text-center flex flex-col items-center justify-center">
                    <span class="text-2xl mb-1.5">🖨️</span>
                    <span class="text-[11px] font-bold text-slate-400 dark:text-slate-650 leading-tight">Cetak Logbook</span>
                    <span class="text-[9px] text-slate-405 mt-0.5 font-medium leading-none">Cetak belum tersedia</span>
                </button>
            @endif
        </div>

        <!-- 3. Catatan & Peringatan (Conditional Banner) -->
        @if(!$tugasAkhir)
            <div class="bg-[#FEEAD9] border border-[#FCD4B2] rounded-2xl p-6 text-[#7A3E1B] space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#5C2E14] flex items-center">
                    <span class="mr-1.5">⚠️</span> Catatan & Peringatan
                </h4>
                <div class="space-y-2">
                    <p class="text-xs font-black text-[#5C2E14]">Belum Mengajukan Judul Tugas Akhir</p>
                    <p class="text-[11px] leading-relaxed text-[#7A3E1B]/90 font-medium">
                        Anda belum mendaftarkan usulan Tugas Akhir Anda di sistem. Silakan ajukan judul TA sebelah kiri atau gunakan tombol pintasan **"Ajukan Judul TA"** di atas.
                    </p>
                    <ul class="list-disc pl-4 text-[10px] space-y-1 text-[#7A3E1B]/95 font-semibold">
                        <li>Mengajukan Judul TA tanpa hambatan.</li>
                        <li>Silakan proposal anda dengan menekan tombol **"Ajukan Judul TA"** di atas.</li>
                        <li>Belum itu the shortcut.</li>
                    </ul>
                </div>
            </div>
        @elseif($tugasAkhir->status === 'ditolak')
            <div class="bg-[#FEEAD9] border border-[#FCD4B2] rounded-2xl p-6 text-[#7A3E1B] space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#5C2E14] flex items-center">
                    <span class="mr-1.5">⚠️</span> Catatan & Peringatan
                </h4>
                <div class="space-y-2">
                    <p class="text-xs font-black text-[#C62828]">Pengajuan Judul TA Ditolak</p>
                    <p class="text-[11px] leading-relaxed font-medium">
                        Maaf, pengajuan judul tugas akhir Anda ditolak. Silakan buka menu **"Pengajuan Judul TA"** di sidebar untuk mengirimkan perbaikan judul atau mengganti usulan pembimbing.
                    </p>
                </div>
            </div>
        @elseif($tugasAkhir->status === 'menunggu')
            <div class="bg-[#E3F2FD] border border-[#BBDEFB] rounded-2xl p-6 text-[#1565C0] space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#1565C0] flex items-center">
                    <span class="mr-1.5">⏳</span> Catatan & Peringatan
                </h4>
                <div class="space-y-2">
                    <p class="text-xs font-black text-[#1565C0]">Judul TA Menunggu Persetujuan</p>
                    <p class="text-[11px] leading-relaxed font-medium">
                        Judul tugas akhir Anda: <strong>"{{ $tugasAkhir->judul }}"</strong> sedang ditinjau oleh Admin atau Dosen Pembimbing pilihan Anda ({{ $tugasAkhir->dosen->nama_lengkap }}).
                    </p>
                </div>
            </div>
        @endif

        <!-- 4. Dosen Pembimbing Info (Hanya jika disetujui / ada TA) -->
        @if($tugasAkhir)
            <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] space-y-4">
                <h4 class="text-xs font-bold text-slate-400 dark:text-slate-555 uppercase tracking-widest">Dosen Pembimbing</h4>
                <div class="flex items-center space-x-3.5">
                    <div class="w-14 h-14 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-center font-bold text-slate-800 dark:text-white text-2xl shadow-sm">
                        👨‍🏫
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-805 dark:text-white text-sm leading-snug">{{ $tugasAkhir->dosen->nama_lengkap }}</h4>
                        <span class="text-[10px] text-slate-400 dark:text-slate-555 block font-mono mt-0.5">NIP. {{ $tugasAkhir->dosen->nip }}</span>
                        <span class="text-[9px] font-bold bg-slate-100 border border-slate-200 dark:bg-slate-800 dark:border-slate-700 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-full block mt-1.5 font-medium select-none w-max">
                            {{ $tugasAkhir->dosen->bidang_keahlian }}
                        </span>
                    </div>
                </div>
                <hr class="border-slate-100 dark:border-slate-850">
                <div class="text-xs text-slate-500 dark:text-slate-400 space-y-1.5">
                    <p class="font-bold text-slate-850 dark:text-white">Judul Tugas Akhir:</p>
                    <p class="font-semibold text-slate-600 dark:text-slate-455 leading-relaxed italic">"{{ $tugasAkhir->judul }}"</p>
                    <p class="mt-2.5 text-[10px] leading-relaxed font-semibold text-slate-400 dark:text-slate-500 border-t border-slate-50 dark:border-slate-800/80 pt-2">{{ $tugasAkhir->deskripsi }}</p>
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
