<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class AsignaturaController extends Controller
{
    public function index(Request $request){
        // Obtener todos los registros primero (sin paginar)
        $query = Asignatura::query();
        
        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }
        
        // Obtener todas las asignaturas (sin paginar aún)
        $asignaturasCollection = $query->get();
        
        // Agregar los conteos a cada asignatura
        foreach ($asignaturasCollection as $asignatura) {
            $asignatura->total_clases = $asignatura->clases()->count();
            $asignatura->total_carreras = $asignatura->carrerasComoMateria1()->count() +
                                          $asignatura->carrerasComoMateria2()->count() +
                                          $asignatura->carrerasComoMateria3()->count();
        }
        
        // ORDENAMIENTO
        $ordenCampo = $request->get('orden_campo', 'nombre');
        $ordenDireccion = $request->get('orden_direccion', 'asc');
        
        // Ordenar la colección según el campo seleccionado
        if ($ordenCampo == 'id') {
            $asignaturasCollection = $ordenDireccion == 'asc' 
                ? $asignaturasCollection->sortBy('id') 
                : $asignaturasCollection->sortByDesc('id');
        } 
        elseif ($ordenCampo == 'nombre') {
            $asignaturasCollection = $ordenDireccion == 'asc' 
                ? $asignaturasCollection->sortBy('nombre') 
                : $asignaturasCollection->sortByDesc('nombre');
        }
        elseif ($ordenCampo == 'clases') {
            $asignaturasCollection = $ordenDireccion == 'asc' 
                ? $asignaturasCollection->sortBy('total_clases') 
                : $asignaturasCollection->sortByDesc('total_clases');
        }
        elseif ($ordenCampo == 'carreras') {
            $asignaturasCollection = $ordenDireccion == 'asc' 
                ? $asignaturasCollection->sortBy('total_carreras') 
                : $asignaturasCollection->sortByDesc('total_carreras');
        }
        
        // Paginar la colección manualmente
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $currentItems = $asignaturasCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $asignaturas = new LengthAwarePaginator(
            $currentItems,
            $asignaturasCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Estadísticas para las tarjetas
        $totalMaterias = Asignatura::count();
        $conClases = Asignatura::whereHas('clases')->count();
        $enCarreras = Asignatura::where(function($q) {
            $q->whereHas('carrerasComoMateria1')
              ->orWhereHas('carrerasComoMateria2')
              ->orWhereHas('carrerasComoMateria3');
        })->count();
        
        return view('administrador.asignaturas.index', compact('asignaturas', 'totalMaterias', 'conClases', 'enCarreras', 'ordenCampo', 'ordenDireccion'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrador.asignaturas.create');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:asignatura,nombre',
        ]);
        
        try {
            $asignatura = Asignatura::create(['nombre' => $request->nombre]);
            
            return redirect()->route('admin.asignaturas.index')
                ->with('success', 'Materia creada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al crear materia: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear la materia: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        abort(404);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asignatura $asignatura)  // Usando Route Model Binding
    {
        return view('administrador.asignaturas.edit', compact('asignatura'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asignatura $asignatura)  // Usando Route Model Binding
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:asignatura,nombre,' . $asignatura->id,
        ]);
        
        try {
            $asignatura->update(['nombre' => $request->nombre]);
            
            return redirect()->route('admin.asignaturas.index')
                ->with('success', 'Materia actualizada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar materia: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar la materia: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignatura $asignatura)  // Usando Route Model Binding
    {
        try {
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
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar la materia porque ' . implode(' y ', $mensajeRelaciones)
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', 'No se puede eliminar la materia porque ' . implode(' y ', $mensajeRelaciones));
            }
            
            $asignatura->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Materia eliminada exitosamente'
                ]);
            }
            
            return redirect()->route('admin.asignaturas.index')
                ->with('success', 'Materia eliminada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar materia: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la materia: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error al eliminar la materia: ' . $e->getMessage());
        }
    }
}