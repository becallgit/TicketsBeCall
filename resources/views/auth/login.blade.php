<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo.png') }}">
    <title>INICIAR SESION</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<style>
        body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #f3f4f6;
        flex-direction: column
    }
    .imagen{
      margin-top:-120px;
      margin-bottom:30px;
    }
    .form {
    background-color: #fff;
    display: block;
    padding: 1rem;
    max-width: 350px;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .form-title {
    font-size: 1.25rem;
    line-height: 1.75rem;
    font-weight: 600;
    text-align: center;
    color: #000;
    }

    .input-container {
    position: relative;
    }

    .input-container input, .form button {
    outline: none;
    border: 1px solid #e5e7eb;
    margin: 8px 0;
    }

    .input-container input {
    background-color: #fff;
    padding: 1rem;
    padding-right: 3rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    width: 300px;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .submit {
    display: block;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    padding-left: 1.25rem;
    padding-right: 1.25rem;
    background-color: #047b8d;
    color: #ffffff;
    font-size: 0.875rem;
    line-height: 1.25rem;
    font-weight: 500;
    width: 100%;
    border-radius: 0.5rem;
    text-transform: uppercase;
    }
    .submit:hover{
        opacity:0.8
    }

</style>

</head>
<body>
 

   
<img class="imagen" src="{{ asset('images/logolargo.png') }}" width="330">
<form method="POST" action="{{ route('login.custom') }}" class="form">

       <p class="form-title">TICKETS BE CALL</p>
        <div class="input-container">
            @csrf
            <input type="text" placeholder="Usuario de Active Directory" id="username"  name="username" required autofocus>
            @if ($errors->has('username'))
            <span class="text-danger">{{ $errors->first('username') }}</span>
            @endif
          <span>
          </span>
      </div>
      <div class="input-container">
      <input type="password" placeholder="Password" id="password"  name="password" required>
            @if ($errors->has('password'))
            <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif
        </div>
        @if(session('error'))
            <div  class="alert alert-danger">{{ session('error') }}</div>
        @endif
         <button type="submit" class="submit">
       INICIAR SESION
      </button>

      
    
   </form>
</body>

