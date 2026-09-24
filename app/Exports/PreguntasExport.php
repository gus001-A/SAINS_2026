<?php

namespace App\Exports;

use App\Models\Pregunta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PreguntasExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    public function collection()
    {
        return Pregunta::with('area')->orderBy('id')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Área', 'Pregunta', 'Respuesta Correcta', 'Respuesta 1', 'Respuesta 2', 'Justificación'];
    }

    public function map($pregunta): array
    {
        return [
            $pregunta->id,
            $pregunta->area?->nombre,
            $pregunta->pregunta,
            $pregunta->respuesta_correcta,
            $pregunta->respuesta1,
            $pregunta->respuesta2,
            $pregunta->justificacion,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 20,
            'C' => 60,
            'D' => 28,
            'E' => 28,
            'F' => 28,
            'G' => 45,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
