<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegaraException extends Model
{
    use HasFactory;
    protected $table = "negara_exception";
    
    protected $fillable = [
        'id',
        'negara',
        'bsu',
        'tanggal',
        'no_referensi',
        'maksimum',
        'dari',
        'sampai',
    ];

}