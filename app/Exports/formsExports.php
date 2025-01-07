<?php

namespace App\Exports;
use Illuminate\Support\Collection;
use App\Models\Forms_Formacion; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class formsExports implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }
    public function collection()
    {
    
        $forms = $this->data->map(function ($forms) {
            return [
                'SOLICITANTE' => $forms->nombre,
                'CARGO' => $forms->cargo,
                'FECHA' => $forms->fecha,
                'TIPO DE FORMACION' => $forms->tipo ? $forms->tipo->nombre : 'No asignado',
                'DETALLE DE LA FORMACION' => $forms->detalle_formacion,
                'FORMACION INICIAL' =>$forms->formacion_inicial,
                'OBSERVACIONES' => $forms->observaciones,
                'ESTADO'=> $forms->estado,

            ];
        });

        return $forms;
    }
    public function headings(): array
    {
       
        return [
            'SOLICITANTE',
            'CARGO',
            'FECHA',
            'TIPO DE FORMACION',
            'DETALLE DE LA FORMACION',
            'FORMACION INICIAL',
            'OBSERVACIONES',
            'ESTADO',
           

        ];
    }
}
