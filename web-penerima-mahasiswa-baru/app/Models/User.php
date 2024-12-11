<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject
{
    use HasUuids;
    protected $table = 'users';
    protected $primaryKey = 'IdUser';
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles',
        'status'
    ];
    protected $hidden = [
        'password',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
        public function getJWTCustomClaims()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public function mahasiswa() : HasOne
    {
        return $this->hasOne(Mahasiswa::class,'IdUser','IdUser');
    }

    public function pembayaran() : HasOne
    {
        return $this->hasOne(Pembayaran::class, 'IdUser','IdUser');
    }
}
