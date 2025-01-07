<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Team;
use App\Models\Ticket_Asignado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\ticketsExports;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $username = Auth::user()->username;
        $userRole = Auth::user()->rol;
        $userTeamId = Auth::user()->team_id;
    
        $assignedTicketIds = Ticket_Asignado::pluck('id_ticket')->toArray();
        $ticketsAbiertos = "";
        $ticketsSinAsignar = "";
        $ticketsPorEquipo = "";
        $teamName = "";
    
        if ($userRole == 'admin') {
            $ticketsPorEquipo = Team::withCount([
                'tickets as tickets_abiertos' => function ($query) {
                    $query->where('estado', 'Abierto');
                },
                'tickets as tickets_sin_asignar' => function ($query) use ($assignedTicketIds) {
                    $query->whereNotIn('id', $assignedTicketIds);
                }
            ])->get();
        } else {
            $ticketsAbiertos = Ticket::where('estado', 'Abierto')
                                      ->where('team_id', $userTeamId)
                                      ->count();
            $ticketsSinAsignar = Ticket::whereNotIn('id', $assignedTicketIds)
                                        ->where('team_id', $userTeamId)
                                        ->count();
            $teamName = Team::where('id', $userTeamId)->value('nombre'); 
        }
    
        return view('dashboard', compact('username', 'userRole', 'ticketsPorEquipo', 'ticketsAbiertos', 'ticketsSinAsignar', 'teamName'));
    }
    


    
    public function exportData()
    {
        try{

        
        $data = Ticket::with(['team', 'categoria', 'sede', 'campana', 'motivoPausado'])
                      ->select('id', 'solicitante', 'team_id', 'id_tipo', 'id_categoria', 'id_sede', 'id_campana', 'asunto', 'descripcion', 'estado', 'prioridad', 'creado', 'actualizado', 'asignado', 'cerrado','cambio_estado','tiempo_asignacion','tiempo_respuesta')
                      ->get();

        $actual = Carbon::now()->format('d-m-Y');
        return Excel::download(new ticketsExports($data), "Exportacion-Tickets-$actual.xlsx");
        }catch(Exception $e){
            Log::error('Error al exportar los tickets a excel: ' . $e->getMessage());
        }
    }

    public function verManual(){
        $username = Auth::user()->username;
        return view('manual',compact("username"));
        
    }
    
}
