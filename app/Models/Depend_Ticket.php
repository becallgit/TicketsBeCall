<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depend_Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_ticket',
        'depend_ticket_id'
    ];
   protected $table = "depend_ticket";
   
}
