@extends('layouts.app')

@section('title', 'Dashboard Dosen')
@section('page-title', 'Dashboard Dosen')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- Kolom Ringkasan Kartu / Metrik -->
    <div class="lg:col-span-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Bimbingan Aktif -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <span class="text-3xl bg-blue-50 p-3 rounded-xl">👨‍🎓</span>
            <div>
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Bimbingan Aktif</span>
                <span class="text-2xl font-bold text-slate-800">{{ $bimbinganAktif }}</span>
            </div>
        </div>

        <!-- Total Bimbingan -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <span class="text-3xl bg-indigo-50 p-3 rounded-xl">📚</span>
            <div>
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Total Mahasiswa</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalBimbingan }}</span>
            </div>
        </div>

        <!-- Logbook Tertunda -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <span class="text-3xl bg-amber-50 p-3 rounded-xl">⌛</span>
            <div>
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Logbook Pending</span>
                <span class="text-2xl font-bold text-slate-800">{{ $logbookPending }}</span>
            </div>
        </div>

        <!-- Jadwal Menguji -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <span class="text-3xl bg-emerald-50 p-3 rounded-xl">⚖️</span>
            <div>
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Jadwal Menguji</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalMenguji }}</span>
            </div>
        </div>
    </div>

    <!-- Kolom Kiri / Utama -->
    <div class="lg:col-span-3 space-y-6">

        <!-- DAFTAR MAHASISWA BIMBINGAN -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4">
                👨‍🎓 Mahasiswa Bimbingan Anda
            </h3>

            @if($mahasiswaBimbingan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xxs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100 select-none">
                            <tr>
                                <th class="px-4 py-3">Mahasiswa</th>
                                <th class="px-4 py-3">NIM / Program Studi</th>
                                <th class="px-4 py-3">Judul TA</th>
                                <th class="px-4 py-3">Status TA</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @foreach($mahasiswaBimbingan as $ta)
                                <tr>
                                    <td class="px-4 py-3.5">{{ $ta->mahasiswa->nama_lengkap }}</td>
                                    <td class="px-4 py-3.5 text-xs">
                                        <span class="text-slate-900 block font-semibold">{{ $ta->mahasiswa->nim }}</span>
                                        <span class="text-slate-400">{{ $ta->mahasiswa->program_studi }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-xs max-w-xs truncate" title="{{ $ta->judul }}">{{ $ta->judul }}</td>
                                    <td class="px-4 py-3.5">
                                        @if($ta->status === 'menunggu')
                                            <span class="px-2 py-0.5 bg-amber-50 border border-amber-200 text-amber-800 text-xxs rounded-full font-bold uppercase">Menunggu</span>
                                        @elseif($ta->status === 'disetujui')
                                            <span class="px-2 py-0.5 bg-blue-50 border border-blue-200 text-blue-800 text-xxs rounded-full font-bold uppercase">Disetujui</span>
                                        @elseif($ta->status === 'ditolak')
                                            <span class="px-2 py-0.5 bg-rose-50 border border-rose-200 text-rose-800 text-xxs rounded-full font-bold uppercase">Ditolak</span>
                                        @elseif($ta->status === 'sidang')
                                            <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-200 text-indigo-800 text-xxs rounded-full font-bold uppercase">Sidang</span>
                                        @elseif($ta->status === 'lulus')
                                            <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xxs rounded-full font-bold uppercase">Lulus</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-y-1">
                                        @if($ta->status === 'menunggu')
                                            <!-- Tombol Approval Judul -->
                                            <div class="flex items-center justify-end space-x-1.5">
                                                <form action="{{ route('dosen.ta.approve', $ta->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="setujui">
                                                    <button type="submit" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xxs font-bold transition">Setujui</button>
                                                </form>
                                                <form action="{{ route('dosen.ta.approve', $ta->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="tolak">
                                                    <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-xxs font-bold transition">Tolak</button>
                                                </form>
                                            </div>
                                        @else
                                            <!-- Lihat Logbook Button -->
                                            <button onclick="toggleLogbooks({{ $ta->id }})" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded text-xxs font-bold transition">
                                                Logbook
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- Baris Logbook (Hidden by default) -->
                                <tr id="logbook-row-{{ $ta->id }}" class="hidden bg-slate-50/50">
                                    <td colspan="5" class="px-6 py-4">
                                        <div class="border border-slate-200 rounded-xl bg-white p-4 space-y-4 shadow-inner">
                                            <h4 class="text-xs font-bold uppercase text-slate-400 tracking-wider">Logbook Bimbingan - {{ $ta->mahasiswa->nama_lengkap }}</h4>
                                            
                                            @if($ta->logbooks->count() > 0)
                                                <div class="space-y-3">
                                                    @foreach($ta->logbooks()->orderBy('tanggal_bimbingan', 'desc')->get() as $log)
                                                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg space-y-2">
                                                            <div class="flex items-center justify-between text-xs text-slate-400 font-semibold">
                                                                <span>📅 {{ date('d M Y', strtotime($log->tanggal_bimbingan)) }}</span>
                                                                <span>
                                                                    @if($log->status_validasi)
                                                                        <span class="text-emerald-600">✓ Validated</span>
                                                                    @else
                                                                        <span class="text-amber-600">⌛ Pending Validation</span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <p class="text-xs text-slate-700 font-medium"><strong>Kegiatan:</strong> {{ $log->kegiatan }}</p>
                                                            
                                                            <!-- Form Validasi Dosen -->
                                                            <form action="{{ route('dosen.logbook.validate', $log->id) }}" method="POST" class="pt-2 border-t border-slate-200 flex flex-col md:flex-row items-end md:items-center justify-between gap-2">
                                                                @csrf
                                                                <div class="w-full md:w-3/5">
                                                                    <input type="text" name="catatan_dosen" value="{{ $log->catatan_dosen }}" placeholder="Berikan catatan bimbingan atau revisi..." 
                                                                        class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded text-xs focus:outline-none focus:border-indigo-500">
                                                                </div>
                                                                <div class="flex items-center space-x-2 w-full md:w-auto justify-end">
                                                                    <select name="status_validasi" class="px-2 py-1 bg-white border border-slate-200 rounded text-xs">
                                                                        <option value="1" {{ $log->status_validasi ? 'selected' : '' }}>Validasikan</option>
                                                                        <option value="0" {{ !$log->status_validasi ? 'selected' : '' }}>Tunda</option>
                                                                    </select>
                                                                    <button type="submit" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-semibold transition">
                                                                        Simpan
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-xs text-slate-400 text-center py-2">Belum ada catatan logbook bimbingan.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-slate-400 text-center py-8">Anda belum memiliki mahasiswa bimbingan yang aktif.</p>
            @endif
        </div>

        <!-- DAFTAR SIDANG MENGUJI -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4">
                ⚖️ Jadwal Menguji Sidang Tugas Akhir
            </h3>

            @if($jadwalSidang->count() > 0)
                <div class="space-y-4">
                    @foreach($jadwalSidang as $sid)
                        <div class="p-4 border border-slate-200 rounded-xl space-y-4">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">{{ $sid->tugasAkhir->mahasiswa->nama_lengkap }} (NIM: {{ $sid->tugasAkhir->mahasiswa->nim }})</h4>
                                    <p class="text-xs text-slate-400 mt-1">Judul: <strong>"{{ $sid->tugasAkhir->judul }}"</strong></p>
                                </div>
                                <div class="text-left md:text-right">
                                    <span class="text-xs bg-indigo-50 border border-indigo-100 text-indigo-700 px-2.5 py-0.5 rounded font-bold">
                                        {{ date('d M Y, H:i', strtotime($sid->tanggal_sidang)) }} WIB
                                    </span>
                                    <span class="text-xxs block text-slate-400 mt-1">Ruang: {{ $sid->ruangan }}</span>
                                </div>
                            </div>

                            @if($sid->tugasAkhir->file_laporan)
                                <div class="p-2 bg-slate-50 rounded-lg text-xs flex items-center justify-between">
                                    <span class="text-slate-500">File Draft Laporan Tugas Akhir:</span>
                                    <a href="{{ Storage::url($sid->tugasAkhir->file_laporan) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 font-bold underline">Lihat PDF</a>
                                </div>
                            @endif

                            @if($sid->status_kelulusan === 'menunggu')
                                <!-- Form Penilaian Sidang -->
                                <hr class="border-slate-100">
                                <form action="{{ route('dosen.sidang.score', $sid->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Nilai Angka (0-100)</label>
                                            <input type="number" name="nilai_angka" min="0" max="100" required placeholder="Contoh: 85" 
                                                class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Keputusan Kelulusan</label>
                                            <select name="status_kelulusan" required class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none">
                                                <option value="lulus">LULUS</option>
                                                <option value="tidak_lulus">TIDAK LULUS</option>
                                            </select>
                                        </div>
                                        <div class="flex items-end">
                                            <button type="submit" class="w-full py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
                                                Simpan & Nilai
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Catatan Penilaian (Opsional)</label>
                                        <textarea name="catatan" rows="1" placeholder="Masukkan masukan revisi atau catatan sidang..." 
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-indigo-500"></textarea>
                                    </div>
                                </form>
                            @else
                                <div class="bg-slate-50 p-3 rounded-lg flex items-center justify-between text-xs border border-slate-100">
                                    <div>
                                        <span class="text-slate-400">Nilai:</span> 
                                        <strong class="text-slate-800">{{ $sid->nilai_angka }} ({{ $sid->nilai_huruf }})</strong> | 
                                        <span class="text-slate-400">Hasil:</span> 
                                        <strong class="{{ $sid->status_kelulusan === 'lulus' ? 'text-emerald-600' : 'text-rose-600' }} uppercase">{{ $sid->status_kelulusan }}</strong>
                                    </div>
                                    <a href="{{ route('sidang.print_berita_acara', $sid->id) }}" target="_blank" class="px-2 py-1 bg-white border border-slate-200 text-slate-700 text-xxs font-bold rounded hover:bg-slate-50 shadow-sm">
                                        Cetak Berita Acara
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400 text-center py-6">Belum ada tugas menguji sidang dalam waktu dekat.</p>
            @endif
        </div>

    </div>

    <!-- Kolom Kanan / Sidebar Dosen -->
    <div class="space-y-6">

        <!-- Dosen Profile & Analytics -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Statistik Bimbingan</h4>
            <div class="w-full aspect-square">
                <canvas id="dosenTaChart"></canvas>
            </div>
        </div>

        <!-- Pengumuman Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengumuman Kampus</h4>
            
            @if($announcements->count() > 0)
                <div class="divide-y divide-slate-100 max-h-[300px] overflow-y-auto pr-1">
                    @foreach($announcements as $announcement)
                        <div class="py-3 first:pt-0 last:pb-0 space-y-1">
                            <h5 class="text-xs font-bold text-slate-800">{{ $announcement->judul }}</h5>
                            <p class="text-xxs text-slate-400">{{ $announcement->created_at->diffForHumans() }}</p>
                            <p class="text-xs text-slate-500 font-normal line-clamp-2 leading-relaxed">{{ $announcement->konten }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 text-center py-2">Belum ada pengumuman.</p>
            @endif
        </div>

    </div>

</div>

<script>
    // Toggle baris logbook mahasiswa
    function toggleLogbooks(id) {
        const row = document.getElementById('logbook-row-' + id);
        row.classList.toggle('hidden');
    }

    // Chart.js doughnut rendering
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('dosenTaChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Disetujui', 'Ditolak', 'Sidang', 'Lulus'],
                datasets: [{
                    data: [
                        {{ $chartData['menunggu'] }},
                        {{ $chartData['disetujui'] }},
                        {{ $chartData['ditolak'] }},
                        {{ $chartData['sidang'] }},
                        {{ $chartData['lulus'] }}
                    ],
                    backgroundColor: ['#fdba74', '#93c5fd', '#fca5a5', '#c4b5fd', '#34d399'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
