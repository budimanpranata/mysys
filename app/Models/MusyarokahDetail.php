<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MusyarokahDetail extends Model
{
    protected $table = 'musyarokah_detail';

    protected $fillable = [
        'id_pinjam',
        'angsuran_ke',
        'omzet',
        'angsuran_pokok',
        'angsuran_margin',
        'tgl_bayar',
        'margin_nisbah',
        'cif',
        'unit',
        'ao'
    ];
    protected $primaryKey = 'id';

}
