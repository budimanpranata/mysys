<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class temp_akad_mus extends Model

{
    protected $table = 'temp_akad_mus';
    use HasFactory;

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'no_anggota', 'no');
    }
}
