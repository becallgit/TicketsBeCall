<?php

namespace App\Http\Controllers;
use Hash;
use Session;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class Authcontroller extends Controller
{


    public function index(){
        return view('auth.login');
    }

    public function login(Request $request)
    {try {
        $apiUrl = 'https://europa.appsbecallgroup.com:8888/endpoint/loginad';
        $client = new Client();
        $username = $request->input('username'); 
        $password = $request->input('password');
        $data = [
            "token" => env("AD_TOKEN_SCRIPT_POWERSHELL"),
            'user' => $username,
            'password' => $password,
            'grupo' => "informatica",
        ];

        $auth = ['veronica.sanchez', 'N00liva2025**'];

        $response = $client->post($apiUrl, [
            'json' => $data,
            'auth' => $auth,
            'verify' => false,
            'timeout' => 10, 
        ]);

        $statusCode = $response->getStatusCode();
        $apiResponse = json_decode($response->getBody(), true);

   
        $ok = $response->getReasonPhrase();
        if ($statusCode == 200 || $ok !== "OK") {
           $user = User::where('username', $username)->first(); 
          
            if($user){
                $userID = $user->id;
                Auth::loginUsingId($userID);
                return redirect()->route('dashboard')->with('ok', 'conexion');
            }else{
                //nos conectamos a userinfo para obtener los datos del usuario y añadirlos a la tabla users
                $apiUserInfo = 'https://europa.appsbecallgroup.com:8888/endpoint/userinfo';

        
                $datos = [
                    'token' => env("AD_TOKEN_SCRIPT_POWERSHELL"),
                    'user' =>  $username,
                ];
        
                $responseUserInfo = $client->post($apiUserInfo, [
                    'json' => $data,
                    'auth' => $auth,
                    'verify' => false,
                    'timeout' => 10
                
                ]);
                $ResponseInfo = json_decode($responseUserInfo->getBody(), true);

                $email = $ResponseInfo['email'];

                $telefono = $ResponseInfo['telefono'];

                if (isset($ResponseInfo['grupo']) && stripos($ResponseInfo['grupo'], 'Informatica') !== false) {
                    $usuario = New User();
                    $usuario->username = $username;
                    $usuario->password = Hash::make($password);
                    $usuario->email = $email;
                    $usuario->team_id = 1;
                    $usuario->rol = "admin";
                    $usuario->save();
                    
                    $userID = $usuario->id;
                    Auth::loginUsingId($userID);
                }else if (isset($ResponseInfo['grupo']) && stripos($ResponseInfo['grupo'], 'BI') !== false) {
                    $usuario = New User();
                    $usuario->username = $username;
                    $usuario->password = Hash::make($password);
                    $usuario->email = $email;
                    $usuario->team_id = 2;
                    $usuario->rol = "bi";
                    $usuario->save();

                    $userID = $usuario->id;
                    Auth::loginUsingId($userID);
                }else{
                    $usuario = New User();
                    $usuario->username = $username;
                    $usuario->password = Hash::make($password);
                    $usuario->email = $email;
                    $usuario->team_id = 3;
                    $usuario->rol = "agente";
                    $usuario->save();

                    $userID = $usuario->id;
                    Auth::loginUsingId($userID);
                }
            
                return redirect()->route('dashboard')->with('ok', 'conexion');
            }
      
        }

    } catch (ConnectException $e) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')->with('ok', 'conectado');
        }
        Log::error("Error de conexion: {$e->getMessage()}");
   
        $request->session()->flash('error', 'Usuario o contraseña incorrecto');
        return redirect()->route('ver.login')->withSuccess('Login details are not valid');

    } catch (RequestException $e) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')->with('ok', 'conectado');
        }
        Log::error("Error al acceder a la API:\n{$e->getMessage()}");
        // $ip = request()->ip(); 
        
        // $ipsbloqueadas = new Ips_Bloqueadas();
        // $ipsbloqueadas->ip = $ip ;
        // $ipsbloqueadas->save();
        $request->session()->flash('error', 'Usuario o contraseña incorrecto');
        return redirect()->route('login')->withSuccess('Login details are not valid');
        
    }
}

public function signOut() {
    return view("auth.login");
}
}
