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

    // Listar todas las preguntas (gestión)
    public function indexPreguntas()
    {
        $preguntas = Pregunta::with('area')->paginate(10);
        $areas = AreaPregunta::all(); // Para el filtro/selector
        return view('administrador.preguntas.index', compact('preguntas', 'areas'));
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
            'respuesta2' => 'required|string'
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

    // Mostrar una pregunta específica
    public function showPregunta($id)
    {
        $pregunta = Pregunta::with('area', 'apoyos')->findOrFail($id);
        return view('administrador.preguntas.show', compact('pregunta'));
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
            'respuesta2' => 'required|string'
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

    // Dashboard con estadísticas de preguntas
    public function dashboard()
    {
        $totalAreas = AreaPregunta::count();
        $totalPreguntas = Pregunta::count();
        $areasConPreguntas = AreaPregunta::has('preguntas')->withCount('preguntas')->get();
        $ultimasPreguntas = Pregunta::with('area')->latest()->take(5)->get();
        
        return view('administrador.preguntas.dashboard', compact('totalAreas', 'totalPreguntas', 'areasConPreguntas', 'ultimasPreguntas'));
    }
}