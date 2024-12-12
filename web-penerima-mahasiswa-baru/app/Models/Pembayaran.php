<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    //
    use HasUuids;
    protected $table = 'pembayarans';
    protected $primaryKey = 'IdPembayaran';
    protected $foreignKey = 'IdUser';
    protected $fillable = [
        'IdUser',
        'tanggalPembayaran',
        'nominalPembayaran',
        'status',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'IdUser', 'IdUser');
    }

}
