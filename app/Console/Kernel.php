<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Ticket;
use App\Models\Ticket_Asignado;
use App\Events\NotifyGroup;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
            
    $schedule->call(function(){
        $totalTickets = Ticket::count();
        $asignedTickets = Ticket_Asignado::count();
    
        $total = $totalTickets - $asignedTickets;
        if($total <= 0){
        return ;
        }
        $message = "Hay $total ticket/s sin asignar";
        broadcast(new NotifyGroup($message,1));
    })->everyFifteenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
