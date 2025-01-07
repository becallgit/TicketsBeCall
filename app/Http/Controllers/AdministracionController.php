<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Team;
use App\Models\Motivo_Pausa;
use App\Models\Campana;
use App\Models\Sede;
use App\Models\Categoria;
use App\Models\User;
use App\Models\Ticket_Asignado;
use App\Models\Tipo;
use Illuminate\Support\Facades\Auth;

class AdministracionController extends Controller
{
    // CAMPAÑAS
    public function ListadoCampanas(){
        $username = Auth::user()->username;
        $campanas = Campana::all();
        return view('administracion.listado-campana',compact('username','campanas'));
    }

    public function GuardarEdiarCampana(Request $request, $id){
        try{
            $campana = Campana::findOrFail($id);
            $campana->nombre = $request->nombre;
    
            $campana->save();
     
            return redirect()->route('listado.campana');
        }catch(Exception $e){
            Log::error('Error al editar la campana con id: '. $id ." ". $e->getMessage());
        }
      
    }

    public function AddCampana(Request $request){
        try{
            $campana = New Campana();
            $campana->nombre = $request->input('nombre');
            $campana->save();
    
            return redirect()->route('listado.campana');
        }catch(Exception $e) {
            Log::error('Error al agregar la campana: '. $e->getMessage());
        }
      
    }
    public function EliminarCampana($id){
        try{

            $campana = Campana::findOrFail($id);
            $campana->delete();
        
            return redirect()->route('listado.campana');
        }catch(Exception $e){
            Log::error('Error al eliminar la campana con id: '. $id ." ".$e->getMessage());
        }
  
    }
    ////////////////////////////////////////////////

    // CATEGORIAS
        public function ListadoCategorias(){
            $username = Auth::user()->username;
            $categorias = Categoria::all();
            return view('administracion.listado-categoria',compact('username','categorias'));
        }
    
        public function GuardarEdiarCategorias(Request $request, $id){
            try{
            $categorias = Categoria::findOrFail($id);
            $categorias->nombre = $request->nombre;
    
            $categorias->save();
     
            return redirect()->route('listado.categorias');
            }catch(Exception $e){
                Log::error('Error al editar la categoria con id: '. $id ." ". $e->getMessage());
            }
        }
    
        public function AddCategoria(Request $request){
            try{
            $categorias = New Categoria();
            $categorias->nombre = $request->input('nombre');
            $categorias->save();
    
            return redirect()->route('listado.categorias');
            }catch(Exception $e){
                Log::error('Error al agregar la categoria: '. $e->getMessage());
            }
        }
        public function EliminarCategoria($id){
            try{
            $categorias = Categoria::findOrFail($id);
            $categorias->delete();
        
            return redirect()->route('listado.categorias');
            }catch(Exception $e){
                Log::error('Error al eliminar la categoria con id: '. $id ." ".$e->getMessage());
            }
        }
        ////////////////////////////////////////////////

        // SEDES
               public function ListadoSedes(){
                $username = Auth::user()->username;
                $sedes = Sede::all();
                return view('administracion.listado-sede',compact('username','sedes'));
            }
        
            public function GuardarEdiarSedes(Request $request, $id){
                try{

                $sedes = Sede::findOrFail($id);
                $sedes->nombre = $request->nombre;
        
                $sedes->save();
         
                return redirect()->route('listado.sedes');
                }catch(Exception $e){
                    Log::error('Error al editar la sede con id: '. $id ." " .$e->getMessage());
                }
            }
        
            public function AddSede(Request $request){
                try{
                $sedes = New Sede();
                $sedes->nombre = $request->input('nombre');
                $sedes->save();
        
                return redirect()->route('listado.sedes');
                }catch(Exception $e){
                    Log::error('Error al agregar la sede: '. $e->getMessage());
                }
            }
            public function EliminarSede($id){
                try{
                $sedes = Sede::findOrFail($id);
                $sedes->delete();
            
                return redirect()->route('listado.sedes');
                }catch(Exception $e){
                    Log::error('Error al eliminar la sede con id: '. $id ." ".$e->getMessage());
                }
            }
            ////////////////////////////////////////////////


               // TIPOS
               public function ListadoTipos(){
                $username = Auth::user()->username;
                $tipos = Tipo::all();
                return view('administracion.listado-tipo',compact('username','tipos'));
            }
        
            public function GuardarEdiarTipos(Request $request, $id){
                try{
                $tipos = Tipo::findOrFail($id);
                $tipos->nombre = $request->nombre;
        
                $tipos->save();
         
                return redirect()->route('listado.tipos');
                }catch(Exception $e){
                    Log::error('Error al editar el tipo con id: '. $id ." " .$e->getMessage());
                }
            }
        
            public function AddTipo(Request $request){
                try{

              
                $tipos = New Tipo();
                $tipos->nombre = $request->input('nombre');
                $tipos->save();
        
                return redirect()->route('listado.tipos');
                } catch(Exception $e){
                    Log::error('Error al agregar el tipo: '. $e->getMessage());
                }
            }
            public function EliminarTipo($id){
                try{

            
                $tipos = Tipo::findOrFail($id);
                $tipos->delete();
            
                return redirect()->route('listado.tipos');
                }catch(Exception $e){
                    Log::error('Error al eliminar el tipo con id: '. $id ." ".$e->getMessage());
                }
            }
            ////////////////////////////////////////////////


               // MOTIVOS
               public function ListadoMotivos(){
                $username = Auth::user()->username;
                $motivos = Motivo_Pausa::all();
                return view('administracion.listado-motivo',compact('username','motivos'));
            }
        
            public function GuardarEdiarMotivos(Request $request, $id){
                try{

                $motivos = Motivo_Pausa::findOrFail($id);
                $motivos->nombre = $request->nombre;
        
                $motivos->save();
         
                return redirect()->route('listado.motivos');
                }catch(Exception $e){
                    Log::error('Error al editar el motivo con id: '. $id ." " .$e->getMessage());
                }
            }
        
            public function AddMotivo(Request $request){
                try{

        
                $motivos = New Motivo_Pausa();
                $motivos->nombre = $request->input('nombre');
                $motivos->save();
        
                return redirect()->route('listado.motivos');
                }catch(Exception $e){
                    Log::error('Error al agregar el motivo: '. $e->getMessage());
                }
            }
            public function EliminarMotivo($id){
                try{

                $motivos = Motivo_Pausa::findOrFail($id);
                $motivos->delete();
            
                return redirect()->route('listado.motivos');
                }catch(Exception $e){
                    Log::error('Error al eliminar el motivo con id: '. $id ." ".$e->getMessage());
                }
            }
            ////////////////////////////////////////////////
}
