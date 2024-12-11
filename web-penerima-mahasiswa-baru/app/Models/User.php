<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasUuids, HasApiTokens;
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
            'roles' => $this->roles,
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
