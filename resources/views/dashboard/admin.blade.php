@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">

    <!-- Metrik Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Mahasiswa -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <span class="text-3xl bg-indigo-50 p-3 rounded-xl">🎓</span>
            <div>
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Total Mahasiswa</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalMahasiswa }}</span>
            </div>
        </div>

        <!-- Dosen -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <span class="text-3xl bg-blue-50 p-3 rounded-xl">👨‍🏫</span>
            <div>
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Total Dosen</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalDosen }}</span>
            </div>
        </div>

        <!-- Tugas Akhir -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <span class="text-3xl bg-amber-50 p-3 rounded-xl">📚</span>
            <div>
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Judul Diajukan</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalTugasAkhir }}</span>
            </div>
        </div>

        <!-- Sidang -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <span class="text-3xl bg-emerald-50 p-3 rounded-xl">⚖️</span>
            <div>
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Total Sidang</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalSidang }}</span>
            </div>
        </div>
    </div>

    <!-- Grafik Sebaran TA & Posting Pengumuman -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Sebaran Status Tugas Akhir</h4>
            <div class="w-full h-64">
                <canvas id="adminTaChart"></canvas>
            </div>
        </div>

        <!-- Post Pengumuman -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center pb-2 border-b border-slate-100">
                <span class="mr-2">📢</span> Kirim Pengumuman Baru Kampus
            </h3>
            <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Judul Pengumuman</label>
                        <input type="text" name="judul" required placeholder="Contoh: Jadwal Ujian Tugas Akhir Semester Genap" 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Lampiran File (Opsional)</label>
                        <input type="file" name="file_lampiran" accept=".pdf,.png,.jpg,.zip" 
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xxs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Konten Pengumuman</label>
                    <textarea name="konten" rows="3" required placeholder="Tuliskan detail info yang ingin disampaikan kepada dosen dan mahasiswa..." 
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-indigo-500"></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">
                    Publikasikan Pengumuman
                </button>
            </form>
        </div>
    </div>

    <!-- Plotting / Penjadwalan Sidang -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- List Antrean Sidang & Jadwalkan (Kiri/Tengah) -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                <span class="mr-2">⏱️</span> Antrean Jadwal Sidang Tugas Akhir
            </h3>
            
            @php
                $antreanSidang = \App\Models\TugasAkhir::where('status', 'sidang')
                    ->whereDoesntHave('sidang')
                    ->with('mahasiswa')
                    ->get();
            @endphp

            @if($antreanSidang->count() > 0)
                <div class="space-y-4">
                    @foreach($antreanSidang as $ta)
                        <div class="p-4 bg-slate-55 border border-slate-200 rounded-xl space-y-4">
                            <div class="text-xs">
                                <span class="font-bold text-slate-800 text-sm">{{ $ta->mahasiswa->nama_lengkap }} ({{ $ta->mahasiswa->nim }})</span>
                                <p class="text-slate-500 mt-1">Judul: <strong>"{{ $ta->judul }}"</strong></p>
                                <p class="text-slate-400">Pembimbing: {{ $ta->dosen->nama_lengkap }}</p>
                            </div>
                            
                            <!-- Form Penjadwalan -->
                            <form action="{{ route('admin.sidang.schedule') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-3 border-t border-slate-100">
                                @csrf
                                <input type="hidden" name="tugas_akhir_id" value="{{ $ta->id }}">
                                
                                <div>
                                    <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Dosen Penguji</label>
                                    <select name="dosen_penguji_id" required class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded text-xs">
                                        <option value="">Pilih Penguji</option>
                                        @foreach(\App\Models\Dosen::where('id', '!=', $ta->dosen_id)->get() as $ds)
                                            <option value="{{ $ds->id }}">{{ $ds->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Tanggal & Waktu</label>
                                    <input type="datetime-local" name="tanggal_sidang" required 
                                        class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-xxs font-bold text-slate-400 uppercase mb-1">Ruangan / Kelas</label>
                                    <input type="text" name="ruangan" required placeholder="Contoh: Ruang Rapat Lt. 2" 
                                        class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded text-xs">
                                </div>
                                <div class="md:col-span-3 flex justify-end">
                                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-bold transition">
                                        Simpan Jadwal Sidang
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 text-center py-4">Tidak ada mahasiswa yang sedang mengantre penjadwalan sidang.</p>
            @endif
        </div>

        <!-- Kelola Pengumuman (Kanan) -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                <span class="mr-2">📋</span> Riwayat Pengumuman
            </h3>
            
            @if($announcements->count() > 0)
                <div class="divide-y divide-slate-100 max-h-[350px] overflow-y-auto pr-1">
                    @foreach($announcements as $ann)
                        <div class="py-3 first:pt-0 last:pb-0 space-y-2">
                            <div class="flex items-start justify-between">
                                <h5 class="text-xs font-bold text-slate-800 leading-snug">{{ $ann->judul }}</h5>
                                <form action="{{ route('pengumuman.destroy', $ann->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-600 text-xxs">🗑️</button>
                                </form>
                            </div>
                            <p class="text-xxs text-slate-400">Diposting: {{ $ann->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 text-center py-2">Belum ada riwayat pengumuman.</p>
            @endif
        </div>

    </div>

    <!-- Daftar Pengajuan TA Menunggu Approval Admin -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center mb-4">
            <span class="mr-2">🔔</span> Pengajuan Judul Tugas Akhir Baru (Menunggu Approval)
        </h3>

        @php
            $pendingTA = \App\Models\TugasAkhir::where('status', 'menunggu')->with(['mahasiswa', 'dosen'])->get();
        @endphp

        @if($pendingTA->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xxs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Mahasiswa</th>
                            <th class="px-4 py-3">NIM</th>
                            <th class="px-4 py-3">Judul Diajukan</th>
                            <th class="px-4 py-3">Rencana Pembimbing</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($pendingTA as $ta)
                            <tr>
                                <td class="px-4 py-3.5">{{ $ta->mahasiswa->nama_lengkap }}</td>
                                <td class="px-4 py-3.5 text-slate-500">{{ $ta->mahasiswa->nim }}</td>
                                <td class="px-4 py-3.5 text-xs max-w-xs truncate" title="{{ $ta->judul }}">{{ $ta->judul }}</td>
                                <td class="px-4 py-3.5 text-xs">{{ $ta->dosen->nama_lengkap }}</td>
                                <td class="px-4 py-3.5 text-right flex justify-end space-x-1.5">
                                    <form action="{{ route('admin.ta.approve', $ta->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="setujui">
                                        <button type="submit" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xxs font-bold transition">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.ta.approve', $ta->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="tolak">
                                        <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-xxs font-bold transition">Tolak</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-slate-400 text-center py-6">Tidak ada pengajuan judul TA baru yang memerlukan verifikasi.</p>
        @endif
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('adminTaChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
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
