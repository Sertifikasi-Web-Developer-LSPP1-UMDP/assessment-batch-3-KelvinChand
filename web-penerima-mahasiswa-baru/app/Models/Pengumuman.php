<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    //
    use HasUuids;
    protected $table = 'pengumumans';
    protected $primaryKey= 'IdPengumuman';
    protected $fillable = [
        'judulPengumuman',
        'deskripsi',
        'gambarPengumuman',
        'tanggalPengumuman',
    ];
}
