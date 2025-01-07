<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LISTADO CATEGORIAS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')

    <div class="container">


<h2>LISTADO DE CATEGORIAS <button id="add" class="button icono" title="Añadir Categoria"> <i class="fa-solid fa-plus" style="font-size:25px;"></i></button>
</h2>

        <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categorias as $categoria)
                <tr data-id="{{ $categoria->id }}">
                    <td>{{ $categoria->id }}</td>
                    <td class="nombre">
                            <span class="nombre-campana">{{ $categoria->nombre }}</span>
                            <form class="form-editar" style="display: none;" action="{{ route('guardar.editar.categorias',$categoria->id)}}" method="POST">
                                @csrf
                                <input type="text" name="nombre" value="{{ $categoria->nombre }}" required><br>
                                <button type="submit" class="edit"><i class="fa-regular fa-floppy-disk"></i> Guardar</button>
                                <button type="button" class="cancelar edit"><i class="fa-solid fa-xmark"></i> Cancelar</button>
                            </form>
                        </td>
                    
                        <td class="acciones">
                            <button type="button" class="editar icono" title="Editar Categoria"><i class="fa-solid fa-pen-to-square"></i></button>
                            @if(Auth::user()->rol == "admin")
                            |&nbsp;
                            <form action="/categoriasdel/{{ $categoria->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('delete')
                                <button type="submit" class="icono borrar" title="Eliminar Categoria"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            @endif
                        </td>

                      
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        @if ($categorias->isEmpty())
            <p class="notickets">No hay categorias para mostrar</p>
        @endif
    </div>
<div id="modal-asignar" style="display: none;">
    <div class="modal-content">

        <form id="form-add-campana" method="POST" action="{{ route('guardar.add.categorias') }}">
            @csrf
            <label for="campana-select">Categoria:</label>
            <input type="text" name="nombre">
            <button type="submit">Añadir Categoría</button>
            <button type="button" id="cancelar-modal">Cancelar</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('add').addEventListener('click', function() {
        document.getElementById('modal-asignar').style.display = 'flex';
    });

    document.getElementById('cancelar-modal').addEventListener('click', function() {
        document.getElementById('modal-asignar').style.display = 'none';
    });
</script>

    <script>

        


        document.querySelectorAll('.editar').forEach(function(button) {
            button.addEventListener('click', function() {
           
                const row = button.closest('tr');
                const nombreCampo = row.querySelector('.nombre-campana');
                const formEditar = row.querySelector('.form-editar');

            
                nombreCampo.style.display = 'none';
                formEditar.style.display = 'inline-block';
            });
        });


        document.querySelectorAll('.cancelar').forEach(function(button) {
            button.addEventListener('click', function() {
                const formEditar = button.closest('.form-editar');
                const nombreCampo = formEditar.previousElementSibling;

      
                nombreCampo.style.display = 'inline';
                formEditar.style.display = 'none';
            });
        });
    </script>
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

    
        .tabla-contenedor {
            display: flex;
            justify-content: center; 
            align-items: center; 
            margin-top: 20px; 
        }

        table {
            width: 50%; 
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

        tbody tr {
            background-color: #f9f9f9; 
            cursor: pointer; 
        }

        tbody tr:hover {
            background-color: #f1f1f1;
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
        .sinasignar{
           border:none;
           padding:7px;
           border-radius:5px;
         cursor:pointer
        }
        .sinasignar:hover{
            background-color: white;
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
            border:none;
            background-color:transparent;
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
        input{            
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
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

   /* Filter styles */
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
        h2{
            text-align:center
        }
        .edit{
            background-color:transparent;
            border:2px solid  #047b8d;
            padding:3px;
            border-radius:3px;
            color : #047b8d;
            cursor:pointer;
            margin-top:4px;
        }
        .edit:hover{
            background-color: #047b8d;
            color:white;
        }
    </style>
</html>
