<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembiayaan extends Model
{

    protected $table = 'pembiayaan';
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'cif';
    protected $casts = [
        'cif' => 'string',
    ];
}
