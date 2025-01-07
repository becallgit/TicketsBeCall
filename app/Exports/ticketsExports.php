<?php

namespace App\Exports;
use Illuminate\Support\Collection;
use App\Models\Ticket; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ticketsExports implements FromCollection, WithHeadings
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
        // Aquí preparamos los datos para exportar, incluidas las relaciones
        $tickets = $this->data->map(function ($ticket) {
            return [
                'SOLICITANTE' => $ticket->solicitante,
                'PARA' => $ticket->team ? $ticket->team->nombre : 'No asignado',
                'ASIGNADO A' => $ticket->usuarioAsignado ? $ticket->usuarioAsignado->username : 'No asignado',
                'TIPO' => $ticket->tipo ? $ticket->tipo->nombre : 'No asignado',
                'CATEGORIA' => $ticket->categoria ? $ticket->categoria->nombre : 'No disponible',
                'SEDE' => $ticket->sede ? $ticket->sede->nombre : 'No disponible',
                'CAMPAÑA' => $ticket->campana ? $ticket->campana->nombre : 'No disponible',
                'ASUNTO' => $ticket->asunto,
                'DESCRIPCION' => $ticket->descripcion,
                'ESTADO' => $ticket->estado,
                'PRIORIDAD' => $ticket->prioridad,
                'FECHA CREACION' => $ticket->creado,
                'FECHA ACTUALIZACION' => $ticket->actualizado,
                'FECHA ASIGNACION' => $ticket->asignado,
                'FECHA PAUSA' => $ticket->pausado,
                'MOTIVO PAUSA'=> $ticket->motivoPausado ? $ticket->motivoPausado->nombre : 'No disponible',
                'FECHA CIERRE' => $ticket->cerrado,
                'CAMBIO PAUSADO A ABIERTO'=> $ticket->cambio_estado,
                'TIEMPO DE ASIGNACION' => $ticket->tiempo_asignacion,
                'TIEMPO DE RESPUESTA TOTAL'=>$ticket->tiempo_respuesta
            ];
        });

        return $tickets;
    }
    public function headings(): array
    {
       
        return [
            'SOLICITANTE',
            'PARA',
            'ASIGNADO A',
            'TIPO',
            'CATEGORIA',
            'SEDE',
            'CAMPAÑA',
            'ASUNTO',
            'DESCRIPCION',
            'ESTADO',
            'PRIORIDAD',
            'FECHA CREACION',
            'FECHA ACTUALIZACION',
            'FECHA ASIGNACION',
            'FECHA PAUSA',
            'MOTIVO PAUSA',
            'FECHA CIERRE',
            'CAMBIO PAUSADO A ABIERTO',
            'TIEMPO DE ASIGNACION',
            'TIEMPO DE RESPUESTA TOTAL'

        ];
    }
}
