<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Ticket;
use App\Models\Team;
use App\Models\Motivo_Pausa;
use App\Models\Campana;
use App\Models\Log_Asignado;
use App\Models\Sede;
use App\Models\Categoria;
use App\Models\User;
use App\Models\Ticket_Asignado;
use App\Models\Depend_Ticket;
use App\Models\Tipo;
use App\Events\NotifyGroup;
use App\Events\AsignUser;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;
use App\Notifications\TicketCreado;

class TicketController extends Controller
{

    //vista al formulario de crear ticket
    public function VerCrearTicket(){
        $username = Auth::user()->username;
        $teams = Team::all();
        $campanas = Campana::all();
        $sedes = Sede::all();
        $categorias = Categoria::all();
        $tipos = Tipo::all();
        return view('ticket.crear_ticket',compact('username','teams','campanas','categorias','sedes','tipos'));
    }

    //funcion para guardar los datos al crear un ticket nuevo
    public function GuardarTicket(Request $request) {
        try {
        
            $request->validate([
                'archivos.*' => 'file|mimes:jpeg,jpg,png,pdf,docx,xlsx,csv,html,txt|max:2048',
            ], [
                'archivos.*.file' => 'El archivo debe ser un archivo válido.',
                'archivos.*.mimes' => 'El archivo debe ser de tipo: jpeg, jpg, png, pdf, docx, xlsx, txt, csv o html.',
                'archivos.*.max' => 'El archivo no debe exceder los 2MB.',
            ]);
            
     
            $ticket = new Ticket();
            $ticket->solicitante = Auth::user()->username;
            $ticket->team_id = $request->input('team_id');
            $ticket->id_tipo = $request->input('id_tipo');
            $ticket->id_categoria = $request->input('id_categoria');
            $ticket->id_sede = $request->input('id_sede');
            $ticket->id_campana = $request->input('id_campana');
            $ticket->asunto = $request->input('asunto');
            $ticket->descripcion = $request->input('descripcion');
            $ticket->estado = "Abierto";
            $ticket->team_id = $request->input('team_id');
            $ticket->creado = Carbon::now()->format('Y-m-d H:i:s');
    
        
            if ($request->hasFile('archivos')) {
                $nombresArchivos = [];
    
                foreach ($request->file('archivos') as $archivo) {
                    $nombreArchivo = $archivo->getClientOriginalName();
                    $archivo->storeAs('', $nombreArchivo, 'public');
                    $nombresArchivos[] = $nombreArchivo;
                }
    
                $ticket->archivo = json_encode($nombresArchivos);
            }
    
        
            $ticket->save();
            $message = "¡Se ha abierto un nuevo ticket!";
            broadcast(new NotifyGroup($message, $ticket->team_id));
    
         
            return redirect()->route('ticket.mostrado', ['ticket' => $ticket->id])->with('success', 'Ticket creado con éxito.');
    
        }  catch (Exception $e) {
            $request->session()->flash('error',  $e->getMessage());
            Log::error('Error al crear el ticket: ' . $e->getMessage());
            return back()->withErrors($e->errors())->withInput();

        }
    }
    
    //vista que muestra la "previa" de un ticket
    public function mostrarTicket($id){
        $username = Auth::user()->username;
        $ticket = Ticket::findOrFail($id);
        return view('ticket.ticket', compact('ticket','username'));
    }

    //vista para los tickets que aun no se han asignado
    public function VerSolicitudesSinAsig(Request $request)
    {
        $username = Auth::user()->username;
        $assignedTicketIds = Ticket_Asignado::pluck('id_ticket')->toArray();
        
        $tickets = Ticket::query();
        
        //filtros para la tabla
        $tickets->when($request->id, function ($query, $id) {
            return $query->where('id', $id);
        })->when($request->solicitante, function ($query, $solicitante) {
            return $query->where('solicitante', 'like', "%{$solicitante}%");
        })->when($request->para, function ($query, $para) {
            return $query->whereHas('team', function ($q) use ($para) {
                $q->where('nombre', 'like', "%{$para}%");
            });
        })->when($request->asignado_a, function ($query, $asignado_a) {
            return $query->whereHas('usuarioAsignado', function ($q) use ($asignado_a) {
                $q->where('username', 'like', "%{$asignado_a}%");
            });
        })->when($request->tipo, function ($query, $tipo) {
            return $query->where('id_tipo', $tipo);
        })->when($request->categoria, function ($query, $categoria) {
            return $query->whereHas('categoria', function ($q) use ($categoria) {
                $q->where('nombre', 'like', "%{$categoria}%");
            });
        })->when($request->sede, function ($query, $sede) {
            return $query->whereHas('sede', function ($q) use ($sede) {
                $q->where('nombre', 'like', "%{$sede}%");
            });
        })->when($request->campana, function ($query, $campana) {
            return $query->whereHas('campana', function ($q) use ($campana) {
                $q->where('nombre', 'like', "%{$campana}%");
            });
        })->when($request->asunto, function ($query, $asunto) {
            return $query->where('asunto', 'like', "%{$asunto}%");
        })->when($request->estado, function ($query, $estado) {
            return $query->where('estado', 'like', "%{$estado}%");
        })->when($request->fecha_creacion, function ($query, $fecha_creacion) {
            return $query->whereDate('created_at', $fecha_creacion);
        });
    
        if (Auth::user()->rol == "admin") {
            $tickets = $tickets->whereNotIn('id', $assignedTicketIds);
        } else if (Auth::user()->rol == "usuario") {
            $teamId = Auth::user()->team_id;
            $tickets = $tickets->where('team_id', $teamId)
                               ->whereNotIn('id', $assignedTicketIds);
        }
        
        $tickets = $tickets->Paginate(10);
    
        return view('ticket.solicitudes_sinAsig', compact('username', 'tickets'));
    }
    
    

    //funcion para asignar un ticket a un usuario
    public function asignarTicket(Request $request)
    {
        try{
            $id_ticket = $request->input('ticket_id');
            $id_user = $request->input('id_user');
        
            if (empty($id_ticket) || empty($id_user)) {
                Log::error('Ticket ID o User ID vacío', ['ticket_id' => $id_ticket, 'user_id' => $id_user]);
                return redirect()->back()->withErrors('No se pudo asignar el ticket. Por favor, verifica que has seleccionado un usuario.');
            }
    
            Ticket_Asignado::updateOrCreate(
                ['id_ticket' => $id_ticket],
                ['id_user' => $id_user]
            );
        
    
            $ticket = Ticket::find($id_ticket);
            if ($ticket) {
                $asignadoTime = Carbon::now(); 
                $ticket->estado = 'En Curso';
                $ticket->asignado = $asignadoTime->format('Y-m-d H:i:s');
              

                 
            $creadoTime = Carbon::parse($ticket->creado);
            $diff = $creadoTime->diff($asignadoTime); 
            

            $tiempoAsignado = '';
            if ($diff->d > 0) {
                $tiempoAsignado .= $diff->d . ' días ';
            }
            if ($diff->h > 0) {
                $tiempoAsignado .= $diff->h . ' horas ';
            }
            if ($diff->i > 0) {
                $tiempoAsignado .= $diff->i . ' minutos';
            }
            
          
            $ticket->tiempo_asignacion = trim($tiempoAsignado); 
            $ticket->save();
            }


  
            $nombreusuario = User::find($id_user)?->username;
            $asigLog = New Log_Asignado();
            $asigLog->id_ticket = $id_ticket;
            $asigLog->username = $nombreusuario;
            $asigLog->fecha = Carbon::now()->format('Y-m-d H:i:s');
            $asigLog->save();

            $message = "¡Se te ha asignado un ticket!";
            broadcast(new AsignUser($message,$id_user));
            Log::info("El ticket con id: ". $id_ticket . "se ha asignado al usuario con id: " . $id_user);
            return redirect()->back()->with('success', 'El ticket ha sido asignado correctamente.');
        }catch(Exception $e){
            Log::error('Error al asignar el ticket con id : '. $id_ticket . ' mensaje de error ' . $e->getMessage());
        }

    }
    

    //para mostrar los usuarios en la lista a la hora de asignar(se usa en un modal en la vista de las tablas)
    public function getUsersByTicket(Request $request)
    {
        try{
            $ticketId = $request->input('ticket_id');
    
            $ticket = Ticket::find($ticketId);
    
            if (!$ticket) {
                Log::info("no ticket");
            }
    
            $teamId = $ticket->team_id;
    
            $users = User::where('team_id', $teamId)->get();
    
            return response()->json($users);
        }catch(Exception $e){
            Log::error('Error al obtener el listado de usuarios para la asignacion de ticket: ' . $e->getMessage());
        }
       
    
    }

    //vista para las solicitudes que se asignan al usuario atenticado
    public function verMisSolicitudes(Request $request)
    {
        $username = Auth::user()->username;
        $userId = Auth::id();
        $motivosPausa = Motivo_Pausa::all();
        $assignedTicketIds = Ticket_Asignado::where('id_user', $userId)->pluck('id_ticket')->toArray();
    
        $tickets = Ticket::whereIn('id', $assignedTicketIds)
            ->when($request->id, function ($query, $id) {
                return $query->where('id', $id);
            })
            ->when($request->solicitante, function ($query, $solicitante) {
                return $query->where('solicitante', 'like', "%{$solicitante}%");
            })
            ->when($request->para, function ($query, $para) {
                return $query->whereHas('team', function ($q) use ($para) {
                    $q->where('nombre', 'like', "%{$para}%");
                });
            })
            ->when($request->asignado_a, function ($query, $asignado_a) {
                return $query->whereHas('usuarioAsignado', function ($q) use ($asignado_a) {
                    $q->where('username', 'like', "%{$asignado_a}%");
                });
            })
            ->when($request->tipo, function ($query, $tipo) {
                return $query->whereHas('tipo', function ($q) use ($tipo) {
                    $q->where('nombre', 'like', "%{$tipo}%");
                });
            })
            ->when($request->categoria, function ($query, $categoria) {
                return $query->whereHas('categoria', function ($q) use ($categoria) {
                    $q->where('nombre', 'like', "%{$categoria}%");
                });
            })
            ->when($request->sede, function ($query, $sede) {
                return $query->whereHas('sede', function ($q) use ($sede) {
                    $q->where('nombre', 'like', "%{$sede}%");
                });
            })
            ->when($request->campana, function ($query, $campana) {
                return $query->whereHas('campana', function ($q) use ($campana) {
                    $q->where('nombre', 'like', "%{$campana}%");
                });
            })
            ->when($request->asunto, function ($query, $asunto) {
                return $query->where('asunto', 'like', "%{$asunto}%");
            })
            ->when($request->estado, function ($query, $estado) {
                return $query->where('estado', 'like', "%{$estado}%");
            })
            ->when($request->fecha_creacion, function ($query, $fecha_creacion) {
                return $query->whereDate('created_at', $fecha_creacion);
            })
            ->Paginate(10);
    
        return view('ticket.mis_solicitudes', compact('username', 'tickets','motivosPausa'));
    }
    

    //vista de la edicion de un ticket
    public function VerEditarTicket($id){
        $username = Auth::user()->username;
        $ticket = Ticket::findOrFail($id);
        $teams = Team::all();
        $campanas = Campana::all();
        $sedes = Sede::all();
        $categorias = Categoria::all();
        $tipos = Tipo::all();
        $motivosPausa = Motivo_Pausa::all();
    
     
        $dependTickets = Depend_Ticket::where('id_ticket', $ticket->id)->get();
    
       
        $depends = [];
        foreach ($dependTickets as $dependTicket) {
            $depends[] = $dependTicket->depend_ticket_id;  
        }
    
       
        return view('ticket.editar-ticket', compact('depends', 'username', 'ticket', 'teams', 'campanas', 'categorias', 'sedes', 'tipos', 'motivosPausa'));
    }
    //funcion para guardar los cambios editados
    public function GuardarEditar(Request $request, $id){
        try{


        $ticket = Ticket::findOrFail($id);
        $ticket->team_id = $request->team_id;
        $ticket->id_tipo = $request->id_tipo;
        $ticket->id_categoria= $request->id_categoria;
        $ticket->id_sede= $request->id_sede;
        $ticket->estado= $request->estado;
        $ticket->id_motivo_pausa= $request->id_motivo_pausa;
        $ticket->id_campana= $request->id_campana;
        $ticket->asunto= $request->asunto;
        $ticket->descripcion= $request->descripcion;
        $ticket->prioridad = $request->prioridad;
        $ticket->actualizado =Carbon::now()->format('Y-m-d H:i:s');
        $archivosExistentes = json_decode($ticket->archivo, true) ?? [];

    $archivosExistentes = json_decode($ticket->archivo, true) ?? [];

  
    if ($request->hasFile('archivos')) {
        foreach ($request->file('archivos') as $archivo) {
          
            $nombreArchivo = $archivo->getClientOriginalName();
            
            $archivo->storeAs('', $nombreArchivo, 'public');

            $archivosExistentes[] = $nombreArchivo;
        }
    }


     $ticket->archivo = json_encode($archivosExistentes);
     //guardar la fecha y hora si se pausa un ticket
     if($ticket->estado == "Pausado"){
        $ticket->pausado =Carbon::now()->format('Y-m-d H:i:s');
        
    }
    // Verificar si el ticket estaba pausado y se cambió a "Abierto"
    if ($ticket->getOriginal('estado') == "Pausado" && $ticket->estado == "Abierto") {
        $ticket->cambio_estado = Carbon::now()->format('Y-m-d H:i:s');
    }

        $ticket->save();

        $depend_ticket_id =  $request->input('depend_ticket_id');
        $id_ticket =  $request->input('id_ticket');

        $ticketDepend = Depend_Ticket::create([
            'id_ticket' => $id_ticket,
            'depend_ticket_id' => $depend_ticket_id,
           
        ]);

    
        return redirect()->back()->with('success', 'your message,here');   

    }catch(Exception $e){
        Log::error('Error al editar el ticket  con id: '.$id . "con mensaje" . $e->getMessage());
    }
    }


    //funcion para eliminar ticket 
    public function EliminarTicket($id){
        try{
        $ticket = Ticket::findOrFail($id);
        
        $ticket->delete();
    
        return redirect()->back()->with('success', 'Archivo eliminado con éxito.');
        }catch(Exception $e){
            Log::error('Error al eliminar el ticket  con id: '.$id . "con mensaje" . $e->getMessage());
        }
    }

    //funcion para eliminar archivos adjuntos en la opcion de editar tickets
    public function eliminarArchivo($ticketId, $nombreArchivo) {
        try{

       
            $ticket = Ticket::findOrFail($ticketId);
            $archivos = json_decode($ticket->archivo, true);
        

            if (($key = array_search($nombreArchivo, $archivos)) !== false) {
                unset($archivos[$key]);
        

                Storage::disk('public')->delete('archivos/' . $nombreArchivo);
        
            
                $ticket->archivo = json_encode(array_values($archivos));
                $ticket->save();
            }
        
            return redirect()->back()->with('success', 'Archivo eliminado con éxito.');
        }catch(Exception $e){
            Log::error('Error al eliminar el archivo de la vista editar: ' . $e->getMessage());
        }
    }

    //vista de las peticiones que realiza el usuario autenticado
    public function VerPeticiones(Request $request)
    {
        $username = Auth::user()->username;
    
 
        $tickets = Ticket::where('solicitante', $username)
            ->when($request->id, function ($query, $id) {
                return $query->where('id', $id);
            })
            ->when($request->para, function ($query, $para) {
                return $query->whereHas('team', function ($q) use ($para) {
                    $q->where('nombre', 'like', "%{$para}%");
                });
            })
            ->when($request->asignado_a, function ($query, $asignado_a) {
                return $query->whereHas('usuarioAsignado', function ($q) use ($asignado_a) {
                    $q->where('username', 'like', "%{$asignado_a}%");
                });
            })
            ->when($request->tipo, function ($query, $tipo) {
                return $query->whereHas('tipo', function ($q) use ($tipo) {
                    $q->where('nombre', 'like', "%{$tipo}%");
                });
            })
            ->when($request->categoria, function ($query, $categoria) {
                return $query->whereHas('categoria', function ($q) use ($categoria) {
                    $q->where('nombre', 'like', "%{$categoria}%");
                });
            })
            ->when($request->sede, function ($query, $sede) {
                return $query->whereHas('sede', function ($q) use ($sede) {
                    $q->where('nombre', 'like', "%{$sede}%");
                });
            })
            ->when($request->campana, function ($query, $campana) {
                return $query->whereHas('campana', function ($q) use ($campana) {
                    $q->where('nombre', 'like', "%{$campana}%");
                });
            })
            ->when($request->asunto, function ($query, $asunto) {
                return $query->where('asunto', 'like', "%{$asunto}%");
            })
            ->when($request->estado, function ($query, $estado) {
                return $query->where('estado', 'like', "%{$estado}%");
            })
            ->when($request->fecha_creacion, function ($query, $fecha_creacion) {
                return $query->whereDate('created_at', $fecha_creacion);
            })
            ->Paginate(10);
    
        return view("ticket.peticiones", compact('username', 'tickets'));
    }
    
    //vista de los tickets totales, asignados, sin asignar, abiertos,cerrados...
    public function VerTotales(Request $request)
    {
        $username = Auth::user()->username;
        $teamId = Auth::user()->team_id;
     
        $tickets = Ticket::query()
            ->when($request->id, function ($query, $id) {
                return $query->where('id', $id);
            })
            ->when($request->solicitante, function ($query, $solicitante) {
                return $query->where('solicitante', 'like', "%{$solicitante}%");
            })
            ->when($request->para, function ($query, $para) {
                return $query->whereHas('team', function ($q) use ($para) {
                    $q->where('nombre', 'like', "%{$para}%");
                });
            })
            ->when($request->asignado_a, function ($query, $asignado_a) {
                return $query->whereHas('usuarioAsignado', function ($q) use ($asignado_a) {
                    $q->where('username', 'like', "%{$asignado_a}%");
                });
            })
            ->when($request->tipo, function ($query, $tipo) {
                return $query->whereHas('tipo', function ($q) use ($tipo) {
                    $q->where('nombre', 'like', "%{$tipo}%");
                });
            })
            ->when($request->categoria, function ($query, $categoria) {
                return $query->whereHas('categoria', function ($q) use ($categoria) {
                    $q->where('nombre', 'like', "%{$categoria}%");
                });
            })
            ->when($request->sede, function ($query, $sede) {
                return $query->whereHas('sede', function ($q) use ($sede) {
                    $q->where('nombre', 'like', "%{$sede}%");
                });
            })
            ->when($request->campana, function ($query, $campana) {
                return $query->whereHas('campana', function ($q) use ($campana) {
                    $q->where('nombre', 'like', "%{$campana}%");
                });
            })
            ->when($request->asunto, function ($query, $asunto) {
                return $query->where('asunto', 'like', "%{$asunto}%");
            })
            ->when($request->estado, function ($query, $estado) {
                return $query->where('estado', 'like', "%{$estado}%");
            })
            ->when($request->fecha_creacion, function ($query, $fecha_creacion) {
                return $query->whereDate('created_at', $fecha_creacion);
            })
            ->Paginate(10);
            if (  Auth::user()->roll != "admin") {
                $tickets->where('team_id', $teamId);
            }
        return view("ticket.totales", compact('username', 'tickets'));
    }
    
    public function CerrarTicket($id)
    {
        try {
            $ticket = Ticket::find($id);
            if ($ticket) {
                
                $ticket->estado = 'Cerrado';
                $ticket->cerrado = Carbon::now()->format('Y-m-d H:i:s');
    
                if ($ticket->asignado) {
                    $asignadoTime = Carbon::parse($ticket->asignado);
                    $cerradoTime = Carbon::parse($ticket->cerrado);
    
                   
                    $tiempoTotalEnSegundos = $cerradoTime->diffInSeconds($asignadoTime);
    
                  
                    $tiempoPausadoEnSegundos = 0;
                    if ($ticket->pausado && $ticket->cambio_estado) {
                        $pausadoTime = Carbon::parse($ticket->pausado);
                        $cambioEstadoTime = Carbon::parse($ticket->cambio_estado);
    
                     
                        if ($pausadoTime <= $cambioEstadoTime) {
                            $tiempoPausadoEnSegundos = $cambioEstadoTime->diffInSeconds($pausadoTime);
                        }
                    }
    
                 
                    $tiempoRealEnSegundos = max(0, $tiempoTotalEnSegundos - $tiempoPausadoEnSegundos);
    
         
                    $dias = floor($tiempoRealEnSegundos / 86400);
                    $tiempoRealEnSegundos %= 86400;
                    $horas = floor($tiempoRealEnSegundos / 3600);
                    $tiempoRealEnSegundos %= 3600;
                    $minutos = floor($tiempoRealEnSegundos / 60);
                    $segundos = $tiempoRealEnSegundos % 60;
    
             
                    $tiempoRespuesta = "$dias días, $horas horas, $minutos minutos";
                    $ticket->tiempo_respuesta = $tiempoRespuesta;
                }
    
                $ticket->save();
    
            
                $dependTickets = Depend_Ticket::where('depend_ticket_id', $id)->get();
                foreach ($dependTickets as $dependTicket) {
                    $ticketDependiente = Ticket::find($dependTicket->id_ticket);
                    if ($ticketDependiente) {
                        $ticketDependiente->estado = 'Abierto';
                        $ticketDependiente->save();
                    }
                
                    $dependTicket->delete();
                }
    
                return redirect()->back()->with('success', 'Ticket cerrado y tickets dependientes actualizados.');
            }
    
            return redirect()->back()->with('error', 'El ticket no se encontró.');
        } catch (Exception $e) {
            Log::error('Error al cerrar el ticket con id: ' . $id . " con mensaje: " . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al cerrar el ticket.');
        }
    }
    
    
//vista de los tickets cerrados   
public function VerCerrados(Request $request)
{
    $username = Auth::user()->username;
    $user = Auth::user();

    // Verifica si el usuario es admin. Si no es admin, solo podrá ver los tickets de su propio team_id
    $ticketsQuery = Ticket::where('estado', 'Cerrado')
        ->when($request->id, function ($query, $id) {
            return $query->where('id', $id);
        })
        ->when($request->solicitante, function ($query, $solicitante) {
            return $query->where('solicitante', 'like', "%{$solicitante}%");
        })
        ->when($request->para, function ($query, $para) {
            return $query->whereHas('team', function ($q) use ($para) {
                $q->where('nombre', 'like', "%{$para}%");
            });
        })
        ->when($request->asignado_a, function ($query, $asignado_a) {
            return $query->whereHas('usuarioAsignado', function ($q) use ($asignado_a) {
                $q->where('username', 'like', "%{$asignado_a}%");
            });
        })
        ->when($request->tipo, function ($query, $tipo) {
            return $query->whereHas('tipo', function ($q) use ($tipo) {
                $q->where('nombre', 'like', "%{$tipo}%");
            });
        })
        ->when($request->categoria, function ($query, $categoria) {
            return $query->whereHas('categoria', function ($q) use ($categoria) {
                $q->where('nombre', 'like', "%{$categoria}%");
            });
        })
        ->when($request->sede, function ($query, $sede) {
            return $query->whereHas('sede', function ($q) use ($sede) {
                $q->where('nombre', 'like', "%{$sede}%");
            });
        })
        ->when($request->campana, function ($query, $campana) {
            return $query->whereHas('campana', function ($q) use ($campana) {
                $q->where('nombre', 'like', "%{$campana}%");
            });
        })
        ->when($request->asunto, function ($query, $asunto) {
            return $query->where('asunto', 'like', "%{$asunto}%");
        })
        ->when($request->estado, function ($query, $estado) {
            return $query->where('estado', 'like', "%{$estado}%");
        })
        ->when($request->fecha_creacion, function ($query, $fecha_creacion) {
            return $query->whereDate('created_at', $fecha_creacion);
        });


    if ($user->rol != 'admin') {
        $ticketsQuery->where('team_id', $user->team_id);
    }

    $tickets = $ticketsQuery->Paginate(10);

    return view("ticket.cerrado", compact('username', 'tickets'));
}




}