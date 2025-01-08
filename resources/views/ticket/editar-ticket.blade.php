<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR TICKET</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   

<script>

        function toggleMotivoPausa() {
            var estado = document.getElementById("estado").value;
            var motivoPausa = document.getElementById("motivo_pausa_container");
            var dependid = document.getElementById("depend");
         
            if (estado === "Pausado") {
                motivoPausa.style.display = "block";
            } else {
                motivoPausa.style.display = "none";
                document.getElementById("id_motivo_pausa").value = "";
                toggleDepend();
            }

        }
    
        function toggleDepend() {
            var motivoPausa = document.getElementById("id_motivo_pausa");
            var dependid = document.getElementById("depend");
            var motivo = motivoPausa.selectedOptions[0].textContent; 
            var dependvalue= document.getElementById("depend_ticket_id").value
          
            if (motivo.includes("Depende de otro Ticket")) {
                dependid.style.display = "block";
            } else {
                dependid.style.display = "none";
                document.getElementById("depend_ticket_id").value = "";

            }
        }

        document.addEventListener("DOMContentLoaded", function() {
    
            toggleMotivoPausa();

            toggleDepend()
            document.getElementById("estado").addEventListener("change", toggleMotivoPausa);
            document.getElementById("id_motivo_pausa").addEventListener("change", toggleDepend);
        });
        </script>
</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')

    <form action="{{ route('guardar.editar', $ticket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-container">
            <h2>EDITAR TICKET Nª {{$ticket->id}}</h2>
        <div class="form-section">
            <h3>Detalles</h3>
            <div class="form-group">
                <label for="team_id">Para</label>
                <select id="team_id" name="team_id">
                    <option value="" disabled selected>Para que departamento es la solicitud...</option>
                    @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{ $team->id == $ticket->team_id ? 'selected' : '' }}>
                        {{ $team->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="id_tipo">
                    <option value="" disabled selected>Seleccione el tipo...</option>
                    @foreach ($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ $tipo->id == $ticket->id_tipo ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_categoria">Categoría</label>
                <select id="id_categoria" name="id_categoria">
                    <option value="" disabled selected>Seleccione la categoría...</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ $categoria->id == $ticket->id_categoria ? 'selected' : '' }}>
                            {{ $categoria->nombre }} 
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_sede">Sede</label>
                <select id="id_sede" name="id_sede">
                    <option value="" disabled selected>Seleccione la sede...</option>
                    @foreach ($sedes as $sede)
                        <option value="{{ $sede->id }}"  {{ $sede->id == $ticket->id_sede ? 'selected' : '' }}>
                            {{ $sede->nombre }} 
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_campana">Campaña</label>
                <select id="id_campana" name="id_campana">
                    <option value="" disabled selected>Seleccione la campaña...</option>
                    @foreach ($campanas as $campana)
                        <option value="{{ $campana->id }}" {{ $campana->id == $ticket->id_campana ? 'selected' : '' }}>
                            {{ $campana->nombre }} 
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_user">Asignado a:</label>
                <input type="text" id="id_user" name="id_user" value="{{ $ticket->usuarioAsignado->username ?? 'No asignado' }}" readonly>
            </div>

            @if ($ticket->usuarioAsignado && (auth()->user()->id == $ticket->usuarioAsignado->id ))
                <div class="form-group">
                    <label for="prioridad">Prioridad:</label>
                    <select name="prioridad" id="prioridad">
                        <option value="" label="Selecciona..."></option>
                        <option value="Alta" {{ $ticket->prioridad == "Alta" ? 'selected' : '' }}>Alta</option>
                        <option value="Media" {{ $ticket->prioridad == "Media" ? 'selected' : '' }}>Media</option>
                        <option value="Baja" {{ $ticket->prioridad == "Baja" ? 'selected' : '' }}>Baja</option>
                    </select>
                </div>
            @endif

            <div class="form-group">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado">
                    <option value="" label="Selecciona..."></option>
                    <option value="Abierto" {{ $ticket->estado == "Abierto" ? 'selected' : '' }}>Abierto</option>
                    <option value="En Curso" {{ $ticket->estado == "En Curso" ? 'selected' : '' }}>En Curso</option>
                    <option value="Pausado" {{ $ticket->estado == "Pausado" ? 'selected' : '' }}>Pausado</option> 
                    <option value="Cerrado" {{ $ticket->estado == "Cerrado" ? 'selected' : '' }}>Cerrado</option>
                </select>
            </div>
     
            <div id="motivo_pausa_container" class="form-group" style="display: none;">
                <label for="id_motivo_pausa">Motivo de Pausa:&nbsp;&nbsp;</label>
                <select id="id_motivo_pausa" name="id_motivo_pausa">
                    <option value="" disabled selected>Seleccione el motivo...</option>
                    @foreach ($motivosPausa as $motivo)
                        <option value="{{ $motivo->id }}" {{ $motivo->id == $ticket->id_motivo_pausa ? 'selected' : '' }}>
                            {{ $motivo->nombre }} 
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div id="depend" class="form-group" style="display: none;">
                <label for="depend_ticket_id">ID Ticket: &nbsp;&nbsp;</label>
                <input type="text" id="depend_ticket_id"name="depend_ticket_id" placeholder="Ingrese el id del ticket del que dependes" value="">

         
                <input type="hidden" id="id_ticket" name="id_ticket" value="{{$ticket->id}}">
                @foreach ($depends as $dependTicketId)
                 Actualmente depende del ticket con id:   {{ $dependTicketId }}
                @endforeach

            </div>
        </div>
    

        <div class="form-section">
            <h3>Descripción de la Solicitud</h3>
            <div class="form-group">
                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto" placeholder="Ingrese el asunto" value="{{$ticket->asunto}}">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" placeholder="Describa su solicitud" maxlength="400">{{$ticket->descripcion}}</textarea>
            </div>
         
        </div>

        
            <div class="form-section">
            <h3>Archivos Adjuntos</h3>
            
            <div class="form-group">
                <label>Archivos actuales:</label>
                <ul>
                @if(is_array(json_decode($ticket->archivo, true)))
                    @foreach(json_decode($ticket->archivo, true) as $archivo)
                        <li>
                            <a href="{{ asset('archivos/' . $archivo) }}" target="_blank">{{ $archivo }}</a>
                            <a href="{{ route('eliminar.archivo', ['ticket' => $ticket->id, 'archivo' => $archivo]) }}" class="delete-btn">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </a>
                        </li>
                    @endforeach
                    @else
                    <p>No hay archivos subidos.</p>
                @endif
                </ul>
            </div>

            <div class="form-group">
                <label for="archivos">Agregar Archivos</label>
                <input type="file" id="archivos" name="archivos[]" multiple>
            </div>
        </div>

    
        <div class="form-group">
            <button type="submit" class="submit-btn">EDITAR TICKET</button>
        </div>
    </div>
    </form>
    @endsection
    <style>
            @font-face {
            font-family: 'Nexa';
            src:url('{{ asset('fonts/Nexa-ExtraLight.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: "Nexa","Segoe UI Light";
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f5f5f5;
        }

     
        
           /* Formulario */
           .form-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
   
        }

        .form-section {
            margin-bottom: 20px;
        }

        .form-section h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #f7a731;
            padding-bottom: 5px;
        }

        .form-group {
            display: flex;
        
            margin-bottom: 15px;
        }
        #motivo_pausa_container{
            
            margin-left:2vw;
            margin-bottom: 15px;
        }
        #depend{
            
            margin-left:4.7vw;
            margin-bottom: 15px;
        }
        .form-group label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group input,
        .form-group textarea {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
        }

        .form-group textarea {
            min-height: 100px;
        }

        .form-group .submit-btn {
            background-color: #f7a731;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .form-group .submit-btn:hover {
            background-color: #e69428;
        }
        h2{
            margin-top:-13px;
            text-align:center;
            margin-bottom:-30px;
        }
        @media (min-width: 600px) {
            .form-group {
                flex-direction: row;
                align-items: center;
                gap: 15px;
            }

            .form-group label {
                width: 150px;
                text-align: right;
            }

            .form-group select,
            .form-group input,
            .form-group textarea {
                flex: 1;
            }
        }
        .delete-btn {
        color: red;
        margin-left: 10px;
        text-decoration: none;
        font-weight: bold;
    }

    .delete-btn:hover {
        color: darkred;
    }

    </style>
</body>
</html>
