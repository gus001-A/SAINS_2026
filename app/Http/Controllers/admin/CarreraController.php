<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use App\Models\Asignatura;
use App\Models\Tronco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class CarreraController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las carreras primero
        $query = Carrera::with(['tronco', 'asignatura1', 'asignatura2', 'asignatura3', 'universidades']);
        
        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }
        
        // Filtro por tronco
        if ($request->filled('tronco_id')) {
            $query->where('tronco_id', $request->tronco_id);
        }
        
        // Obtener todas las carreras (sin paginar aún)
        $carrerasCollection = $query->get();
        
        // Agregar conteos a cada carrera
        foreach ($carrerasCollection as $carrera) {
            $carrera->total_universidades = $carrera->universidades->count();
        }
        
        // ORDENAMIENTO
        $ordenCampo = $request->get('orden_campo', 'nombre');
        $ordenDireccion = $request->get('orden_direccion', 'asc');
        
        // Ordenar la colección según el campo seleccionado
        if ($ordenCampo == 'id') {
            $carrerasCollection = $ordenDireccion == 'asc' 
                ? $carrerasCollection->sortBy('id') 
                : $carrerasCollection->sortByDesc('id');
        } 
        elseif ($ordenCampo == 'nombre') {
            $carrerasCollection = $ordenDireccion == 'asc' 
                ? $carrerasCollection->sortBy('nombre') 
                : $carrerasCollection->sortByDesc('nombre');
        }
        elseif ($ordenCampo == 'tronco') {
            $carrerasCollection = $ordenDireccion == 'asc' 
                ? $carrerasCollection->sortBy(function($item) {
                    return $item->tronco ? $item->tronco->nombre : '';
                }) 
                : $carrerasCollection->sortByDesc(function($item) {
                    return $item->tronco ? $item->tronco->nombre : '';
                });
        }
        elseif ($ordenCampo == 'calificacion_minima') {
            $carrerasCollection = $ordenDireccion == 'asc' 
                ? $carrerasCollection->sortBy(function($item) {
                    return $item->calificacion_minima ?? PHP_FLOAT_MAX;
                }) 
                : $carrerasCollection->sortByDesc(function($item) {
                    return $item->calificacion_minima ?? -1;
                });
        }
        elseif ($ordenCampo == 'universidades') {
            $carrerasCollection = $ordenDireccion == 'asc' 
                ? $carrerasCollection->sortBy('total_universidades') 
                : $carrerasCollection->sortByDesc('total_universidades');
        }
        
        // Paginar la colección manualmente
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $currentItems = $carrerasCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $carreras = new LengthAwarePaginator(
            $currentItems,
            $carrerasCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Estadísticas para las tarjetas
        $totalCarreras = Carrera::count();
        $conTronco = Carrera::whereNotNull('tronco_id')->count();
        $conUniversidades = Carrera::has('universidades')->count();
        $conCalificacionMinima = Carrera::whereNotNull('calificacion_minima')->count();
        
        // Datos para selects
        $asignaturas = Asignatura::orderBy('nombre', 'asc')->get();
        $troncos = Tronco::orderBy('nombre', 'asc')->get();
        
        return view('administrador.carreras.index', compact(
            'carreras', 
            'totalCarreras', 
            'conTronco', 
            'conUniversidades',
            'conCalificacionMinima',
            'asignaturas',
            'troncos',
            'ordenCampo',
            'ordenDireccion'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:carreras,nombre',
            'tronco_id' => 'nullable|exists:tronco,id',
            'calificacion_minima' => 'nullable|numeric|min:0|max:100',
            'id_asignatura_1' => 'nullable|exists:asignatura,id',
            'id_asignatura_2' => 'nullable|exists:asignatura,id',
            'id_asignatura_3' => 'nullable|exists:asignatura,id',
        ]);
        
        try {
            $carrera = Carrera::create([
                'nombre' => $request->nombre,
                'tronco_id' => $request->tronco_id,
                'calificacion_minima' => $request->calificacion_minima,
                'id_asignatura_1' => $request->id_asignatura_1,
                'id_asignatura_2' => $request->id_asignatura_2,
                'id_asignatura_3' => $request->id_asignatura_3,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Carrera creada exitosamente',
                'data' => $carrera
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear carrera: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la carrera: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $carrera = Carrera::with(['tronco', 'asignatura1', 'asignatura2', 'asignatura3', 'universidades'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $carrera->id,
                    'nombre' => $carrera->nombre,
                    'tronco' => $carrera->tronco ? $carrera->tronco->nombre : 'No asignado',
                    'calificacion_minima' => $carrera->calificacion_minima,
                    'asignatura_1' => $carrera->asignatura1 ? $carrera->asignatura1->nombre : 'No asignada',
                    'asignatura_2' => $carrera->asignatura2 ? $carrera->asignatura2->nombre : 'No asignada',
                    'asignatura_3' => $carrera->asignatura3 ? $carrera->asignatura3->nombre : 'No asignada',
                    'total_universidades' => $carrera->universidades->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los datos'
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:carreras,nombre,' . $id,
            'tronco_id' => 'nullable|exists:tronco,id',
            'calificacion_minima' => 'nullable|numeric|min:0|max:100',
            'id_asignatura_1' => 'nullable|exists:asignatura,id',
            'id_asignatura_2' => 'nullable|exists:asignatura,id',
            'id_asignatura_3' => 'nullable|exists:asignatura,id',
        ]);
        
        try {
            $carrera = Carrera::findOrFail($id);
            $carrera->update([
                'nombre' => $request->nombre,
                'tronco_id' => $request->tronco_id,
                'calificacion_minima' => $request->calificacion_minima,
                'id_asignatura_1' => $request->id_asignatura_1,
                'id_asignatura_2' => $request->id_asignatura_2,
                'id_asignatura_3' => $request->id_asignatura_3,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Carrera actualizada exitosamente',
                'data' => $carrera
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar carrera: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la carrera: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $carrera = Carrera::findOrFail($id);
            
            // Verificar si tiene universidades asociadas
            if ($carrera->universidades()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la carrera porque tiene universidades asociadas'
                ], 400);
            }
            
            $carrera->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Carrera eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar carrera: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la carrera: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API para obtener carreras (selectores dinámicos)
     */
    public function getCarrerasApi()
    {
        try {
            $carreras = Carrera::select('id', 'nombre', 'calificacion_minima')
                ->orderBy('nombre', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $carreras
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => []
            ]);
        }
    }
    
    /**
     * Obtener carreras filtradas por calificación mínima del estudiante
     */
    public function getCarrerasByCalificacion($calificacion)
    {
        try {
            $carreras = Carrera::select('id', 'nombre', 'calificacion_minima')
                ->where(function($query) use ($calificacion) {
                    $query->whereNull('calificacion_minima')
                          ->orWhere('calificacion_minima', '<=', $calificacion);
                })
                ->orderBy('nombre', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $carreras,
                'calificacion_estudiante' => $calificacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => []
            ]);
        }
    }
    
    /**
     * Verificar si una carrera es accesible para un estudiante
     */
    public function verificarAcceso($carreraId, $calificacion)
    {
        try {
            $carrera = Carrera::findOrFail($carreraId);
            
            $accesible = true;
            $mensaje = 'Carrera accesible';
            
            if ($carrera->calificacion_minima) {
                $accesible = $calificacion >= $carrera->calificacion_minima;
                $mensaje = $accesible 
                    ? 'El estudiante cumple con la calificación mínima requerida' 
                    : 'El estudiante NO cumple con la calificación mínima requerida';
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'carrera_id' => $carrera->id,
                    'carrera_nombre' => $carrera->nombre,
                    'calificacion_requerida' => $carrera->calificacion_minima,
                    'calificacion_estudiante' => $calificacion,
                    'accesible' => $accesible,
                    'mensaje' => $mensaje
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar acceso'
            ], 500);
        }
    }
}