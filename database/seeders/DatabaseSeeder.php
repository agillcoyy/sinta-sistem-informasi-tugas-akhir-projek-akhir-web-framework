<?php

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@pnp.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // 2. Buat Akun Dosen
        $userDosen = User::create([
            'name' => 'Bapak Dosen Penguji, M.Kom',
            'email' => 'dosen@pnp.ac.id',
            'password' => bcrypt('password'),
            'role' => 'dosen'
        ]);

        Dosen::create([
            'user_id' => $userDosen->id,
            'nip' => '198001012005011003', // Menggunakan NIP yang benar
            'nama_lengkap' => 'Bapak Dosen Penguji, M.Kom',
            'bidang_keahlian' => 'Rekayasa Perangkat Lunak'
        ]);

        // 3. Buat Akun Mahasiswa (Otomatis)
        $daftarMahasiswa = [
            'Zahran', 'Adinda', 'Ririn', 'Aura', 'Fathya', 'Adil', 'Isa', 
            'Tama', 'Sinta', 'Ibra', 'Citra', 'Halen', 'Keysa', 'Cia', 
            'Via', 'Intan', 'Mila', 'Muti', 'Fauzio', 'Nyoleh', 'Halim', 
            'Reza', 'Irwan', 'Wulan', 'Revan'
        ];

        $programStudis = ['D3 Teknik Komputer', 'D3 Manajemen Informatika', 'D4 Teknologi Rekayasa Perangkat Lunak'];

        foreach ($daftarMahasiswa as $index => $nama) {
            $userMhs = User::create([
                'name' => $nama,
                'email' => strtolower($nama) . '@pnp.ac.id',
                'password' => bcrypt('password123'),
                'role' => 'mahasiswa'
            ]);

            Mahasiswa::create([
                'user_id' => $userMhs->id,
                'nim' => '24010920' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'nama_lengkap' => $nama,
                'program_studi' => $programStudis[$index % 3]
            ]);
        }
    }
}
