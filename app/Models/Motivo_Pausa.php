<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motivo_Pausa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
    ];
   protected $table = "motivo_pausa";
}
