<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Manual de Ayuda</title>
    <style>
            @font-face {
            font-family: 'Nexa';
            src:url('{{ asset('fonts/Nexa-ExtraLight.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: "Nexa","Segoe UI Light";
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
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


        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
     
        .section {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .section h2 {
            border-bottom: 2px solid #f7a731;
            padding-bottom: 5px;
        }

   
    </style>
</head>
<body>
@extends('layouts.app')

@section('title', 'Inicio')
    
@section('content')

    <div class="container">
        <div class="section">
            <h2>Introducción</h2>
            <p>Bienvenid@ al manual de ayuda de la app de tickets de Be Call. En esta página podrás consultar, siempre que lo necesites, cómo utilizar la aplicación.</p>
            <p>Si tienes alguna duda o incidencia, envia un correo a <strong>soporte-tickets@becallgroup.com</strong>.</p>
            <p><strong>*Si encuentras algún error, por favor, adjunta una captura de pantalla del mismo cuando envíes el correo, junto con una breve explicación de lo sucedido.</strong></p>
        </div>

        <div class="section">
            <h2>Preguntas Frecuentes</h2>
            
            <h3>¿Cómo crear un nuevo ticket?</h3>
            <p>En cualquier página de la aplicación, en la parte superior derecha, junto a tu nombre de usuario, encontrarás el apartado <strong> + Nuevo Ticket</strong>.</p>
            <p>Al acceder a este enlace, se abrirá una página con un formulario donde podrás completar los campos según tu necesidad. Recuerda que los campos marcados con un <strong>*</strong> son obligatorios. Además, si deseas adjuntar archivos, puedes subir más de uno.</p>
            <p>Una vez creado el ticket, se generará una vista previa que será enviada al departamento seleccionado en el campo "Para". Este departamento se encargará de gestionar tu solicitud.</p>
            
            <h3>¿Cómo puedo ver el estado de mi solicitud?</h3>
            <p>En el menú superior encontrarás el apartado <strong><i class="fa-solid fa-paper-plane"></i> Peticiones Realizadas</strong>, donde podrás ver todas tus peticiones a cualquier departamento.</p>
            <p>Al acceder, aparecerá una tabla que muestra todas tus solicitudes. En ella podrás verificar si ya hay un empleado a cargo, el estado en el que se encuentra, entre otros detalles.</p>
            <p>Al final de cada fila de la tabla, encontrarás el apartado "ACCIONES", donde podrás:</p>
            <ul>
                <li>Marcar tu solicitud como cerrada si consideras que ya está resuelta, pulsando el ícono <i class="fa-solid fa-door-closed"></i>.</li>
                <li>Revisar nuevamente los detalles del ticket seleccionando el ícono <i class="fa-solid fa-eye"></i>.</li>
            </ul>
            <p>En la parte superior de la tabla hay una sección de filtros que te permite buscar por cualquier campo. Por ejemplo, si buscas un ticket creado ayer, puedes utilizar el filtro de fecha para seleccionar la de ayer.</p>
        </div>
    </div>
      
        

    @endsection
</body>
</html>
