<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TugasAkhir;

class TugasAkhirPolicy
{
    /**
     * Tentukan apakah user dapat melihat detail Tugas Akhir.
     */
    public function view(User $user, TugasAkhir $tugasAkhir): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDosen()) {
            return $user->dosen->id === $tugasAkhir->dosen_id || $tugasAkhir->sidang?->dosen_penguji_id === $user->dosen->id;
        }

        if ($user->isMahasiswa() && $user->mahasiswa) {
            return $user->mahasiswa->id === $tugasAkhir->mahasiswa_id;
        }

        return false;
    }

    /**
     * Tentukan apakah user dapat memperbarui Tugas Akhir.
     */
    public function update(User $user, TugasAkhir $tugasAkhir): bool
    {
        if ($user->isMahasiswa() && $user->mahasiswa) {
            return $user->mahasiswa->id === $tugasAkhir->mahasiswa_id && in_array($tugasAkhir->status, ['menunggu', 'ditolak']);
        }

        return false;
    }

    /**
     * Tentukan apakah user dapat menyetujui Tugas Akhir.
     */
    public function approve(User $user, TugasAkhir $tugasAkhir): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDosen() && $user->dosen) {
            return $user->dosen->id === $tugasAkhir->dosen_id;
        }

        return false;
    }
}
