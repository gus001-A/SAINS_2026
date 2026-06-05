<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Asignatura;
use App\Models\ProgresoVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $plan = $request->get('plan');
        
        // Obtener parámetros de ordenamiento
        $ordenCampo = $request->get('orden_campo', 'id');
        $ordenDireccion = $request->get('orden_direccion', 'desc');
        
        // Validar que el campo de ordenamiento sea válido
        $camposPermitidos = ['id', 'titulo', 'materia', 'duracion', 'plan', 'created_at'];
        if (!in_array($ordenCampo, $camposPermitidos)) {
            $ordenCampo = 'id';
        }
        
        // Validar dirección de ordenamiento
        $ordenDireccion = in_array($ordenDireccion, ['asc', 'desc']) ? $ordenDireccion : 'desc';
        
        $videos = Video::when($search, function($query, $search) {
                return $query->where('titulo', 'like', "%{$search}%")
                            ->orWhere('materia', 'like', "%{$search}%")
                            ->orWhere('tema', 'like', "%{$search}%");
            })
            ->when($plan !== null && $plan !== '', function($query) use ($plan) {
                return $query->where('plan', $plan);
            })
            ->orderBy($ordenCampo, $ordenDireccion)
            ->paginate(10);
        
        // Mantener los parámetros de ordenamiento en la paginación
        $videos->appends([
            'orden_campo' => $ordenCampo,
            'orden_direccion' => $ordenDireccion,
            'search' => $search,
            'plan' => $plan
        ]);
        
        // Estadísticas
        $totalVideos = Video::count();
        $conProgresos = Video::has('progresos')->count();
        $planGratuito = Video::where('plan', true)->count();
        
        return view('administrador.videos.index', compact('videos', 'totalVideos', 'conProgresos', 'planGratuito'));
    }
    
    public function create()
    {
        $asignaturas = Asignatura::orderBy('nombre')->get();
        return view('administrador.videos.create', compact('asignaturas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'materia' => 'required|string|max:255',
            'tema' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'link' => 'required|url|max:500',
            'duracion' => 'nullable|string|max:50',
            'plan' => 'boolean'
        ]);
        
        try {
            $video = Video::create([
                'materia' => $request->materia,
                'tema' => $request->tema,
                'titulo' => $request->titulo,
                'link' => $request->link,
                'duracion' => $request->duracion,
                'plan' => $request->has('plan') ? 1 : 0
            ]);
            
            return redirect()->route('admin.videos.index')
                ->with('success', 'Video creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el video: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function show($id)
    {
        $video = Video::with(['progresos.estudiante'])->findOrFail($id);
        
        // Calcular porcentaje de completado
        $totalProgresos = $video->progresos()->count();
        $completados = $video->progresos()->where('completado', true)->count();
        $porcentajeCompletado = $totalProgresos > 0 ? round(($completados / $totalProgresos) * 100) : 0;
        
        // Últimos progresos
        $ultimosProgresos = $video->progresos()
            ->with('estudiante')
            ->orderBy('fecha_visto', 'desc')
            ->limit(10)
            ->get();
        
        // Agregar porcentaje a cada progreso
        foreach ($ultimosProgresos as $progreso) {
            // Calcular porcentaje basado en duración estimada del video
            $duracionSegundos = $this->convertirDuracionASegundos($video->duracion);
            
            // Asegurar que ultimo_segundo sea numérico
            $ultimoSegundo = is_numeric($progreso->ultimo_segundo) ? (float)$progreso->ultimo_segundo : 0;
            
            if ($duracionSegundos > 0 && $ultimoSegundo > 0) {
                $progreso->porcentaje = round(($ultimoSegundo / $duracionSegundos) * 100);
                $progreso->porcentaje = min(100, max(0, $progreso->porcentaje));
            } else {
                $progreso->porcentaje = $progreso->completado ? 100 : 0;
            }
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $video->id,
                    'materia' => $video->materia,
                    'tema' => $video->tema,
                    'titulo' => $video->titulo,
                    'link' => $video->link,
                    'duracion' => $video->duracion,
                    'plan' => $video->plan,
                    'progresos_count' => $totalProgresos,
                    'porcentaje_completado' => $porcentajeCompletado
                ]
            ]);
        }
        
        return view('administrador.videos.show', compact('video', 'porcentajeCompletado', 'ultimosProgresos'));
    }
    
    /**
     * Convertir duración formato HH:MM:SS a segundos
     */
    private function convertirDuracionASegundos($duracion)
    {
        if (!$duracion || !is_string($duracion)) {
            return 3600; // Valor por defecto
        }
        
        $parts = explode(':', $duracion);
        
        if (count($parts) == 3) {
            // HH:MM:SS
            $hours = is_numeric($parts[0]) ? (int)$parts[0] : 0;
            $minutes = is_numeric($parts[1]) ? (int)$parts[1] : 0;
            $seconds = is_numeric($parts[2]) ? (int)$parts[2] : 0;
            return ($hours * 3600) + ($minutes * 60) + $seconds;
        } elseif (count($parts) == 2) {
            // MM:SS
            $minutes = is_numeric($parts[0]) ? (int)$parts[0] : 0;
            $seconds = is_numeric($parts[1]) ? (int)$parts[1] : 0;
            return ($minutes * 60) + $seconds;
        }
        
        // Si el formato es solo un número (segundos)
        if (is_numeric($duracion)) {
            return (int)$duracion;
        }
        
        return 3600; // Valor por defecto si no se puede parsear
    }
    
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        $asignaturas = Asignatura::orderBy('nombre')->get();
        return view('administrador.videos.edit', compact('video', 'asignaturas'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'materia' => 'required|string|max:255',
            'tema' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'link' => 'required|url|max:500',
            'duracion' => 'nullable|string|max:50',
            'plan' => 'boolean'
        ]);
        
        try {
            $video = Video::findOrFail($id);
            $video->update([
                'materia' => $request->materia,
                'tema' => $request->tema,
                'titulo' => $request->titulo,
                'link' => $request->link,
                'duracion' => $request->duracion,
                'plan' => $request->has('plan') ? 1 : 0
            ]);
            
            return redirect()->route('admin.videos.index')
                ->with('success', 'Video actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el video: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            
            if ($video->progresos()->count() > 0) {
                return redirect()->route('admin.videos.index')
                    ->with('error', 'No se puede eliminar el video porque tiene progresos asociados.');
            }
            
            $video->delete();
            
            return redirect()->route('admin.videos.index')
                ->with('success', 'Video eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.videos.index')
                ->with('error', 'Error al eliminar el video: ' . $e->getMessage());
        }
    }
}