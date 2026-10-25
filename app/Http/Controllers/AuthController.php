<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'captcha.required' => 'Captcha wajib diisi.',
            'captcha.captcha' => 'Kode captcha salah.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withInput()->withErrors(['email' => 'Kredensial yang diberikan tidak cocok dengan data kami.']);
    }

    /**
     * Tampilkan Halaman Register (Mahasiswa saja)
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Proses Register Mahasiswa
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim|max:20',
            'email' => 'required|string|email|unique:users,email|max:255|ends_with:@pnp.ac.id,@student.pnp.ac.id',
            'program_studi' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'captcha' => 'required|captcha',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.ends_with' => 'Registrasi ditolak. Anda wajib menggunakan email resmi institusi Politeknik Negeri Padang (@pnp.ac.id atau @student.pnp.ac.id).',
            'program_studi.required' => 'Program studi wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'captcha.required' => 'Captcha wajib diisi.',
            'captcha.captcha' => 'Kode captcha salah.',
        ]);

        // Mulai transaksi database
        \DB::beginTransaction();

        try {
            // 1. Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
            ]);

            // 2. Buat Mahasiswa
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'nama_lengkap' => $request->name,
                'program_studi' => $request->program_studi,
            ]);

            \DB::commit();

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil. Selamat datang!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
