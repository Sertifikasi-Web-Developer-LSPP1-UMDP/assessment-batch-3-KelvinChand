<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    //
    use HasUuids;
    protected $table = 'mahasiswas';
    protected $primaryKey = 'IdMahasiswa';
    protected $foreignKey = ['IdUser', 'IdMahasiswa'];
    protected $fillable = [
        'NPM',
        'alamat',
        'noTelp',
        'jenisKelamin',
        'asalSekolah',
        'tanggalLahir',
        'tanggalBergabung',
        'tanggalKelulusan',
    ];
    public function dokumen() : HasMany {
        return $this->hasMany(Dokumen::class,'IdMahasiswa','IdMahasiswa');
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'IdUser', 'IdUser');
    }

    public function jurusan() : BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'IdJurusan','IdJurusan');
    }
}
