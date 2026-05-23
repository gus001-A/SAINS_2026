<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsignaturaController extends Controller
{
    public function index(Request $request){
        $query = Asignatura::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }
        
        $asignaturas = $query->orderBy('nombre', 'asc')->paginate(15);
        
        // Estadísticas para las tarjetas
        $totalMaterias = Asignatura::count();
        $conClases = Asignatura::whereHas('clases')->count();
        $enCarreras = Asignatura::where(function($q) {
            $q->whereHas('carrerasComoMateria1')
              ->orWhereHas('carrerasComoMateria2')
              ->orWhereHas('carrerasComoMateria3');
        })->count();
        
        return view('administrador.asignaturas.index', compact('asignaturas', 'totalMaterias', 'conClases', 'enCarreras'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:asignatura,nombre',
        ]);
        
        try {
            $asignatura = Asignatura::create(['nombre' => $request->nombre]);
            
            return response()->json([
                'success' => true,
                'message' => 'Materia creada exitosamente',
                'data' => $asignatura
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear materia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la materia: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:asignatura,nombre,' . $id,
        ]);
        
        try {
            $asignatura = Asignatura::findOrFail($id);
            $asignatura->update(['nombre' => $request->nombre]);
            
            return response()->json([
                'success' => true,
                'message' => 'Materia actualizada exitosamente',
                'data' => $asignatura
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar materia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la materia: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $asignatura = Asignatura::findOrFail($id);
            
            // Verificar si tiene relaciones
            $tieneRelaciones = false;
            $mensajeRelaciones = [];
            
            if ($asignatura->clases()->count() > 0) {
                $tieneRelaciones = true;
                $mensajeRelaciones[] = 'tiene clases asociadas';
            }
            
            if ($asignatura->carrerasComoMateria1()->count() > 0 ||
                $asignatura->carrerasComoMateria2()->count() > 0 ||
                $asignatura->carrerasComoMateria3()->count() > 0) {
                $tieneRelaciones = true;
                $mensajeRelaciones[] = 'está siendo usada en carreras';
            }
            
            if ($tieneRelaciones) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la materia porque ' . implode(' y ', $mensajeRelaciones)
                ], 400);
            }
            
            $asignatura->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Materia eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar materia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la materia: ' . $e->getMessage()
            ], 500);
        }
    }
}