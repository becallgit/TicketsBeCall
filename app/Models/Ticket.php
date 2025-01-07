<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'solicitante',
        'id_tipo',
        'id_categoria',
        'id_sede',
        'id_campana',
        'asunto',
        'descripcion',
        'archivo',
        'team_id',
        'prioridad',
        'estado',
        'creado',
        'asignado',
        'cerrado',
        'actualizado',
        'id_motivo_pausa',
        'pausado',
        'tiempo_asignacion',
        'tiempo_respuesta',
        'cambio_estado'
    ];
   protected $table = "tickets";
   public function team()
   {
       return $this->belongsTo(Team::class, 'team_id');
   }
   public function motivoPausado()
   {
       return $this->belongsTo(Motivo_Pausa::class, 'id_motivo_pausa');
   }
   
   public function categoria()
   {
       return $this->belongsTo(Categoria::class, 'id_categoria');
   }

   public function sede()
   {
       return $this->belongsTo(Sede::class, 'id_sede');
   }
   public function tipo()
   {
       return $this->belongsTo(Tipo::class, 'id_tipo');
   }

   public function campana()
   {
       return $this->belongsTo(Campana::class, 'id_campana');
   }
   public function ticketAsignado()
   {
       return $this->hasOne(Ticket_Asignado::class, 'id_ticket', 'id');
   }

    public function asignaciones()
    {
        return $this->hasMany(Ticket_Asignado::class, 'id_ticket');
    }

    public function usuarioAsignado()
    {
        return $this->hasOneThrough(User::class, Ticket_Asignado::class, 'id_ticket', 'id', 'id', 'id_user');
    }
}


