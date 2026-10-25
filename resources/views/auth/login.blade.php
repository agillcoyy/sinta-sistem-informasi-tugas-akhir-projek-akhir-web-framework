<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMTA PNP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        
        <!-- Background glows -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-blue-500 rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500 rounded-full blur-[100px] opacity-20 pointer-events-none"></div>

        <div class="relative">
            <!-- Header Logo & Title -->
            <div class="text-center mb-8">
                <span class="inline-flex px-4 py-1.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-full text-xs font-semibold uppercase tracking-wider mb-3">Tugas Akhir PNP</span>
                <h1 class="text-3xl font-extrabold text-white">SIMTA <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">PNP</span></h1>
                <p class="text-slate-400 text-sm mt-2">Masuk ke dashboard manajemen tugas akhir</p>
            </div>

            <!-- Toast Alerts -->
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-xs flex items-center">
                    <span class="mr-2">🎉</span> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-xs">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@pnp.ac.id" 
                        class="w-full px-4 py-3 bg-slate-950/50 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-sm">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required placeholder="••••••••" 
                            class="w-full px-4 py-3 bg-slate-950/50 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-sm pr-12">
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition focus:outline-none select-none" title="Tampilkan/Sembunyikan password">
                            <!-- Eye Open -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-open">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <!-- Eye Closed -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-closed hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.815 7.815 3 3m-3-3a3 3 0 0 0-4.243-4.243m0 0-3.65-3.65m0 0a3 3 0 0 0-4.243 4.243m0 0 3.65 3.65" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Captcha -->
                <div class="p-4 bg-slate-950/60 border border-slate-800 rounded-2xl">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Verifikasi Keamanan</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden flex items-center justify-center p-1.5 grow">
                            <img src="{{ captcha_src('flat') }}" alt="captcha" class="h-10 rounded-lg cursor-pointer hover:opacity-95" id="captcha-img" onclick="refreshCaptcha()" title="Klik untuk segarkan">
                        </div>
                        <button type="button" onclick="refreshCaptcha()" class="p-2.5 bg-slate-900 border border-slate-800 rounded-xl text-slate-400 hover:text-white hover:border-slate-700 transition" title="Segarkan captcha">
                            
                        </button>
                        <input type="text" name="captcha" required placeholder="Kode" 
                            class="w-24 px-3 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-center focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-sm font-bold uppercase">
                    </div>
                    <p class="text-slate-500 text-xxs mt-2">Masukkan kode huruf/angka yang tampil pada gambar di atas.</p>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-400">
                        <input type="checkbox" name="remember" class="mr-2 rounded border-slate-850 bg-slate-950/50 text-indigo-600 focus:ring-indigo-500"> Ingat saya
                    </label>
                </div>

                <button type="submit" 
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-indigo-500/20 transition duration-300 text-sm hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>
            </form>


        </div>
    </div>

    <script>
        function refreshCaptcha() {
            const img = document.getElementById('captcha-img');
            img.src = '{{ captcha_src("flat") }}' + '?' + Math.random();
        }

        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const openIcon = button.querySelector('.eye-open');
            const closedIcon = button.querySelector('.eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                openIcon.classList.add('hidden');
                closedIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                openIcon.classList.remove('hidden');
                closedIcon.classList.add('hidden');
            }
        }
    </script>

</body>
</html>
