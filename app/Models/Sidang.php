<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    protected $fillable = [
        'tugas_akhir_id',
        'dosen_penguji_id',
        'tanggal_sidang',
        'ruangan',
        'nilai_angka',
        'nilai_huruf',
        'status_kelulusan',
        'catatan'
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function dosenPenguji()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji_id');
    }
}
