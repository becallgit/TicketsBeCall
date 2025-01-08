<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>BE HELP</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')
<div class="equipos-container {{ $userRole != 'admin' || (isset($ticketsPorEquipo) && $ticketsPorEquipo->count() == 1) ? 'centrado' : '' }}">
    @if ($userRole == 'admin')
        @foreach ($ticketsPorEquipo as $equipo)
            @if ($equipo->nombre != 'Formacion') 
                <div class="equipo">
                    <div class="team-title">{{ $equipo->nombre }}</div>
                    <div class="tarjetas-container">
                        <div class="tarjeta">
                            <div class="texto">Solicitudes Abiertas</div>
                            <div class="numero">{{ $equipo->tickets_abiertos }}</div>
                        </div>
                        <div class="tarjeta">
                            <div class="texto">Solicitudes Sin Asignar</div>
                            <div class="numero">{{ $equipo->tickets_sin_asignar }}</div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @elseif ($userRole == 'agente')
        <div class="section">
            <h2>Bienvenid@ a la App de Tickets de Be Call</h2>

            <p>En esta aplicación puedes realizar tus solicitudes de manera rápida y sencilla. Si tienes alguna duda, accede al apartado de ayuda en el menú desplegable que aparece al pasar el cursor sobre tu nombre de usuario, ubicado en la parte superior derecha de la pantalla.</p>
        </div>
    @else
        <div class="equipo">
            <div class="team-title">{{ $teamName }}</div>
            <div class="tarjetas-container">
                <div class="tarjeta">
                    <div class="texto">Solicitudes Abiertas</div>
                    <div class="numero">{{ $ticketsAbiertos }}</div>
                </div>
                <div class="tarjeta">
                    <div class="texto">Solicitudes Sin Asignar</div>
                    <div class="numero">{{ $ticketsSinAsignar }}</div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
<script>
    var team_id = {{auth()->user()->team_id}}
    var user_id= {{auth()->user()->id}}
</script>

<style>
  

        @font-face {
            font-family: 'Nexa';
            src:url('{{ asset('fonts/Nexa-ExtraLight.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family:"Nexa", "Segoe UI Light";
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f5f5f5;
        }
        .equipos-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 50px; 
            max-width: 800px;
            width: 100%;
            margin: 0 auto; 
            margin-top:30px;
            justify-content: center;}
        .equipos-container.centrado {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px; 
        }
        .section {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        h1, h2, h3 {
            color: #047b8d;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 0.5em;
        }
        h2 {
            font-size: 1.8em;
            margin-bottom: 0.4em;
            text-align:center
        }
        h3 {
            font-size: 1.5em;
            margin-bottom: 0.3em;
        }
        p {
            line-height: 1.6;
            margin-bottom: 1.2em;
        }
        a {
            color: #f7a731;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .section h2 {
            border-bottom: 2px solid #f7a731;
            padding-bottom: 5px;
        }
        .equipo {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%; 
            max-width: 460px; 
            margin: 0 auto; 
        }

  
        .team-title {
            font-size: 20px;
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
            text-align: center;
        }

    
        .tarjetas-container {
            display: flex;
            gap: 15px;
            justify-content: center; 
        }

   
        .tarjeta {
            background-color: #fff;
            flex: 1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            max-width: 150px;
        }

        .tarjeta:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

    
        .texto {
            font-size: 16px;
            color: #333;
        }

        .numero {
            font-size: 20px;
            font-weight: bold;
            color: #4a90e2;
            background-color: #e6f2ff;
            padding: 8px 12px;
            border-radius: 8px;
        }


 

    </style>
</body>
</html>
