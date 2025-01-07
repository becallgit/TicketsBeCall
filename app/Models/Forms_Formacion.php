<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forms_Formacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'cargo',
        'fecha',
        'id_tipo_formacion',
        'detalle_formacion',
        'formacion_inicial',
        'estado',
        'observaciones', 
    ];
   protected $table = "forms_formacion";

   public function tipo()
   {
       return $this->belongsTo(Tipo_Formacion::class, 'id_tipo_formacion');
   }
}
