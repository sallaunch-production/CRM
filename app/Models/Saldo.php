<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    protected $table = "saldo";
    protected $primaryKey = 'no';
    protected $fillable = [
        'no',
        'nominal',
        'keterangan',
        'ip',
        'user',
        'updated_by',
        'tanggal',
    ];

}
