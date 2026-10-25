<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = ['user_id', 'judul', 'konten', 'file_lampiran'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
