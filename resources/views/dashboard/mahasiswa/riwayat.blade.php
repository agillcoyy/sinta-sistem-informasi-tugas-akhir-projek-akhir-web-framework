@extends('layouts.app')

@section('title', 'Riwayat Kegiatan')
@section('page-title', 'Riwayat Kegiatan')

@section('content')
@php
    $activities = collect();

    // 1. Pengajuan TA
    if ($tugasAkhir) {
        $activities->push((object)[
            'timestamp' => strtotime($tugasAkhir->created_at),
            'date' => $tugasAkhir->created_at,
            'icon' => '📝',
            'title' => 'Mengajukan Judul Tugas Akhir',
            'description' => 'Mengajukan judul baru: "' . $tugasAkhir->judul . '" dengan usulan dosen pembimbing ' . $tugasAkhir->dosen->nama_lengkap . '.',
            'badge' => 'Pengajuan',
            'badge_color' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700'
        ]);

        if ($tugasAkhir->status === 'disetujui') {
            $activities->push((object)[
                'timestamp' => strtotime($tugasAkhir->updated_at),
                'date' => $tugasAkhir->updated_at,
                'icon' => '✨',
                'title' => 'Judul Tugas Akhir Disetujui',
                'description' => 'Judul Tugas Akhir Anda telah disetujui oleh Koordinator TA. Anda dapat mulai mencatat logbook bimbingan.',
                'badge' => 'Persetujuan',
                'badge_color' => 'bg-slate-900 text-white dark:bg-white dark:text-slate-905 border border-slate-900 dark:border-white'
            ]);
        } elseif ($tugasAkhir->status === 'ditolak') {
            $activities->push((object)[
                'timestamp' => strtotime($tugasAkhir->updated_at),
                'date' => $tugasAkhir->updated_at,
                'icon' => '❌',
                'title' => 'Judul Tugas Akhir Ditolak',
                'description' => 'Judul Tugas Akhir Anda ditolak. Silakan periksa masukan Dosen/Admin dan ajukan kembali.',
                'badge' => 'Ditolak',
                'badge_color' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700'
            ]);
        }
    }

    // 2. Logbook bimbingan
    foreach ($logbooks as $logbook) {
        $activities->push((object)[
            'timestamp' => strtotime($logbook->created_at),
            'date' => $logbook->created_at,
            'icon' => '📖',
            'title' => 'Mencatat Logbook Bimbingan',
            'description' => 'Mencatat progress bimbingan tanggal ' . date('d M Y', strtotime($logbook->tanggal_bimbingan)) . ': "' . \Str::limit($logbook->kegiatan, 100) . '"',
            'badge' => 'Logbook',
            'badge_color' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700'
        ]);

        if ($logbook->status_validasi) {
            $activities->push((object)[
                'timestamp' => strtotime($logbook->updated_at),
                'date' => $logbook->updated_at,
                'icon' => '⚡',
                'title' => 'Bimbingan Tervalidasi',
                'description' => 'Bimbingan tanggal ' . date('d M Y', strtotime($logbook->tanggal_bimbingan)) . ' telah divalidasi oleh dosen pembimbing.',
                'badge' => 'Validasi',
                'badge_color' => 'bg-slate-900 text-white dark:bg-white dark:text-slate-905 border border-slate-900 dark:border-white'
            ]);
        }

        if ($logbook->catatan_dosen) {
            $activities->push((object)[
                'timestamp' => strtotime($logbook->updated_at) + 1,
                'date' => $logbook->updated_at,
                'icon' => '💬',
                'title' => 'Catatan Dosen Pembimbing',
                'description' => 'Dosen memberikan masukan: "' . \Str::limit($logbook->catatan_dosen, 120) . '"',
                'badge' => 'Masukan',
                'badge_color' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700'
            ]);
        }
    }

    // 3. Sidang
    if ($sidang) {
        $activities->push((object)[
            'timestamp' => strtotime($sidang->created_at),
            'date' => $sidang->created_at,
            'icon' => '🎓',
            'title' => 'Mendaftar Ujian Sidang',
            'description' => 'Melakukan pendaftaran ujian sidang Tugas Akhir secara online.',
            'badge' => 'Sidang',
            'badge_color' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700'
        ]);

        if ($sidang->tanggal_sidang) {
            $activities->push((object)[
                'timestamp' => strtotime($sidang->tanggal_sidang) - 86400,
                'date' => $sidang->updated_at,
                'icon' => '📅',
                'title' => 'Jadwal Ujian Sidang Diterbitkan',
                'description' => 'Jadwal ujian sidang Anda diterbitkan: ' . date('d M Y, H:i', strtotime($sidang->tanggal_sidang)) . ' WIB di ruangan ' . $sidang->ruangan . '.',
                'badge' => 'Jadwal',
                'badge_color' => 'bg-slate-900 text-white dark:bg-white dark:text-slate-905 border border-slate-900 dark:border-white'
            ]);
        }

        if ($tugasAkhir->status === 'lulus') {
            $activities->push((object)[
                'timestamp' => strtotime($sidang->updated_at),
                'date' => $sidang->updated_at,
                'icon' => '🏆',
                'title' => 'Dinyatakan Lulus Ujian Sidang',
                'description' => 'Selamat! Anda lulus dengan nilai angka ' . $sidang->nilai_angka . ' (' . $sidang->nilai_huruf . ').',
                'badge' => 'Lulus',
                'badge_color' => 'bg-slate-900 text-white dark:bg-white dark:text-slate-905 border border-slate-900 dark:border-white'
            ]);
        }
    }

    $sortedActivities = $activities->sortByDesc('timestamp');
@endphp

<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)] space-y-6">
        <div>
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center">
                <span class="mr-2">🕒</span> Riwayat & Aktivitas Akademik
            </h3>
            <p class="text-xs text-slate-505 dark:text-slate-400 mt-1 font-semibold leading-relaxed">
                Linimasa lengkap seluruh aktivitas pendaftaran, bimbingan logbook, dan ujian Tugas Akhir Anda.
            </p>
        </div>
        
        <hr class="border-slate-100 dark:border-slate-800/80">

        @if($sortedActivities->count() > 0)
            <div class="flow-root pl-2">
                <ul role="list" class="-mb-8">
                    @foreach($sortedActivities as $index => $act)
                        <li>
                            <div class="relative pb-8">
                                @if($index !== $sortedActivities->count() - 1)
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-slate-150 dark:bg-slate-800/85" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-4 items-start">
                                    <div>
                                        <span class="h-10 w-10 rounded-xl bg-slate-50 dark:bg-slate-850/80 border border-slate-150 dark:border-slate-800 flex items-center justify-center ring-4 ring-white dark:ring-slate-900 text-base shadow-sm">
                                            {{ $act->icon }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-1.5">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-x-4 gap-y-1">
                                            <div>
                                                <p class="text-xs font-bold text-slate-850 dark:text-white">{{ $act->title }}</p>
                                                <p class="text-[11px] text-slate-505 dark:text-slate-500 mt-1 font-semibold leading-relaxed">{{ $act->description }}</p>
                                            </div>
                                            <div class="text-left sm:text-right shrink-0">
                                                <span class="text-[10px] text-slate-455 dark:text-slate-550 block font-mono">{{ date('d M Y, H:i', $act->timestamp) }} WIB</span>
                                                <span class="text-[9px] font-extrabold uppercase px-2.5 py-0.5 rounded-full inline-block mt-1.5 {{ $act->badge_color }}">
                                                    {{ $act->badge }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 px-4 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50/50">
                <span class="text-3xl mb-2">⏱️</span>
                <p class="text-xs text-slate-450 dark:text-slate-500 text-center font-medium leading-relaxed">Belum ada riwayat aktivitas akademik Tugas Akhir tercatat.</p>
            </div>
        @endif
    </div>
</div>
@endsection
