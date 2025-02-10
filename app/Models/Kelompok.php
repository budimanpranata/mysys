<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $table = 'kelompok';
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'code_kel';
}
