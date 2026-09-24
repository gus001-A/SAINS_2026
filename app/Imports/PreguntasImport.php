<?php

namespace App\Imports;

use App\Models\AreaPregunta;
use App\Models\Pregunta;
use Illuminate\Support\Collection as SupportCollection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

/**
 * Columnas esperadas (mismo orden que PreguntasExport): ID, Área, Pregunta,
 * Respuesta Correcta, Respuesta 1, Respuesta 2, Justificación.
 * El ID es opcional: si coincide con una pregunta existente la actualiza,
 * si no (o viene vacío) crea una pregunta nueva. El área se busca por nombre
 * (sin distinguir mayúsculas) y se crea sola si no existe todavía.
 */
class PreguntasImport implements ToCollection, WithStartRow
{
    public int $creadas = 0;
    public int $actualizadas = 0;
    /** @var array<int, string> */
    public array $errores = [];

    private array $areasCache = [];

    public function startRow(): int
    {
        return 2;
    }

    public function collection(SupportCollection $rows)
    {
        foreach ($rows as $i => $row) {
            $numeroFila = $i + 2; // +2: startRow(2) + índice base 0

            if ($row->filter(fn ($v) => trim((string) $v) !== '')->isEmpty()) {
                continue; // fila vacía, se ignora en silencio
            }

            $id = trim((string) ($row[0] ?? ''));
            $areaNombre = trim((string) ($row[1] ?? ''));
            $pregunta = trim((string) ($row[2] ?? ''));
            $respuestaCorrecta = trim((string) ($row[3] ?? ''));
            $respuesta1 = trim((string) ($row[4] ?? ''));
            $respuesta2 = trim((string) ($row[5] ?? ''));
            $justificacion = trim((string) ($row[6] ?? ''));

            if ($areaNombre === '' || $pregunta === '' || $respuestaCorrecta === '' || $respuesta1 === '' || $respuesta2 === '') {
                $this->errores[] = "Fila {$numeroFila}: faltan datos obligatorios (área, pregunta o respuestas).";
                continue;
            }

            $idArea = $this->resolverArea($areaNombre);

            $datos = [
                'id_area' => $idArea,
                'pregunta' => $pregunta,
                'respuesta_correcta' => $respuestaCorrecta,
                'respuesta1' => $respuesta1,
                'respuesta2' => $respuesta2,
                'justificacion' => $justificacion !== '' ? $justificacion : null,
            ];

            $existente = $id !== '' && is_numeric($id) ? Pregunta::find((int) $id) : null;

            if ($existente) {
                $existente->update($datos);
                $this->actualizadas++;
            } else {
                Pregunta::create($datos);
                $this->creadas++;
            }
        }
    }

    private function resolverArea(string $nombre): int
    {
        $clave = mb_strtolower($nombre);

        if (isset($this->areasCache[$clave])) {
            return $this->areasCache[$clave];
        }

        $area = AreaPregunta::whereRaw('LOWER(nombre) = ?', [$clave])->first()
            ?? AreaPregunta::create(['nombre' => $nombre]);

        return $this->areasCache[$clave] = $area->id;
    }
}
