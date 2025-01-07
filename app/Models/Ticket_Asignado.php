<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket_Asignado extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'id_ticket'
    ];
   protected $table = "tickets_asignados";
   public function ticket()
   {
       return $this->belongsTo(Ticket::class, 'id_ticket', 'id');
   }

   public function user()
   {
       return $this->belongsTo(User::class, 'id_user', 'id');
   }
}
