<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tipo_Formacion;
use App\Models\Forms_Formacion;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\formsExports;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Mail\nuevoforms;
use Illuminate\Support\Facades\Mail;
class FormacionController extends Controller
{
    public function VerForms(){
        $username = Auth::user()->username;
        $tipos = Tipo_Formacion::all();
        return view("forms.ver-forms",compact("username","tipos"));
    }

    public function ListadoForms(Request $request)
    {
        $username = Auth::user()->username;
        $tipos = Tipo_Formacion::all();
    
        $forms = Forms_Formacion::when($request->id, function ($query, $id) {
                return $query->where('id', $id);
            })
            ->when($request->cargo, function ($query, $cargo) {
                return $query->where('cargo', 'like', "%{$cargo}%");
            })
            ->when($request->fecha, function ($query, $fecha) {
                return $query->whereDate('fecha', $fecha);
            })
            ->when($request->id_tipo_formacion, function ($query, $id_tipo_formacion) {
                return $query->whereHas('tipo', function ($q) use ($id_tipo_formacion) {
                    $q->where('id', $id_tipo_formacion);
                });
            })
            ->when($request->detalle_formacion, function ($query, $detalle_formacion) {
                return $query->where('detalle_formacion', 'like', "%{$detalle_formacion}%");
            })
            ->when($request->formacion_inicial, function ($query, $formacion_inicial) {
                return $query->where('formacion_inicial', 'like', "%{$formacion_inicial}%");
            })
            ->when($request->observaciones, function ($query, $observaciones) {
                return $query->where('observaciones', 'like', "%{$observaciones}%");
            })
            ->when($request->estado, function ($query, $estado) {
                return $query->where('estado', 'like', "%{$estado}%");
            })
            ->Paginate(10);
    
        return view('forms.listado-forms', compact('username', 'forms','tipos'));
    }
    

    public function SaveForms(Request $request){
        try{
            $forms = New Forms_Formacion();
            $forms->nombre = Auth::user()->username;
            $forms->cargo = $request->input('cargo');
            $forms->fecha = Carbon::now()->format('Y-m-d');
            $forms->id_tipo_formacion = $request->input('id_tipo_formacion');
            $forms->detalle_formacion = $request->input('detalle_formacion');
            $forms->formacion_inicial = $request->input('formacion_inicial');
            $forms->estado = "Abierta";
            $forms->observaciones = $request->input('observaciones');
            $forms->save();
    
            $enlace = "http://127.0.0.1:8000/ver/$forms->id";
            Mail::to("veronica.sanchez@becallgroup.com")->send(new nuevoforms($enlace));
    
            return redirect()->route('listado.forms');
        }catch(Exception $e){
            Log::error('Error al crear una formacion: ' . $e->getMessage());
        }
       
    }

    public function verEditar($id){
        $username = Auth::user()->username;
        $form= Forms_Formacion::findOrFail($id);
        $tipos = Tipo_Formacion::all();
        return view("forms.editar-forms",compact("username","form","tipos"));
    }

    public function guardarEditar(Request $request, $id){
        try{
            $form= Forms_Formacion::findOrFail($id);
            $form->cargo = $request->cargo;
            $form->fecha = $request->fecha;
            $form->id_tipo_formacion = $request->id_tipo_formacion;
            $form->detalle_formacion = $request->detalle_formacion;
            $form->formacion_inicial = $request->formacion_inicial;
            $form->observaciones = $request->observaciones;
            $form->save();
            return redirect()->route('listado.forms');
        }catch(Exception $e){
            Log::error('Error al editar una formacion: ' . $e->getMessage());
        }
    
    }

    public function Eliminar($id){
        try{
            $form = Forms_Formacion::findOrFail($id);
            $form ->delete();
        
            return redirect()->route('listado.forms');
        }catch(Exception $e){
            Log::error('Error al eliminar una formacion: ' . $e->getMessage());
        }
   
    }

    public function Ver($id){
        $username = Auth::user()->username;
        $form = Forms_Formacion::findOrFail($id);

        return view("forms.vista",compact("username","form"));
    }

    public function cerrarFormacion($id){
        try{
            $form = Forms_Formacion::find($id);
            if ($form) {
                $form->estado = 'Cerrada';
                $form->save();
            }
            return redirect()->back()->with('success', 'ticket cerrado.');
        }catch(Exception $e){
            Log::error('Error al cerrar foormacion: ' . $e->getMessage());
        }
       
    }

    public function exportForms()
    {
        try{

        
        $data = Forms_Formacion::with(['tipo'])
                      ->select('id', 'nombre','cargo','fecha','id_tipo_formacion','detalle_formacion','formacion_inicial','observaciones','estado')
                      ->get();

        $actual = Carbon::now()->format('d-m-Y');
        return Excel::download(new formsExports($data), "Exportacion-Forms-$actual.xlsx");
        }catch(Exception $e){
            Log::error('Error al exportar los forms a excel: ' . $e->getMessage());
        }
    }
}
