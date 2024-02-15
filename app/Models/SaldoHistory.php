<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoHistory extends Model
{
    use HasFactory;
    protected $table = "saldo_history";

    protected $fillable = [
        'id',
        'awal',
        'akhir',
        'tanggal',
    ];

}
