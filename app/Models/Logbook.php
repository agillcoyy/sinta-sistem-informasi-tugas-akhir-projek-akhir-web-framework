<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = ['tugas_akhir_id', 'tanggal_bimbingan', 'kegiatan', 'catatan_dosen', 'status_validasi'];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }
}
