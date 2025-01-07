<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TicketCreado extends Notification
{
    use Queueable;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'asunto' => $this->ticket->asunto,
            'descripcion' => $this->ticket->descripcion,
            'url' => route('ticket.mostrado', $this->ticket->id)
        ];
    }
}
