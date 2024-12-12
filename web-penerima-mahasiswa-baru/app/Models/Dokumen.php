<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{

    use HasUuids;
    protected $table = 'dokumens';
    protected $primaryKey = 'IdDokumen';
    protected $foreignKey = 'IdMahasiswa';
    protected $fillable = [
        'IdMahasiswa',
        'namaDokumen',
        'dokumenPath',
    ];
    public function mahasiswa() : BelongsTo{
        return $this->belongsTo(Mahasiswa::class, 'IdMahasiswa','IdMahasiswa');
    }
}
