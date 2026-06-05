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
    
    // Vista pública de preguntas frecuentes
    public function preguntas()
    {
        $preguntas = Pregunta::with('area')->get();
        return view('administrador.preguntas', compact('preguntas'));
    }

    // Listar todas las preguntas (gestión) CON FILTROS Y ORDENAMIENTO
    public function indexPreguntas(Request $request)
    {
        $query = Pregunta::with('area');
        
        // Filtro por búsqueda (pregunta)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('pregunta', 'LIKE', "%{$search}%");
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
        
        $preguntas = $query->paginate(10)->appends($request->all());
        
        $areas = AreaPregunta::all();
        
        // Estadísticas para el dashboard
        $totalPreguntas = Pregunta::count();
        $totalAreas = AreaPregunta::count();
        $preguntasActivas = Pregunta::count();
        $preguntasConJustificacion = Pregunta::whereNotNull('justificacion')->count();
        
        return view('administrador.preguntas.index', compact('preguntas', 'areas', 'totalPreguntas', 'totalAreas', 'preguntasActivas', 'preguntasConJustificacion'));
    }

    // Mostrar formulario de creación de pregunta
    public function createPregunta()
    {
        $areas = AreaPregunta::all();
        return view('administrador.preguntas.create', compact('areas'));
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
            
            return view('administrador.preguntas.show', compact('pregunta'));
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

    // Mostrar formulario de edición de pregunta
    public function editPregunta($id)
    {
        $pregunta = Pregunta::findOrFail($id);
        $areas = AreaPregunta::all();
        return view('administrador.preguntas.edit', compact('pregunta', 'areas'));
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

    // ==================== MÉTODO PARA VER ÁREAS (SOLO LECTURA) ====================

    // Ver áreas existentes (solo consulta, sin CRUD)
    public function verAreas()
    {
        $areas = AreaPregunta::withCount('preguntas')->get();
        $totalPreguntas = Pregunta::count();
        return view('administrador.areas.ver', compact('areas', 'totalPreguntas'));
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

    // Dashboard con estadísticas de preguntas
    public function dashboard()
    {
        $totalAreas = AreaPregunta::count();
        $totalPreguntas = Pregunta::count();
        $areasConPreguntas = AreaPregunta::has('preguntas')->withCount('preguntas')->get();
        $ultimasPreguntas = Pregunta::with('area')->latest()->take(5)->get();
        $preguntasConJustificacion = Pregunta::whereNotNull('justificacion')->count();
        $preguntasSinJustificacion = Pregunta::whereNull('justificacion')->count();
        
        return view('administrador.preguntas.dashboard', compact(
            'totalAreas', 
            'totalPreguntas', 
            'areasConPreguntas', 
            'ultimasPreguntas',
            'preguntasConJustificacion',
            'preguntasSinJustificacion'
        ));
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