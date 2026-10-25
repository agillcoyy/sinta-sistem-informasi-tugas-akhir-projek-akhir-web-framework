@extends('layouts.app')

@section('title', 'Pengajuan Judul Tugas Akhir')
@section('page-title', 'Pengajuan Judul Tugas Akhir')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header info -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center">
            <span class="mr-2.5">📝</span> Pengajuan Judul Tugas Akhir
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed font-semibold">
            Silakan lengkapi formulir di bawah ini untuk mengajukan judul Tugas Akhir Anda. Usulan Anda akan diperiksa oleh Dosen Pembimbing yang dipilih dan disahkan oleh Koordinator TA.
        </p>
    </div>

    @if(!$tugasAkhir)
        <!-- Form Pengajuan Baru -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
            <form action="{{ route('mahasiswa.ta.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Judul Tugas Akhir</label>
                    <input type="text" name="judul" required placeholder="Contoh: Rancang Bangun Sistem Bimbingan TA Online" 
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white placeholder-slate-450 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Deskripsi / Abstrak Singkat</label>
                    <textarea name="deskripsi" rows="6" placeholder="Jelaskan secara singkat mengenai sistem yang akan Anda bangun..." 
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white placeholder-slate-450 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Dosen Pembimbing Utama</label>
                    <select name="dosen_id" required 
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all">
                        <option value="">Pilih Dosen Pembimbing</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama_lengkap }} ({{ $dosen->bidang_keahlian }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl text-sm font-bold transition shadow-lg shadow-indigo-100 dark:shadow-none hover:-translate-y-0.5">
                        Kirim Pengajuan Judul
                    </button>
                </div>
            </form>
        </div>
    @elseif($tugasAkhir->status === 'ditolak' || $tugasAkhir->status === 'menunggu')
        <!-- Form Edit / Perbaikan (Jika Ditolak atau Sedang Menunggu) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
            @if($tugasAkhir->status === 'ditolak')
                <div class="p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-rose-800 dark:text-rose-455 flex items-start">
                    <span class="text-2xl mr-3">❌</span>
                    <div>
                        <h4 class="font-bold text-sm">Status: Pengajuan Ditolak</h4>
                        <p class="text-xs mt-1 text-rose-750 dark:text-rose-450">Anda dapat mengubah dan mengirimkan ulang detail judul di bawah ini.</p>
                    </div>
                </div>
            @else
                <div class="p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl text-amber-800 dark:text-amber-400 flex items-start">
                    <span class="text-2xl mr-3">⏳</span>
                    <div>
                        <h4 class="font-bold text-sm">Status: Menunggu Review</h4>
                        <p class="text-xs mt-1 text-amber-750 dark:text-amber-550">Pengajuan Anda sedang ditinjau. Anda masih dapat memperbarui detail pengajuan jika diperlukan.</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('mahasiswa.ta.update', $tugasAkhir->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Judul Tugas Akhir</label>
                    <input type="text" name="judul" value="{{ old('judul', $tugasAkhir->judul) }}" required 
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Deskripsi / Abstrak Singkat</label>
                    <textarea name="deskripsi" rows="6" 
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white text-sm focus:outline-none focus:border-indigo-500">{{ old('deskripsi', $tugasAkhir->deskripsi) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Pilih Dosen Pembimbing</label>
                    <select name="dosen_id" required 
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white text-sm focus:outline-none focus:border-indigo-500">
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ $tugasAkhir->dosen_id == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nama_lengkap }} ({{ $dosen->bidang_keahlian }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl text-sm font-bold transition shadow-lg hover:-translate-y-0.5">
                        Perbarui & Kirim Ulang
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>
@endsection
