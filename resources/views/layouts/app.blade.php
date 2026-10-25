<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMTA') - Sistem Informasi Manajemen Tugas Akhir</title>
    
    <!-- Early Theme Restoration (Cegah Flicker Putih) -->
    <script>
        (function() {
            const isDark = localStorage.getItem('theme') === 'dark';
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Outfit', 'Instrument Sans', sans-serif;
            background-color: #FAF8F5;
        }
        .sidebar-active {
            background-color: #D2E8DD !important;
            border-left: 4px solid #81C784 !important;
            color: #2E7D32 !important;
        }

        /* Overrides Tema Gelap (Dark Mode) */
        .dark body {
            background-color: #0b0f19 !important;
            color: #e2e8f0 !important;
        }
        .dark #mainContent header {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
            color: #ffffff !important;
        }
        .dark #mainContent header h1 {
            color: #ffffff !important;
        }
        .dark .bg-white {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
        }
        .dark .border-slate-200 {
            border-color: #1e293b !important;
        }
        .dark .text-slate-800 {
            color: #e2e8f0 !important;
        }
        .dark .text-slate-900 {
            color: #ffffff !important;
        }
        .dark .text-slate-500 {
            color: #94a3b8 !important;
        }
        .dark .text-slate-600 {
            color: #cbd5e1 !important;
        }
        .dark .bg-slate-50 {
            background-color: #0b0f19 !important;
        }
        .dark .bg-slate-100 {
            background-color: #1e293b !important;
        }
        .dark .hover\:bg-slate-50:hover {
            background-color: #1e293b !important;
        }
        .dark .hover\:bg-slate-100:hover {
            background-color: #334155 !important;
        }
        .dark .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #1e293b !important;
        }
        .dark input, .dark select, .dark textarea {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #ffffff !important;
        }
        .dark input::placeholder {
            color: #64748b !important;
        }
        .dark .sidebar-active {
            background-color: rgba(210, 232, 221, 0.15) !important;
            border-left: 4px solid #81C784 !important;
            color: #A5D6A7 !important;
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden text-slate-800">

    <!-- Sidebar Overlay (Hanya untuk mobile ketika sidebar aktif) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed md:relative top-0 left-0 z-30 h-screen w-64 bg-[#FAF6F0] dark:bg-slate-900 text-slate-700 dark:text-white flex flex-col shrink-0 shadow-lg border-r border-[#EBE3D5] dark:border-slate-800 transition-all duration-300 -translate-x-full md:translate-x-0 md:overflow-y-auto">
            <!-- Logo / Brand -->
            <div class="p-6 bg-[#F3ECE0] dark:bg-slate-950 border-b border-[#E6DEC9]/40 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">SIMTA</span>
                    <span class="text-xs px-2 py-0.5 bg-indigo-500 rounded-full font-semibold uppercase tracking-wider">PNP</span>
                </a>
            </div>

            <!-- User Information -->
            <div class="px-6 py-4 bg-[#FAF6F0] dark:bg-slate-900 border-b border-[#EBE3D5] dark:border-slate-800 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-[#589A7D] flex items-center justify-center font-bold text-white text-lg shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="font-semibold text-sm truncate text-slate-800 dark:text-white">{{ Auth::user()->name }}</h4>
                    <span class="text-xs text-[#2E7D32] dark:text-[#A5D6A7] capitalize px-2 py-0.5 bg-[#E8F5E9] dark:bg-slate-850 rounded border border-[#C8E6C9]/40 inline-block mt-0.5 font-medium">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-slate-650 dark:text-slate-300 hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white rounded-lg transition {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                @if(Auth::user()->isMahasiswa())
                    @php
                        $userTA = Auth::user()->mahasiswa->tugasAkhir;
                    @endphp
                    @if(!$userTA || in_array($userTA->status, ['menunggu', 'ditolak']))
                        <a href="{{ route('mahasiswa.ta.create') }}" class="flex items-center px-4 py-3 text-slate-650 dark:text-slate-300 hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white rounded-lg transition {{ request()->routeIs('mahasiswa.ta.create') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="text-sm font-medium">Pengajuan Judul TA</span>
                        </a>
                    @endif

                    @if($userTA && in_array($userTA->status, ['disetujui', 'sidang', 'lulus']))
                        <a href="{{ route('mahasiswa.logbook.index') }}" class="flex items-center px-4 py-3 text-slate-650 dark:text-slate-300 hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white rounded-lg transition {{ request()->routeIs('mahasiswa.logbook.index') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span class="text-sm font-medium">Logbook Bimbingan</span>
                        </a>

                        <a href="{{ route('mahasiswa.laporan.index') }}" class="flex items-center px-4 py-3 text-slate-650 dark:text-slate-300 hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white rounded-lg transition {{ request()->routeIs('mahasiswa.laporan.index') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H9A3.75 3.75 0 0 0 5.25 6v12A3.75 3.75 0 0 0 9 21.75h1.5a1.125 1.125 0 0 0 1.125-1.125v-1.5a3.375 3.375 0 0 1 3.375-3.375h-1.5M12 18.75v-1.125m0-4.875V12" />
                            </svg>
                            <span class="text-sm font-medium">Laporan & Sidang</span>
                        </a>
                    @endif

                    @if($userTA)
                        <a href="{{ route('mahasiswa.riwayat.index') }}" class="flex items-center px-4 py-3 text-slate-650 dark:text-slate-300 hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white rounded-lg transition {{ request()->routeIs('mahasiswa.riwayat.index') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="text-sm font-medium">Riwayat Kegiatan</span>
                        </a>
                    @endif
                @endif

                @if(Auth::user()->isAdmin())
                    <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Manajemen Data</div>
                    
                    <a href="{{ route('admin.mahasiswa.index') }}" class="flex items-center px-4 py-3 text-slate-650 dark:text-slate-300 hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white rounded-lg transition {{ request()->routeIs('admin.mahasiswa.*') ? 'sidebar-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84a50.58 50.58 0 0 0-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M12 2.25V4.5m0 12.895V21.75M3.057 15.75c-.137.659-.25 1.35-.342 2.052A3 3 0 0 0 5.67 21.151l.732-.086a3.003 3.003 0 0 0 2.22-1.644M20.943 15.75c.137.659.25 1.35.342 2.052a3 3 0 0 1-2.955 3.349l-.732-.086a3.003 3.003 0 0 1-2.22-1.644" />
                        </svg>
                        <span class="text-sm font-medium">Data Mahasiswa</span>
                    </a>
                    
                    <a href="{{ route('admin.dosen.index') }}" class="flex items-center px-4 py-3 text-slate-650 dark:text-slate-300 hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white rounded-lg transition {{ request()->routeIs('admin.dosen.*') ? 'sidebar-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        <span class="text-sm font-medium">Data Dosen</span>
                    </a>

                    <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Laporan</div>
                    
                    <a href="{{ route('admin.export_csv') }}" class="flex items-center px-4 py-3 text-slate-650 dark:text-slate-300 hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span class="text-sm font-medium">Ekspor CSV</span>
                    </a>
                @endif
            </nav>

            <!-- Footer / Logout -->
            <div class="p-4 pb-8 bg-[#F3ECE0] dark:bg-slate-950 border-t border-[#E6DEC9]/40 dark:border-slate-800">
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 border border-red-500/80 text-red-500 hover:bg-red-600/10 hover:text-red-450 bg-transparent rounded-lg transition font-medium text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main id="mainContent" class="flex-1 flex flex-col min-w-0 min-h-screen">
            <!-- Top Bar -->
            <header class="bg-[#FAF6F0] dark:bg-slate-900 border-b border-[#EBE3D5] dark:border-slate-800 px-6 py-4 flex items-center justify-between shrink-0 shadow-sm">
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="toggleSidebar()" class="p-1.5 rounded-lg hover:bg-[#EBE3D5]/40 dark:hover:bg-slate-800 text-slate-500 transition focus:outline-none" title="Toggle Sidebar">
                        <span class="text-xl">☰</span>
                    </button>
                    <h1 class="text-xl font-semibold text-slate-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Toggle Switch Slider (Light / Dark Mode Switch) -->
                    <button type="button" onclick="toggleTheme()" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-slate-200 dark:bg-[#589A7D] transition-colors duration-200 ease-in-out focus:outline-none" title="Ubah Tema">
                        <span class="sr-only">Ubah Tema</span>
                        <span aria-hidden="true" class="translate-x-0 dark:translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out flex items-center justify-center">
                            <span class="text-[9px] dark:hidden">☀️</span>
                            <span class="text-[9px] hidden dark:inline">🌙</span>
                        </span>
                    </button>
                    <!-- Date Badge -->
                    <div class="flex items-center space-x-1.5 text-xs text-slate-500 dark:text-slate-400 font-semibold bg-white dark:bg-slate-850 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm">
                        <span>📅</span>
                        <span>{{ date('d M Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 p-6 overflow-y-auto">
                <!-- Toast Alerts -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-55 border border-emerald-200 text-emerald-800 rounded-xl flex items-center shadow-sm">
                        <span class="text-2xl mr-3">🎉</span>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-55 border border-rose-200 text-rose-800 rounded-xl flex items-center shadow-sm">
                        <span class="text-2xl mr-3">⚠️</span>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-55 border border-rose-200 text-rose-800 rounded-xl shadow-sm">
                        <div class="flex items-center mb-2">
                            <span class="text-2xl mr-3">⚠️</span>
                            <span class="text-sm font-bold">Harap koreksi kesalahan berikut:</span>
                        </div>
                        <ul class="list-disc pl-9 text-xs space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Toggle & Theme Scripts -->
    <script>
        // Mengembalikan status sidebar saat pertama kali dimuat
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById('sidebar');
            
            if (window.innerWidth >= 768) {
                const isHidden = localStorage.getItem('sidebar_hidden') === 'true';
                if (isHidden) {
                    sidebar.classList.add('md:hidden');
                    sidebar.classList.add('-translate-x-full');
                } else {
                    sidebar.classList.remove('md:hidden');
                    sidebar.classList.remove('-translate-x-full');
                }
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('md:hidden');
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth >= 768) {
                if (sidebar.classList.contains('md:hidden')) {
                    sidebar.classList.remove('md:hidden');
                    sidebar.classList.remove('-translate-x-full');
                    localStorage.setItem('sidebar_hidden', 'false');
                } else {
                    sidebar.classList.add('md:hidden');
                    sidebar.classList.add('-translate-x-full');
                    localStorage.setItem('sidebar_hidden', 'true');
                }
            } else {
                const isHidden = sidebar.classList.contains('-translate-x-full');
                if (isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }
            }
        }

        // Fungsi mengubah tema (Light/Dark Mode)
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>

</body>
</html>
