<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMTA PNP - Repositori Tugas Akhir & Bimbingan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800">

    <!-- Top Navigation Bar -->
    <nav class="bg-slate-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">SIMTA</span>
                    <span class="text-xs px-2 py-0.5 bg-indigo-500 rounded-full font-semibold uppercase tracking-wider">PNP</span>
                </div>
                
                <!-- Action Button -->
                <div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-lg hover:shadow-indigo-500/25 transition">
                            Kembali ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-lg hover:shadow-indigo-500/25 transition">
                            Masuk Ke Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white py-16 px-4 relative overflow-hidden text-center">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-blue-500 rounded-full blur-[120px] opacity-20 pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-500 rounded-full blur-[120px] opacity-20 pointer-events-none"></div>
        
        <div class="max-w-3xl mx-auto relative z-10 space-y-4">
            <span class="inline-flex px-3 py-1 bg-white/15 text-indigo-300 rounded-full text-xs font-semibold uppercase tracking-wider">Dashboard Publik</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Repositori Tugas Akhir & Bimbingan Online</h1>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">Selamat datang di SIMTA Politeknik Negeri Padang. Cari tahu judul penelitian, status kelulusan, dan pengumuman terbaru di sini.</p>
            
            <!-- Search Bar -->
            <form action="{{ route('home') }}" method="GET" class="pt-4 max-w-xl mx-auto flex items-center bg-white border border-slate-200 rounded-2xl overflow-hidden p-1 shadow-xl">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul TA, nama mahasiswa, NIM atau pembimbing..." 
                    class="w-full px-4 py-2 text-slate-800 focus:outline-none placeholder-slate-400 text-sm">
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shrink-0">
                    Cari
                </button>
            </form>
        </div>
    </header>

    <!-- Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Statistik Publik -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm text-center">
                <span class="text-3xl block mb-2">🎓</span>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider block">Total Mahasiswa</span>
                <span class="text-2xl font-bold text-slate-800">{{ $stats['mahasiswa'] }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm text-center">
                <span class="text-3xl block mb-2">👨‍🏫</span>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider block">Total Dosen</span>
                <span class="text-2xl font-bold text-slate-800">{{ $stats['dosen'] }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm text-center">
                <span class="text-3xl block mb-2">📚</span>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider block">Judul Terdaftar</span>
                <span class="text-2xl font-bold text-slate-800">{{ $stats['ta'] }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm text-center">
                <span class="text-3xl block mb-2">🎉</span>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider block">Lulus Sidang</span>
                <span class="text-2xl font-bold text-slate-800 text-emerald-600">{{ $stats['lulus'] }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Daftar TA (Kiri/Tengah) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6 flex items-center">
                        <span class="mr-2">📂</span> Repositori Penelitian & Tugas Akhir
                    </h3>

                    @if($tugasAkhirs->count() > 0)
                        <div class="space-y-6">
                            @foreach($tugasAkhirs as $ta)
                                <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-3 relative overflow-hidden transition hover:shadow-md">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-bold text-xs">{{ $ta->mahasiswa->nim }} | Program Studi: {{ $ta->mahasiswa->program_studi }}</span>
                                        @if($ta->status === 'lulus')
                                            <span class="px-2.5 py-0.5 bg-emerald-100 border border-emerald-200 text-emerald-800 text-xxs font-bold uppercase rounded-full">LULUS</span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-blue-100 border border-blue-200 text-blue-800 text-xxs font-bold uppercase rounded-full">AKTIF/BIMBINGAN</span>
                                        @endif
                                    </div>
                                    <h4 class="font-extrabold text-slate-800 text-base leading-snug">"{{ $ta->judul }}"</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed font-normal line-clamp-3">{{ $ta->deskripsi }}</p>
                                    <hr class="border-slate-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 text-xxs text-slate-400 font-semibold uppercase tracking-wider">
                                        <span>Peneliti: {{ $ta->mahasiswa->nama_lengkap }}</span>
                                        <span>Pembimbing: {{ $ta->dosen->nama_lengkap }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Pagination -->
                            <div class="pt-4 border-t border-slate-100">
                                {{ $tugasAkhirs->links() }}
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-400 text-center py-10">Tidak ditemukan data Tugas Akhir untuk pencarian Anda.</p>
                    @endif
                </div>
            </div>

            <!-- Kolom Pengumuman Terbaru (Kanan) -->
            <div class="space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 flex items-center">
                        <span class="mr-2">📢</span> Pengumuman Kampus Terbaru
                    </h3>
                    
                    @if($announcements->count() > 0)
                        <div class="divide-y divide-slate-100 max-h-[500px] overflow-y-auto pr-1">
                            @foreach($announcements as $announcement)
                                <div class="py-4 first:pt-0 last:pb-0 space-y-1.5">
                                    <h4 class="text-sm font-bold text-slate-850 leading-snug">{{ $announcement->judul }}</h4>
                                    <p class="text-xs text-slate-550 font-normal line-clamp-3 leading-relaxed">{{ $announcement->konten }}</p>
                                    
                                    <div class="flex items-center justify-between text-xxs text-slate-400 pt-1">
                                        <span>Oleh: {{ $announcement->user->name }}</span>
                                        <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                    </div>

                                    @if($announcement->file_lampiran)
                                        <a href="{{ Storage::url($announcement->file_lampiran) }}" target="_blank" 
                                            class="inline-block text-xxs font-bold text-indigo-600 hover:text-indigo-700 underline mt-1">
                                            📎 Unduh Lampiran
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada pengumuman terbaru saat ini.</p>
                    @endif
                </div>
            </div>

        </div>

    </main>

</body>
</html>
