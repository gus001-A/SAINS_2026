<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamenGenerado;
use App\Models\Pregunta;
use App\Models\AreaPregunta;
use App\Models\ApoyoPregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExamenGeneradoController extends Controller
{
    // ==================== MÉTODOS PRINCIPALES ====================
    public function index(Request $request)
    {
        $query = ExamenGenerado::query();
        
        // 🔍 Filtro por búsqueda (ID o tipo de examen)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('tipo_examen', 'like', "%{$search}%");
            });
        }
        
        // 🔍 Filtro por tipo de examen
        if ($request->filled('tipo')) {
            $query->where('tipo_examen', $request->tipo);
        }
        
        // 🔍 Filtro por rango de preguntas
        if ($request->filled('rango')) {
            $rango = $request->rango;
            switch ($rango) {
                case '0-20':
                    $query->whereBetween('numero_preguntas', [0, 20]);
                    break;
                case '21-50':
                    $query->whereBetween('numero_preguntas', [21, 50]);
                    break;
                case '51-100':
                    $query->whereBetween('numero_preguntas', [51, 100]);
                    break;
                case '100+':
                    $query->where('numero_preguntas', '>', 100);
                    break;
            }
        }
        
        // 🔍 Ordenamiento
        switch ($request->get('orden', 'reciente')) {
            case 'antiguo':
                $query->orderBy('created_at', 'asc');
                break;
            case 'preguntas':
                $query->orderBy('numero_preguntas', 'desc');
                break;
            case 'tiempo':
                $query->orderBy('tiempo', 'desc');
                break;
            case 'reciente':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $examenes = $query->paginate(15);
        
        // 📊 ESTADÍSTICAS PARA LAS TARJETAS
        $totalExamenes = ExamenGenerado::count();
        
        // Contadores por tipo de examen
        $examenesMateria = ExamenGenerado::where('tipo_examen', 'Materia')->count();
        $examenesSimulacion = ExamenGenerado::where('tipo_examen', 'Simulación')->count();
        $examenesGeneral = ExamenGenerado::where('tipo_examen', 'General del curso')->count();
        
        // Estadísticas adicionales
        $totalPreguntasAsignadas = ApoyoPregunta::count();
        $promedioPreguntas = round(ExamenGenerado::avg('numero_preguntas') ?? 0, 1);
        $promedioTiempo = round(ExamenGenerado::avg('tiempo') ?? 0, 1);
        
        // Para el modal de generación automática
        $tipos_examen = ['Materia', 'General del curso', 'Simulación'];
        $areas = AreaPregunta::orderBy('nombre')->get();
        
        return view('administrador.examenes.index', compact(
            'examenes',
            'totalExamenes',
            'examenesMateria',
            'examenesSimulacion',
            'examenesGeneral',
            'totalPreguntasAsignadas',
            'promedioPreguntas',
            'promedioTiempo',
            'tipos_examen',
            'areas'
        ));
    }

    // Mostrar formulario de creación de examen
    public function create()
    {
        $areas = AreaPregunta::with('preguntas')->get();
        $preguntas = Pregunta::with('area')->get();
        
        $tipos_examen = [
            'Materia' => 'Materia',
            'General del curso' => 'General del curso',
            'Simulación' => 'Simulación'
        ];
        
        return view('administrador.examenes.create', compact('areas', 'preguntas', 'tipos_examen'));
    }

    // Guardar nuevo examen
    public function store(Request $request)
    {
        $request->validate([
            'numero_preguntas' => 'required|integer|min:1|max:200',
            'tiempo' => 'required|integer|min:1|max:180',
            'tipo_examen' => 'required|string|max:50',
            'preguntas_seleccionadas' => 'required|array|min:1',
            'preguntas_seleccionadas.*' => 'exists:preguntas,id',
        ]);

        try {
            DB::beginTransaction();
            
            // Validar que el número de preguntas coincida
            if (count($request->preguntas_seleccionadas) != $request->numero_preguntas) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'El número de preguntas seleccionadas no coincide con el indicado');
            }
            
            // Crear el examen
            $examen = ExamenGenerado::create([
                'numero_preguntas' => $request->numero_preguntas,
                'tiempo' => $request->tiempo,
                'tipo_examen' => $request->tipo_examen,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            // Asignar las preguntas al examen usando ApoyoPregunta
            foreach ($request->preguntas_seleccionadas as $pregunta_id) {
                ApoyoPregunta::create([
                    'examen' => $examen->id,
                    'pregunta' => $pregunta_id,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.examenes.index')
                ->with('success', "Examen #{$examen->id} creado correctamente con {$request->numero_preguntas} preguntas");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear examen: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el examen: ' . $e->getMessage());
        }
    }

    // Mostrar un examen específico - VERSIÓN CORREGIDA
    public function show($id){
        try {
            // Primero, obtener el examen básico
            $examen = ExamenGenerado::findOrFail($id);
            
            // Obtener las preguntas del examen usando query builder directamente (más seguro)
            $preguntasDelExamen = DB::table('apoyo_preguntas as ap')
                ->join('preguntas as p', 'ap.pregunta', '=', 'p.id')
                ->leftJoin('area_preguntas as a', 'p.id_area', '=', 'a.id')
                ->where('ap.examen', $id)
                ->select([
                    'p.id',
                    'p.pregunta as texto_pregunta',
                    'p.respuesta1',
                    'p.respuesta2',
                    'p.respuesta_correcta',
                    'p.id_area',
                    'a.nombre as area_nombre'
                ])
                ->get();
            
            // Asignar las preguntas al examen como una colección
            $examen->preguntas_lista = $preguntasDelExamen;
            
            // Calcular preguntas por área
            $preguntasPorArea = [];
            foreach($preguntasDelExamen as $pregunta) {
                $areaNombre = $pregunta->area_nombre ?? 'Sin área';
                $preguntasPorArea[$areaNombre] = ($preguntasPorArea[$areaNombre] ?? 0) + 1;
            }
            
            $totalPreguntas = $preguntasDelExamen->count();
            return view('administrador.examenes.show', compact('examen', 'totalPreguntas', 'preguntasPorArea'));
            
        } catch (\Exception $e) {
            \Log::error('Error en show de examen: ' . $e->getMessage());
            return redirect()->route('admin.examenes.index')
                ->with('error', 'Error al cargar el examen: ' . $e->getMessage());
        }
    }

    // Mostrar formulario de edición de examen
    public function edit($id)
    {
        \Log::info('=== EDIT EXAMEN ===');
        \Log::info('ID recibido: ' . $id);
        \Log::info('Tipo de ID: ' . gettype($id));
        
        try {
            // Buscar el examen manualmente
            $examen = ExamenGenerado::find($id);
            
            if (!$examen) {
                \Log::error('Examen no encontrado con ID: ' . $id);
                return redirect()->route('admin.examenes.index')
                    ->with('error', "El examen #{$id} no existe");
            }
            
            \Log::info('Examen encontrado: ' . $examen->id . ' - ' . $examen->tipo_examen);
            
            // Obtener las preguntas seleccionadas del examen
            $preguntasSeleccionadas = DB::table('apoyo_preguntas')
                ->where('examen', $examen->id)
                ->pluck('pregunta')
                ->toArray();
            
            \Log::info('Preguntas seleccionadas: ' . json_encode($preguntasSeleccionadas));
            
            // Obtener todas las áreas y preguntas
            $areas = AreaPregunta::with('preguntas')->get();
            $preguntas = Pregunta::with('area')->get();
            
            // Tipos de examen disponibles
            $tipos_examen = [
                'Materia' => 'Materia',
                'General del curso' => 'General del curso',
                'Simulación' => 'Simulación'
            ];
            
            return view('administrador.examenes.edit', compact('examen', 'areas', 'preguntas', 'preguntasSeleccionadas', 'tipos_examen'));
            
        } catch (\Exception $e) {
            \Log::error('Error en edit de examen: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.examenes.index')
                ->with('error', 'Error al cargar el examen para editar: ' . $e->getMessage());
        }
    }

// Actualizar examen - CORREGIDO
public function update(Request $request, $id)
{
    \Log::info('=== UPDATE EXAMEN ===');
    \Log::info('ID: ' . $id);
    \Log::info('Request data: ' . json_encode($request->all()));
    
    $request->validate([
        'numero_preguntas' => 'required|integer|min:1|max:200',
        'tiempo' => 'required|integer|min:1|max:180',
        'tipo_examen' => 'required|string|max:50',
        'preguntas_seleccionadas' => 'required|array|min:1',
        'preguntas_seleccionadas.*' => 'exists:preguntas,id',
    ]);

    try {
        DB::beginTransaction();
        
        $examen = ExamenGenerado::findOrFail($id);
        
        // Validar que el número de preguntas coincida
        if (count($request->preguntas_seleccionadas) != $request->numero_preguntas) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', "El número de preguntas seleccionadas (" . count($request->preguntas_seleccionadas) . ") no coincide con el indicado ({$request->numero_preguntas})");
        }
        
        // Actualizar el examen
        $examen->update([
            'numero_preguntas' => $request->numero_preguntas,
            'tiempo' => $request->tiempo,
            'tipo_examen' => $request->tipo_examen,
            'updated_at' => Carbon::now(),
        ]);
        
        // Eliminar las preguntas antiguas
        $deleted = ApoyoPregunta::where('examen', $examen->id)->delete();
        \Log::info('Preguntas antiguas eliminadas: ' . $deleted);
        
        // Asignar las nuevas preguntas
        $inserted = 0;
        foreach ($request->preguntas_seleccionadas as $pregunta_id) {
            ApoyoPregunta::create([
                'examen' => $examen->id,
                'pregunta' => $pregunta_id,
            ]);
            $inserted++;
        }
        
        \Log::info('Nuevas preguntas insertadas: ' . $inserted);
        
        DB::commit();
        
        return redirect()->route('admin.examenes.index')
            ->with('success', "Examen #{$examen->id} actualizado correctamente con {$inserted} preguntas");
            
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollBack();
        \Log::error('Examen no encontrado para actualizar: ' . $id);
        return redirect()->route('admin.examenes.index')
            ->with('error', 'El examen que intentas actualizar no existe');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al actualizar examen: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar el examen: ' . $e->getMessage());
    }
}

    // Eliminar examen
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $examen = ExamenGenerado::findOrFail($id);
            
            // Eliminar las preguntas asociadas
            ApoyoPregunta::where('examen', $examen->id)->delete();
            
            $examenId = $examen->id;
            $examen->delete();
            
            DB::commit();
            
            return redirect()->route('admin.examenes.index')
                ->with('success', "Examen #{$examenId} eliminado correctamente");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar examen: ' . $e->getMessage());
            return redirect()->route('admin.examenes.index')
                ->with('error', 'Error al eliminar el examen: ' . $e->getMessage());
        }
    }

    // ==================== MÉTODOS ADICIONALES ====================
    
    // Generar examen automático con preguntas aleatorias por área
    public function generarAutomatico(Request $request)
    {
        $request->validate([
            'numero_preguntas' => 'required|integer|min:1|max:200',
            'tiempo' => 'required|integer|min:1|max:180',
            'tipo_examen' => 'required|string|max:50',
            'areas' => 'nullable|array',
            'areas.*' => 'exists:area_preguntas,id',
        ]);

        try {
            DB::beginTransaction();
            
            // Construir query de preguntas disponibles
            $query = Pregunta::query();
            
            if ($request->has('areas') && !empty($request->areas)) {
                $query->whereIn('id_area', $request->areas);
            }
            
            // Obtener preguntas aleatorias
            $preguntasDisponibles = $query->count();
            
            if ($preguntasDisponibles < $request->numero_preguntas) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "No hay suficientes preguntas disponibles. Disponibles: {$preguntasDisponibles}, Requeridas: {$request->numero_preguntas}");
            }
            
            $preguntasSeleccionadas = $query->inRandomOrder()
                ->limit($request->numero_preguntas)
                ->pluck('id')
                ->toArray();
            
            // Crear el examen
            $examen = ExamenGenerado::create([
                'numero_preguntas' => $request->numero_preguntas,
                'tiempo' => $request->tiempo,
                'tipo_examen' => $request->tipo_examen,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            // Asignar las preguntas
            foreach ($preguntasSeleccionadas as $pregunta_id) {
                ApoyoPregunta::create([
                    'examen' => $examen->id,
                    'pregunta' => $pregunta_id,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.examenes.index')
                ->with('success', "Examen #{$examen->id} generado automáticamente con {$request->numero_preguntas} preguntas");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al generar examen automático: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al generar el examen: ' . $e->getMessage());
        }
    }
    
    // Duplicar examen existente
    public function duplicar($id){
        try {
            DB::beginTransaction();
            
            $examenOriginal = ExamenGenerado::with('apoyos')->findOrFail($id);
            
            // Crear copia del examen
            $nuevoExamen = ExamenGenerado::create([
                'numero_preguntas' => $examenOriginal->numero_preguntas,
                'tiempo' => $examenOriginal->tiempo,
                'tipo_examen' => $examenOriginal->tipo_examen . ' (Copia)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            // Copiar las preguntas
            foreach ($examenOriginal->apoyos as $apoyo) {
                ApoyoPregunta::create([
                    'examen' => $nuevoExamen->id,
                    'pregunta' => $apoyo->pregunta,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.examenes.edit', $nuevoExamen->id)
                ->with('success', "Examen duplicado correctamente. Ahora puedes editarlo");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al duplicar examen: ' . $e->getMessage());
            return redirect()->route('admin.examenes.index')
                ->with('error', 'Error al duplicar el examen: ' . $e->getMessage());
        }
    }
    
    // Obtener preguntas por área para selección dinámica (API)
    public function getPreguntasByArea($areaId)
    {
        $preguntas = Pregunta::where('id_area', $areaId)
            ->select('id', 'pregunta')
            ->orderBy('pregunta')
            ->get();
        
        return response()->json($preguntas);
    }
    
    // Obtener todas las preguntas para selección (API)
    public function getTodasPreguntas(Request $request)
    {
        $query = Pregunta::with('area')
            ->select('id', 'pregunta', 'id_area');
        
        if ($request->has('area_id') && $request->area_id != '') {
            $query->where('id_area', $request->area_id);
        }
        
        $preguntas = $query->orderBy('pregunta')->get();
        
        return response()->json($preguntas);
    }
    
    // Dashboard de exámenes
    public function dashboard()
    {
        $totalExamenes = ExamenGenerado::count();
        $totalPreguntasAsignadas = ApoyoPregunta::count();
        $promedioPreguntas = ExamenGenerado::avg('numero_preguntas') ?? 0;
        $promedioTiempo = ExamenGenerado::avg('tiempo') ?? 0;
        
        // Exámenes por tipo
        $examenesPorTipo = ExamenGenerado::selectRaw('tipo_examen, COUNT(*) as total')
            ->groupBy('tipo_examen')
            ->get();
        
        // Distribución de preguntas
        $distribucionPreguntas = ExamenGenerado::selectRaw('numero_preguntas, COUNT(*) as total')
            ->groupBy('numero_preguntas')
            ->orderBy('numero_preguntas')
            ->limit(10)
            ->get();
        
        // Últimos exámenes creados
        $ultimosExamenes = ExamenGenerado::withCount('apoyos')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('administrador.examenes.dashboard', compact(
            'totalExamenes',
            'totalPreguntasAsignadas',
            'promedioPreguntas',
            'promedioTiempo',
            'examenesPorTipo',
            'distribucionPreguntas',
            'ultimosExamenes'
        ));
    }
}