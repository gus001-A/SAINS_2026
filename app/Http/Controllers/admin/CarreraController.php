<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use App\Models\Asignatura;
use App\Models\Tronco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarreraController extends Controller
{
    public function index(Request $request)
    {
        $query = Carrera::with(['tronco', 'asignatura1', 'asignatura2', 'asignatura3']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }
        
        if ($request->filled('tronco_id')) {
            $query->where('tronco_id', $request->tronco_id);
        }
        
        $carreras = $query->orderBy('nombre', 'asc')->paginate(15);
        
        // Estadísticas
        $totalCarreras = Carrera::count();
        $conTronco = Carrera::whereNotNull('tronco_id')->count();
        $conUniversidades = Carrera::has('universidades')->count();
        
        // Datos para selects
        $asignaturas = Asignatura::orderBy('nombre', 'asc')->get();
        $troncos = Tronco::orderBy('nombre', 'asc')->get();
        
        return view('administrador.carreras.index', compact(
            'carreras', 
            'totalCarreras', 
            'conTronco', 
            'conUniversidades',
            'asignaturas',
            'troncos'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:carreras,nombre',
            'tronco_id' => 'nullable|exists:troncos,id',
            'id_asignatura_1' => 'nullable|exists:asignatura,id',
            'id_asignatura_2' => 'nullable|exists:asignatura,id',
            'id_asignatura_3' => 'nullable|exists:asignatura,id',
        ]);
        
        try {
            $carrera = Carrera::create([
                'nombre' => $request->nombre,
                'tronco_id' => $request->tronco_id,
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
            'tronco_id' => 'nullable|exists:troncos,id',
            'id_asignatura_1' => 'nullable|exists:asignatura,id',
            'id_asignatura_2' => 'nullable|exists:asignatura,id',
            'id_asignatura_3' => 'nullable|exists:asignatura,id',
        ]);
        
        try {
            $carrera = Carrera::findOrFail($id);
            $carrera->update([
                'nombre' => $request->nombre,
                'tronco_id' => $request->tronco_id,
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
            $carreras = Carrera::select('id', 'nombre')
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
}