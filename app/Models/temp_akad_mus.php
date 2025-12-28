<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class temp_akad_mus extends Model

{
    use HasFactory;
    protected $table = 'temp_akad_mus';
    protected $primaryKey = 'no_anggota';
    public $incrementing = false;
    protected $keyType = 'string';

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'no_anggota', 'no');
    }
}
