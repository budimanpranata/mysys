<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pull_data extends Model
{

    protected $table = 'pull_data'; // Nama tabel

    protected $fillable = [
        'unit',
        'tgl_tagih',
        'code_kel',
        'cif',
        'cao',
        'norek',
        'angsuran_pokok',
        'angsuran_margin',
        'angsuran',
        'bayar',
        'status_realisasi',
        'pb',
        'ke',
        'tunggakan',
        'hari',
        'twm',
        'bulat',
        'simpanan_wajib',
        'simpanan_pokok',
        'os',
        'nama',
        'nama_kel',
        'saldo_margin',
        'plafond',
        'jenis_pull',
    ];
}
