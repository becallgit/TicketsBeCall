<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLICITUTES TOTALES</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')

    <div class="container">
        <h2 style="text-align:center;">Solicitudes Totales</h2>
        <form method="GET" action="{{ route('ver.solicitudes.departamento') }}">
    <div class="filter-container">
        <input type="text" name="id" placeholder="ID" value="{{ request('id') }}">
        <input type="text" name="solicitante" placeholder="Solicitante" value="{{ request('solicitante') }}">
        <input type="text" name="para" placeholder="Para" value="{{ request('para') }}">
        <input type="text" name="asignado_a" placeholder="Asignado a" value="{{ request('asignado_a') }}">
        <input type="text" name="tipo" placeholder="Tipo" value="{{ request('tipo') }}">
        <input type="text" name="categoria" placeholder="Categoría" value="{{ request('categoria') }}">
        <input type="text" name="sede" placeholder="Sede" value="{{ request('sede') }}">
        <input type="text" name="campana" placeholder="Campaña" value="{{ request('campana') }}">
        <input type="text" name="asunto" placeholder="Asunto" value="{{ request('asunto') }}">
        <input type="text" name="estado" placeholder="Estado" value="{{ request('estado') }}">
        <input type="date" name="fecha_creacion" placeholder="Fecha de Creación" value="{{ request('fecha_creacion') }}">
        
        <button type="submit">Filtrar</button>
        <a href="{{ route('ver.solicitudes.departamento') }}"><i class="fa-solid fa-eraser"></i> Limpiar</a>
    </div>
</form>
        <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>P</th>
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Para</th>
                    <th>Asignado a</th>
                    <th>Tipo</th>
                    <th>Categoría</th>
                    <th>Sede</th>
                    <th>Campaña</th>
                    <th>Asunto</th>
                    <th>Estado</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>
                        @if($ticket->prioridad === 'Alta') 
                        <span  style="color:red;"><i class="fa-solid fa-triangle-exclamation"></i></span>
                        @elseif($ticket->prioridad === 'Media') 
                            <span style="color:orange;"><i class="fa-solid fa-triangle-exclamation"></i></span> 
                        @elseif($ticket->prioridad === 'Baja') 
                            <span style="color:gray;"><i class="fa-solid fa-triangle-exclamation"></i></span>
                        @endif    

                        </td>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->solicitante }}</td>
                        <td>{{ $ticket->team ? $ticket->team->nombre : 'No asignado' }}</td>
                        <td>
                        @if ($ticket->usuarioAsignado)
                        <button type="button" class="btn-asignar asignado" data-ticket-id="{{ $ticket->id }}" data-team-id="{{ $ticket->team_id }}">
                        <i class="fa-solid fa-user-gear"></i><span class="nombre-asignado">   {{ $ticket->usuarioAsignado->username }}</span>
                        @else
                            <button type="button" class="btn-asignar sinasignar" data-ticket-id="{{ $ticket->id }}" data-team-id="{{ $ticket->team_id }}">
                              Sin Asignar
                           
                        @endif
                        </td>
                        <td>{{ $ticket->tipo ? $ticket->tipo->nombre : 'No disponible' }}</td>
                        <td>{{ $ticket->categoria ? $ticket->categoria->nombre : 'No disponible' }}</td>
                        <td>{{ $ticket->sede ? $ticket->sede->nombre : 'No disponible' }}</td>
                        <td>{{ $ticket->campana ? $ticket->campana->nombre : 'No disponible' }}</td>
                        <td>{{ $ticket->asunto }}</td>
                        <td>
                        @if($ticket->estado === 'Abierto') 
                        <span  style="color:green;font-weight:bold"><i class="fa-regular fa-circle"></i>&nbsp;&nbsp;{{$ticket->estado}}</span>
                        @elseif($ticket->estado === 'Cerrado') 
                            <span style="color:grey;font-weight:bold"><i class="fa-solid fa-circle-xmark"></i>&nbsp;&nbsp;{{$ticket->estado}}</span> 
                        @elseif($ticket->estado === 'En Curso') 
                            <span style="color:blue;font-weight:bold"><i class="fa-solid fa-circle"></i>&nbsp;&nbsp;{{$ticket->estado}}</span>
                            @elseif($ticket->estado === 'Pausado') 
                            <span style="color:purple;font-weight:bold"><i class="fa-solid fa-pause"></i>&nbsp;&nbsp;{{$ticket->estado}}</span>
                        @endif 
                        </td>
                        <td>{{ $ticket->creado}}</td>
                        <td class="acciones">
                        @if(Auth::user()->rol == "admin")
                            <a href="{{ route('ver.Editar', $ticket->id) }}" class="icono" title="Editar Ticket"><i class="fa-solid fa-pen-to-square"></i></a>
                        
                            |&nbsp;<form action="/ticketsdel/{{ $ticket->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('delete')
                                <button type="submit" class="icono borrar"  title="Eliminar Ticket"><i class="fa-solid fa-trash"></i></button>
                            </form>&nbsp;|
                            @endif
                            <a href="{{ route('ticket.mostrado', $ticket->id) }}" class="icono"  title="VerTicket"><i class="fa-solid fa-eye"></i></a>&nbsp;

                        </td>
                        </td>
                      
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        @if ($tickets->isEmpty())
            <p class="notickets">No tienes tickets asignados.</p>
        @endif
    </div>

    <div id="modal-asignar" style="display:none;">
        <div class="modal-content">
            <h2>Asignar Usuario</h2>
            <form id="form-asignar" action="{{ route('asignar') }}" method="POST">
                @csrf
                <input type="hidden" id="ticket-id" name="ticket_id">
                <label for="user-select">Selecciona un usuario:</label>
                <select id="user-select" name="id_user">
                    <option value="" label="Selecciona.."></option>
                </select>
                <button type="submit">Asignar</button>
                <button type="button" onclick="closeModal()">Cerrar</button>
            </form>
        </div>
    </div>

<script>
    document.querySelectorAll('.btn-asignar').forEach(button => {
        button.addEventListener('click', function() {
            const ticketId = this.getAttribute('data-ticket-id');
            console.log('Ticket ID antes de la llamada:', ticketId); 

            if (!ticketId) {
                console.error('No se encontró el ID del ticket.');
                return;
            }

            document.getElementById('ticket-id').value = ticketId;

            const url = '{{ route("tickets.get-users") }}?ticket_id=' + ticketId;
            console.log('URL de fetch:', url); 

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json(); 
            })
            .then(users => {
                console.log('Usuarios devueltos:', users); 

                if (!Array.isArray(users)) {
                    console.error('La respuesta no es un array:', users);
                    return;
                }

                const userSelect = document.getElementById('user-select');
                userSelect.innerHTML = '<option value="" label="Selecciona.."></option>'; 

          
                users.forEach(user => {
                    console.log('Usuario:', user);
                    const option = document.createElement('option');
                    option.value = user.id; 
                    option.textContent = user.username;
                    userSelect.appendChild(option);
                });

                
                document.getElementById('modal-asignar').style.display = 'flex';
            })
            .catch(error => {
                console.error('Error:', error); 
            });
        });
    });



    function closeModal() {
        document.getElementById('modal-asignar').style.display = 'none';
    }
</script>
<div style="display: flex; justify-content: center;">
    {{ $tickets->links() }}
</div>
@endsection


<style>
    nav.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin: 12px 0;
}

nav.pagination a,
nav.pagination span {
    display: inline-block;
    padding: 5px 10px;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    color: #047b8d;
    border: 2px solid #047b8d;
    border-radius: 8px;
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}

/* Estilo al pasar el ratón por los enlaces */
nav.pagination a:hover {
    background-color: #047b8d;
    color: #fff;
    border-color:#047b8d;
}

/* Página activa */
nav.pagination span.font-weight-bold {
    background-color: #047b8d;
    color: #fff;
    border-color: #047b8d;
    font-weight: bold;
}

/* Flechas deshabilitadas */
nav.pagination span.text-muted {
    color: #aaa;
    border-color: #ddd;
    cursor: not-allowed;
    background-color: #f9f9f9;
}

/* Ajuste de flechas */
nav.pagination a:first-child,
nav.pagination a:last-child {
    font-size: 18px;
    padding: 6px 9px;
 
}

/* Estilo general */
nav.pagination span,
nav.pagination a {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
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

       

        .tabla-contenedor {
            display: flex;
            justify-content: center; 
            align-items: center; 
            margin-top: 20px; 
        }

        table {
            width: 90%; 
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            border-radius: 10px; 
            overflow: hidden; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            transition: background-color 0.3s ease; 
        }

        th {
            background-color: gray; 
            color: white;
            font-weight: bold;
            text-transform: uppercase; 
        }

        .estado-abierto {
            color: green;
        }

        .estado-cerrado {
            color: gray;
        }

        .estado-progreso {
            color: blue;
        }
        #modal-asignar {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .asignado{
            padding:6px;
            border-radius:6px;
            border: none; 
            cursor:pointer;
   
        }
        .asignado:hover{
            background-color:white
        }

        .notickets{
            text-align:center;
        }

    
        .acciones {
            align-items: center; 
        }

        .icono {
            color: #047b8d;
            font-size: 15px; 
            text-decoration: none; 
            transition: color 0.3s ease; 
        }

        .icono:hover {
            color: #f7a731; 
        }

        .borrar {
            background: none; 
            border: none; 
            padding: 0; 
        }
      
        #modal-asignar {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); 
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .modal-content {
            background-color: #ffffff; 
            padding: 25px;
            border-radius: 10px; 
            width: 90%;
            max-width: 400px; 
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2); 
            text-align: center;
            animation: fadeIn 0.3s ease-in-out; 
        }

        .modal-content h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .modal-content label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #666;
        }

        #user-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #333;
        }

        .modal-content button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .modal-content button[type="submit"] {
            background-color: #047b8d;
            color: #ffffff;
        }

        .modal-content button[type="submit"]:hover {
            background-color: #035d6b;
        }

        .modal-content button[type="button"] {
            background-color: #e0e0e0;
            color: #333;
        }

        .modal-content button[type="button"]:hover {
            background-color: #cccccc; 
        }
        .sinasignar{
           border:none;
           padding:7px;
           border-radius:5px;
         cursor:pointer
        }
        .sinasignar:hover{
            background-color: white;
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            width:88%;
            margin: 0 auto; 
            justify-content:center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-container input[type="text"], .filter-container input[type="date"], .filter-container button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
           width: 120px;
        }

        .filter-container button[type="submit"] {
            background-color: #047b8d;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .filter-container button[type="submit"]:hover {
            background-color: #035d6b;
        }

        .filter-container a {
            text-decoration: none;
            color: #047b8d;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
        }
    </style>



</html>
