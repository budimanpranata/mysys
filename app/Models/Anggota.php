<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';
    protected $guarded = [];
    protected $primaryKey = 'no';

    protected $casts = [
        'no' => 'string',
    ];

    public function temp_mus_akad()
    {
        return $this->hasMany(temp_akad_mus::class, 'no_anggota', 'no');
    }

    public function pembiayaan()
    {
        return $this->hasMany(pembiayaan::class, 'no_anggota', 'no');
    }
}
