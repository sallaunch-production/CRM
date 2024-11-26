<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentId extends Model
{
    use HasFactory;
    protected $table = "agent_id";

    protected $fillable = [
        'id','id_pos','agent_id','alamat','nama','legacy','pos','pos_password','created_at','updated_at','corporate'
    ];
    
    

}
