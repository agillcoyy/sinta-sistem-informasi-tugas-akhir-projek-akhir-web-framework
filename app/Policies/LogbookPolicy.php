<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Logbook;

class LogbookPolicy
{
    /**
     * Apakah mahasiswa dapat mengubah atau menghapus logbook.
     */
    public function modify(User $user, Logbook $logbook): bool
    {
        if ($user->isMahasiswa() && $user->mahasiswa) {
            return $user->mahasiswa->id === $logbook->tugasAkhir->mahasiswa_id && !$logbook->status_validasi;
        }

        return false;
    }

    /**
     * Apakah dosen pembimbing dapat memvalidasi logbook.
     */
    public function validate(User $user, Logbook $logbook): bool
    {
        if ($user->isDosen() && $user->dosen) {
            return $user->dosen->id === $logbook->tugasAkhir->dosen_id;
        }

        return false;
    }
}
