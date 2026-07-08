<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaArusKasMapping extends Model
{
    protected $table = 'coa_arus_kas_mappings';

    protected $fillable = [
        'match_type',
        'match_value',
        'arah',
        'code_arus_kas',
        'keterangan',
    ];
}
