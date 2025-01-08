<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMS</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   
</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')

    @php
    use Illuminate\Support\Str;
@endphp
    <div class="container">
    <h2 style="text-align:center;">FORMACIONES SOLICITADAS</h2>
    <form method="GET" action="{{ route('listado.forms') }}">
    <div class="filter-container">
        <input type="text" name="id" placeholder="ID" value="{{ request('id') }}">
        <input type="text" name="cargo" placeholder="Cargo" value="{{ request('cargo') }}">
        <input type="date" name="fecha" placeholder="Fecha" value="{{ request('fecha') }}">
        

        <select name="id_tipo_formacion">
            <option value="">Seleccionar tipo de formación</option>
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo->id }}" {{ request('id_tipo_formacion') == $tipo->id ? 'selected' : '' }}>
                    {{ $tipo->nombre }}
                </option>
            @endforeach
        </select>

        <input type="text" name="detalle_formacion" placeholder="Detalle formación" value="{{ request('detalle_formacion') }}">
        <input type="text" name="formacion_inicial" placeholder="Formación inicial" value="{{ request('formacion_inicial') }}">
        <input type="text" name="observaciones" placeholder="Observaciones" value="{{ request('observaciones') }}">
        <input type="text" name="estado" placeholder="Estado" value="{{ request('estado') }}">

        <button type="submit">Filtrar</button>
        <a href="{{ route('listado.forms') }}"><i class="fa-solid fa-eraser"></i> Limpiar</a>
    </div>
   
       
        <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Cargo</th>
                    <th>Fecha de Solicitud</th>
                    <th>Tipo de formacion</th>
                    <th>Detalle Formacion</th>
                    <th>Detalle Formacion incial</th>
                    <th>Observaciones</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($forms as $form)
                    <tr>

                        <td>{{ $form->id }}</td>
                        <td>{{ $form->nombre }}</td>
                        <td>{{ $form->cargo }}</td>
                        <td>{{ $form->fecha }}</td>
                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $form->tipo ? $form->tipo->nombre : 'No disponible' }}</td>
                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $form->detalle_formacion}}</td>
                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $form->formacion_inicial}}</td>
                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $form->observaciones }}</td>
                        <td>
                        @if($form->estado === 'Abierta') 
                        <span  style="color:green;font-weight:bold"><i class="fa-regular fa-circle"></i>&nbsp;&nbsp;{{$form->estado}}</span>
                        @elseif($form->estado === 'Cerrada') 
                            <span style="color:grey;font-weight:bold"><i class="fa-solid fa-circle-xmark"></i>&nbsp;&nbsp;{{$form->estado}}</span> 
                        @endif
                        </td>
                        <td class="acciones">
                        @if(Auth::user()->rol == "admin")
                            <a href="{{ route('ver.Editar.forms', $form->id) }}" class="icono" title="Editar Formacion"><i class="fa-solid fa-pen-to-square"></i></a> 
                    
                            |&nbsp;<form   action="/formsdel/{{ $form->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('delete')
                                <button type="submit" class="icono borrar"  title="Eliminar Formacion"><i class="fa-solid fa-trash"></i></button>
                            </form>&nbsp;|
                            @endif
                            <a href="{{ route('ver.form', $form->id) }}" class="icono"  title="Ver Formacion"><i class="fa-solid fa-eye"></i></a>&nbsp;|&nbsp;
                            <a href="{{ route('cerrar.formacion', $form->id) }}" class="icono"  title="Cerrar Formacion"><i class="fa-solid fa-door-closed"></i></a>

                        </td>
                        </td>
                      
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        @if ($forms->isEmpty())
            <p class="notickets">Aun no se han solicitado formaciones nuevas.</p>
        @endif
    </div>

    <div style="display: flex; justify-content: center;">
    {{ $forms->links() }}
</div>


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
        select{
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
           width:300px;
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
