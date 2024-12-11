<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Jurusan extends Model
{
    //
    use HasUuids;
    protected $table = 'jurusans';
    protected $primaryKey = 'IdJurusan';
    protected $fillable = [
        'namaJurusan',
        'fakultas',
        'kuota',
    ];
    public function mahasiswa(){
        return $this->hasOne(Mahasiswa::class,'IdJurusan','IdJurusan');
    }
}
