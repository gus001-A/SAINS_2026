<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pregunta;
use App\Models\AreaPregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PreguntaController extends Controller
{
    // ==================== MÉTODOS PARA PREGUNTAS ====================

    // Listar todas las preguntas (gestión) CON FILTROS Y ORDENAMIENTO
    public function indexPreguntas(Request $request)
    {
        $query = Pregunta::with('area');
        
        // Filtro por búsqueda (pregunta)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('pregunta', 'LIKE', "%{$search}%");
        }

        // Filtro por respuesta correcta
        if ($request->filled('respuesta_correcta')) {
            $query->where('respuesta_correcta', 'LIKE', "%{$request->respuesta_correcta}%");
        }

        // Filtro por área
        if ($request->filled('id_area')) {
            $query->where('id_area', $request->id_area);
        }
        
        // Filtro por justificación (si tiene o no)
        if ($request->filled('has_justificacion')) {
            if ($request->has_justificacion == 'si') {
                $query->whereNotNull('justificacion');
            } elseif ($request->has_justificacion == 'no') {
                $query->whereNull('justificacion');
            }
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Permitir ordenamiento solo por columnas válidas
        $allowedSorts = ['id', 'pregunta', 'respuesta_correcta', 'respuesta1', 'respuesta2', 'id_area', 'justificacion'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }
        
        $preguntas = $query->paginate(10)->withQueryString();

        $preguntas->getCollection()->transform(fn ($p) => [
            'id' => $p->id,
            'id_area' => $p->id_area,
            'area' => $p->area?->nombre,
            'pregunta' => $p->pregunta,
            'respuesta_correcta' => $p->respuesta_correcta,
            'respuesta1' => $p->respuesta1,
            'respuesta2' => $p->respuesta2,
            'justificacion' => $p->justificacion,
        ]);

        return \Inertia\Inertia::render('Admin/Preguntas/Index', [
            'preguntas' => $preguntas,
            'areas' => AreaPregunta::orderBy('nombre')->get(['id', 'nombre']),
            'stats' => [
                'total' => Pregunta::count(),
                'areas' => AreaPregunta::count(),
                'conJustificacion' => Pregunta::whereNotNull('justificacion')->count(),
                'sinJustificacion' => Pregunta::whereNull('justificacion')->count(),
            ],
            'filters' => [
                'search' => $request->search,
                'respuesta_correcta' => $request->respuesta_correcta,
                'id_area' => $request->id_area ? (int) $request->id_area : null,
                'has_justificacion' => $request->has_justificacion,
            ],
        ]);
    }

    public function createPregunta()
    {
        return redirect()->route('admin.preguntas.index');
    }

    // Guardar nueva pregunta
    public function storePregunta(Request $request)
    {
        $request->validate([
            'id_area' => 'required|exists:area_preguntas,id',
            'pregunta' => 'required|string',
            'respuesta_correcta' => 'required|string',
            'respuesta1' => 'required|string',
            'respuesta2' => 'required|string',
            'justificacion' => 'nullable|string'
        ]);

        try {
            Pregunta::create($request->all());
            return redirect()->route('admin.preguntas.index')
                ->with('success', 'Pregunta creada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la pregunta: ' . $e->getMessage());
        }
    }

    // Mostrar una pregunta específica (API para modal)
    public function showPregunta($id)
    {
        try {
            $pregunta = Pregunta::with('area')->findOrFail($id);
            
            // Siempre devolver JSON para peticiones AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $pregunta->id,
                    'pregunta' => $pregunta->pregunta,
                    'respuesta_correcta' => $pregunta->respuesta_correcta,
                    'respuesta1' => $pregunta->respuesta1,
                    'respuesta2' => $pregunta->respuesta2,
                    'justificacion' => $pregunta->justificacion,
                    'area' => $pregunta->area ? [
                        'id' => $pregunta->area->id,
                        'nombre' => $pregunta->area->nombre
                    ] : null
                ]);
            }
            
            return redirect()->route('admin.preguntas.index');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pregunta no encontrada'
                ], 404);
            }
            abort(404);
        }
    }

    public function editPregunta($id)
    {
        return redirect()->route('admin.preguntas.index');
    }

    // Actualizar pregunta
    public function updatePregunta(Request $request, $id)
    {
        $request->validate([
            'id_area' => 'required|exists:area_preguntas,id',
            'pregunta' => 'required|string',
            'respuesta_correcta' => 'required|string',
            'respuesta1' => 'required|string',
            'respuesta2' => 'required|string',
            'justificacion' => 'nullable|string'
        ]);

        try {
            $pregunta = Pregunta::findOrFail($id);
            $pregunta->update($request->all());
            return redirect()->route('admin.preguntas.index')
                ->with('success', 'Pregunta actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la pregunta: ' . $e->getMessage());
        }
    }

    // Eliminar pregunta
    public function destroyPregunta($id)
    {
        try {
            $pregunta = Pregunta::findOrFail($id);
            
            // Verificar si tiene apoyos asociados
            if($pregunta->apoyos()->count() > 0) {
                return redirect()->route('admin.preguntas.index')
                    ->with('error', 'No se puede eliminar la pregunta porque tiene apoyos asociados');
            }
            
            $pregunta->delete();
            return redirect()->route('admin.preguntas.index')
                ->with('success', 'Pregunta eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.preguntas.index')
                ->with('error', 'Error al eliminar la pregunta: ' . $e->getMessage());
        }
    }

    // ==================== MÉTODOS ADICIONALES ÚTILES ====================

    // Obtener preguntas por área (para API o filtros)
    public function getPreguntasByArea($areaId)
    {
        $preguntas = Pregunta::where('id_area', $areaId)->with('area')->get();
        return response()->json($preguntas);
    }

    // Obtener una pregunta específica con su justificación (para API)
    public function getPreguntaConJustificacion($id)
    {
        $pregunta = Pregunta::with('area')->findOrFail($id);
        return response()->json([
            'id' => $pregunta->id,
            'pregunta' => $pregunta->pregunta,
            'respuesta_correcta' => $pregunta->respuesta_correcta,
            'justificacion' => $pregunta->justificacion ?? "La respuesta correcta es: {$pregunta->respuesta_correcta}",
            'opciones' => [
                'A' => $pregunta->respuesta1,
                'B' => $pregunta->respuesta2,
                'C' => $pregunta->respuesta_correcta
            ],
            'area' => $pregunta->area->nombre ?? 'Sin área'
        ]);
    }

    // Método para exportar preguntas con justificación (útil para reportes)
    public function exportarPreguntasConJustificacion()
    {
        $preguntas = Pregunta::with('area')
            ->whereNotNull('justificacion')
            ->get();
        
        return response()->json($preguntas);
    }

    // Método para actualizar solo la justificación de una pregunta
    public function updateJustificacion(Request $request, $id)
    {
        $request->validate([
            'justificacion' => 'nullable|string'
        ]);

        try {
            $pregunta = Pregunta::findOrFail($id);
            $pregunta->justificacion = $request->justificacion;
            $pregunta->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Justificación actualizada correctamente',
                'justificacion' => $pregunta->justificacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la justificación: ' . $e->getMessage()
            ], 500);
        }
    }
}