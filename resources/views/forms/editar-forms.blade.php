<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TICKETING</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   
</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')
   


    <form action="{{ route('guardar.editar.forms', $form->id) }}" method="POST">
    @csrf
    <div class="form-container">
        <div class="form-section">
            <h3>EDITAR LA SOLICITUD Nº {{$form->id}} de {{$form->nombre}}</h3>
            <div class="form-group-row">
                <div class="form-group">
                    <label for="cargo" class="negrita">Cargo</label>
                    <input type="text" id="cargo" name="cargo" value="{{$form->cargo}}" required>
                </div>
                <div class="form-group">
                    <label for="fecha" class="negrita">Fecha en la que se realiza la solicitud:</label>
                    <input type="date" id="fecha" name="fecha" value="{{$form->fecha}}"required>
                </div>
            </div>
            <div class="form-group">
                <label for="id_tipo_formacion" class="negrita">Tipo de Formacion Solicitada:</label>
                @foreach ($tipos as $tipo)
                    <div class="radio-group">
                        <input 
                            type="radio" 
                            id="tipo_{{ $tipo->id }}" 
                            name="id_tipo_formacion" 
                            value="{{ $tipo->id }}" 
                            {{ $form->id_tipo_formacion == $tipo->id ? 'checked' : '' }}>
                        <label for="tipo_{{ $tipo->id }}">{{ $tipo->nombre }}</label>
                    </div>
                @endforeach

            </div>
            <div class="form-group">
                <label for="detalle_formacion" class="negrita">DETALLE DE LA FORMACION: PROYECTO-CAMPAÑA - Observaciones de resumen de la formacion:</label>
                <textarea name="detalle_formacion" id="detalle_formacion">{{$form->detalle_formacion}}</textarea>
            </div>
            <div class="form-group">
                <label for="formacion_inicial" class="negrita">Cubrir solo en caso de formación inicial: detallar fecha de alta, número de recursos, horas semanales y horario de trabajo:</label>
                <textarea name="formacion_inicial" id="formacion_inicial"> {{$form->formacion_inicial}}</textarea>
            </div>
            <div class="form-group">
                <label for="observaciones" class="negrita">Detalla a continuación cualquier observación importante para ti:</label>
                <textarea name="observaciones" id="observaciones" >{{$form->observaciones}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="submit-btn">Editar Formacion</button>
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

     
       

.radio-group {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    gap: 10px; 
}

.radio-group input {
    margin: 0; 
}

.radio-group label {
    margin: 0;
}


.form-group > .radio-group {
    display: block; 
}

.radio-group {
    display: flex; 
    align-items: center;
    gap: 8px;
}
.form-group-row {
    display: grid;
    grid-template-columns: 1fr 1fr; 
    gap: 20px; 
    margin-bottom: 15px;
}

.form-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.form-section h3 {
    font-size: 20px;
    color: #333;
    text-align:center;
    margin-bottom: 15px;
    border-bottom: 2px solid #f7a731;
    padding-bottom: 5px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.form-group label {
    font-size: 16px;
    color: #333;
    margin-bottom: 5px;
  
}
.negrita{
    font-weight:bold;
}
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



 

    </style>
</body>
</html>
