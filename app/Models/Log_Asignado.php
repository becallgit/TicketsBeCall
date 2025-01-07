<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log_Asignado extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'id_ticket'
    ];
   protected $table = "log_asignado";
}
