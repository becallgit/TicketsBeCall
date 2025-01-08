<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CREAR TICKET</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')
<form action="{{ route('guardar.ticket') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-container">
        <div class="form-section">
            <h3>Detalles</h3>
            <div class="form-group">
                <label for="team_id">Para<span class="oblig">*</span></label>
                <select id="team_id" name="team_id" required> 
                    <option value="" disabled selected>Para que departamento es la solicitud...</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}">
                            {{ $team->nombre }} 
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo<span class="oblig">*</span></label>
                <select id="tipo" name="id_tipo" required>
                    <option value="" disabled selected>Seleccione el tipo...</option>
                    @foreach ($tipos as $tipo)
                    <option value="{{ $tipo->id }}">
                        {{ $tipo->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_categoria">Categoría<span class="oblig">*</span></label>
                <select id="id_categoria" name="id_categoria" required>
                    <option value="" disabled selected>Seleccione la categoría...</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">
                            {{ $categoria->nombre }} 
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_sede">Sede<span class="oblig">*</span></label>
                <select id="id_sede" name="id_sede" required>
                    <option value="" disabled selected>Seleccione la sede...</option>
                    @foreach ($sedes as $sede)
                        <option value="{{ $sede->id }}">
                            {{ $sede->nombre }} 
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="id_campana">Campaña<span class="oblig">*</span></label>
                <select id="id_campana" name="id_campana" required>
                    <option value="" disabled selected>Seleccione la campaña...</option>
                    @foreach ($campanas as $campana)
                        <option value="{{ $campana->id }}">
                            {{ $campana->nombre }} 
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-section">
            <h3>Descripción de la Solicitud</h3>
            <div class="form-group">
                <label for="asunto">Asunto<span class="oblig">*</span></label>
                <input type="text" id="asunto" name="asunto" placeholder="Ingrese el asunto" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción <span class="oblig">*</span></label>
                <textarea id="descripcion" name="descripcion" placeholder="Describa su solicitud (Si en Tipo has seleccionado 'otros', indica aquí el tipo de solicitud)" maxlength="400" required></textarea>
            </div>
            <div class="form-group">
                <label for="archivos">Subir Archivos</label>
                <input type="file" id="archivos" name="archivos[]" multiple>
            </div>
        </div>

        @if(session('errors'))
            <div  class="alert alert-danger">{{ session('error') }}</div>
        @endif
<br>
        <div class="form-group">
            <button type="submit" class="submit-btn">Crear Ticket</button>
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
        .oblig{
            color: red;
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
            justify-content: center; /* Centra horizontalmente */
    align-items: center; /* Centra verticalmente */
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
        .alert{
            background-color:#f7c2c1;
            color:red;
            border:2px solid red;
            border-radius:5px;
            padding:7px;
            font-weight:bold;
            text-align:center;
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
    </style>
</body>
</html>
