<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_Formacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
    ];
   protected $table = "tipo_formacion";
}
