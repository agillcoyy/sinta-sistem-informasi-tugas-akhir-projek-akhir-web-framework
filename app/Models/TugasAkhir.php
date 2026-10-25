<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasAkhir extends Model
{
    protected $guarded = ['id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    public function sidang()
    {
        return $this->hasOne(Sidang::class);
    }
}
