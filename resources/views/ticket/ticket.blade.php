<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TICKET Nº {{$ticket->id}}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   
</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')
    <div class="form-container">
    <h2>Detalles del Ticket Nº {{$ticket->id}}</h2>


    </h2>
        <div class="form-columns">
            <!-- Columna de Detalles -->
            <div class="form-section">
                <h3>Detalles</h3>
                <div class="form-group">
                <div class="estado-prioridad" style="display: flex; align-items: center; gap: 20px;">
                    <div class="estado" style="display: flex; align-items: center; gap: 5px;">
                        <label>Estado:</label>
                        <span class="{{ $ticket->estado === 'Abierto' ? 'green' : ($ticket->estado === 'Cerrado' ? 'grey' : ($ticket->estado === 'Pausado' ? 'purple' : 'blue')) }}">
                        <i class="fa-solid fa-circle"></i> {{$ticket->estado}}
                    </span>

                    </div>
                    
                    <div class="prioridad" style="display: flex; align-items: center; gap: 5px;">
                        <label for="prioridad">Prioridad:</label>
                        <span class="{{ $ticket->prioridad === 'Alta' ? 'red' : ($ticket->prioridad === 'Media' ? 'yellow' : 'gray') }}">
                            <i class="fa-solid fa-circle"></i> {{ $ticket->prioridad ?? 'Sin Prioridad Establecida' }}
                        </span>
                    </div>
                </div>
            </div>
  
                <div class="form-group">
                    <label for="solicitante">Solicitante</label>
                    <input type="text" id="solicitante" name="solicitante" value="{{ $ticket->solicitante }}" readonly>
                </div>
                <div class="form-group">
                    <label for="team_id">Para</label>
                    <input type="text" id="team_id" name="team_id" value="{{ $ticket->team ? $ticket->team->nombre : 'No asignado' }}" readonly>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <input type="text" id="tipo" name="id_tipo" value="{{ $ticket->id_tipo ? $ticket->tipo->nombre : 'No asignado' }}" readonly>
                </div>
                <div class="form-group">
                    <label for="id_categoria">Categoría</label>
                    <input type="text" id="id_categoria" name="id_categoria" value="{{ $ticket->categoria ? $ticket->categoria->nombre : 'No disponible' }}" readonly>
                </div>
                <div class="form-group">
                    <label for="id_sede">Sede</label>
                    <input type="text" id="id_sede" name="id_sede" value="{{ $ticket->sede ? $ticket->sede->nombre : 'No disponible' }}" readonly>
                </div>
                <div class="form-group">
                    <label for="id_campana">Campaña</label>
                    <input type="text" id="id_campana" name="id_campana" value="{{ $ticket->campana ? $ticket->campana->nombre : 'No disponible' }}" readonly>
                </div>
                <div class="form-group">
                    <label for="id_user">Asignado a:</label>
                    <input type="text" id="id_user" name="id_user" value="{{ $ticket->usuarioAsignado->username ?? 'No asignado' }}" readonly>
                </div>
           
            
            </div>

            <!-- Columna de Descripción -->
            <div class="form-section">
                <h3>Descripción de la Solicitud</h3>
                <div class="form-group">
                    <label for="asunto">Asunto</label>
                    <input type="text" id="asunto" name="asunto" value="{{ $ticket->asunto }}" readonly>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" readonly>{{ $ticket->descripcion }}</textarea>
                </div>
                <h3>Mas info:</h3>
               <p><strong>Fecha y Hora de Creacion: </strong>{{$ticket->creado}}</p> 
               <p><strong>Fecha y Hora de Asignacion: </strong>{{$ticket->asignado}}</p>
               <p><strong>Fecha y Hora de Edicion: </strong>{{$ticket->actualizado}}</p>
               <p><strong>Fecha y Hora de Cierre: </strong>{{$ticket->cerrado}}</p>
               @if ($ticket->estado === 'Pausado' && $ticket->motivoPausado)
               <p><strong>Fecha y Hora de Pausa: </strong>{{$ticket->pausado}}</p>
                    <p><strong>Motivo de la Pausa: </strong>{{ $ticket->motivoPausado->nombre }}</p> 
                @endif
                <h3>Archivos Adjuntos:</h3>
                @if (!empty($ticket->archivo) && $ticket->archivo != '[]')
                    <div class="file-list">
                  
                    @foreach (json_decode($ticket->archivo) as $archivo)
                    <a href="{{ asset('archivos/' . $archivo) }}" target="_blank">{{ $archivo }}</a>
                @endforeach

                    </div>
                @else
                    <p>No se han adjuntado archivos.</p>
                @endif
            </div>
        </div>
    </div>
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
        h2{
            text-align:center
        }
     
        .form-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-top:-3px;
            margin-bottom:-4px;;
        }

        /* Diseño de Columnas */
        .form-columns {
            display: flex;
            gap: 20px;
        }

        .form-section {
            flex: 1;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
        }

        .form-section h3 {
            font-size: 18px;
            color: #333;
            border-bottom: 2px solid #f7a731;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            font-size: 14px;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }

        .form-group textarea {
            min-height: 80px;
        }

    
        .estado {
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 23px;
            font-size:13px;
        }
        .prioridad {
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size:13px;
        }
        .icono{
            color:#f7a731;
        }
        .icono:hover{
            color:#047b8d;
        }
        .estado span.green { color: green; }
        .estado span.grey { color: grey; }
        .estado span.blue { color: blue; }
        .estado span.purple { color: purple; }
        .prioridad span.red { color: red; }
        .prioridad span.yellow { color: orange; }
        .prioridad span.gray { color: gray; }
    </style>
</body>
</html>
