<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;


class nuevoforms extends Mailable
{

    public $enlace;


    public function __construct($enlace)
    {
       
        $this->enlace = $enlace;
    }

    // Funcion para generar los correos
    public function build(){
    
        return $this->subject('¡NUEVA SOLICITUD DE FORMACION!') // Introducimos el asunto del correo
                    ->view('emails.nuevoform');
                 
    }
}
