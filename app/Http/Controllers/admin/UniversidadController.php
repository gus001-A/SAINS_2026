<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Universidad;
use App\Models\Preparatoria; // 👈 IMPORTANTE: Agregar esta línea
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UniversidadController extends Controller{
    public function index(Request $request){
        $search = $request->get('search');
        $estado = $request->get('estado');
        
        // Consulta base para los filtros
        $query = Universidad::with('carrera')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('clave', 'LIKE', "%{$search}%")
                        ->orWhere('direccion', 'LIKE', "%{$search}%")
                        ->orWhere('estado', 'LIKE', "%{$search}%");
                });
            })
            ->when($estado, function($query, $estado) {
                return $query->where('estado', $estado);
            });
        
        // Paginación
        $universidades = $query->orderBy('estado')
            ->orderBy('clave')
            ->paginate(20);
        
        // ========== CONTADORES PARA LAS TARJETAS DE ESTADÍSTICAS ==========
        
        // Total de universidades (con filtros aplicados)
        $totalUniversidades = (clone $query)->count();
        
        // Públicas (con filtros aplicados)
        $publicas = (clone $query)->where('tipo', 'PUBLICA')->count();
        
        // Privadas (con filtros aplicados)
        $privadas = (clone $query)->where('tipo', 'PRIVADA')->count();
        
        // Autónomas (con filtros aplicados)
        $autonomas = (clone $query)->where('tipo', 'AUTONOMA')->count();
        
        // Total de carreras (sumando las carreras de las universidades filtradas)
        $totalCarreras = (clone $query)->with('carrera')->get()->sum(function($universidad) {
            return $universidad->carrera ? 1 : 0;
        });
        
        // Obtener estados únicos para filtros
        $estados = Universidad::select('estado')->distinct()->orderBy('estado')->pluck('estado');
        
        return view('administrador.universidades.index', compact(
            'universidades', 
            'estados',
            'totalUniversidades',
            'publicas',
            'privadas',
            'autonomas',
            'totalCarreras'
        ));
    }
    /**
     * Formulario para crear universidad
     */
    public function create()
    {
        // Obtener estados únicos desde la tabla preparatorias
        $estados = Preparatoria::select('estado')
            ->distinct()
            ->orderBy('estado')
            ->pluck('estado');
        
        $carreras = Carrera::orderBy('nombre')->get();
        
        return view('administrador.universidades.create', compact('estados', 'carreras'));
    }
    
    /**
     * Guardar nueva universidad
     */
    public function store(Request $request)
    {
        $request->validate([
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'localidad' => 'nullable|string|max:100',
            'carrera_id' => 'nullable|exists:carreras,id',
            'duracion' => 'nullable|string|max:50',
            'tipo' => 'nullable|string|max:50',
            'clave' => 'required|string|max:20|unique:universidades,clave',
            'direccion' => 'nullable|string',
        ]);
        
        try {
            // Si se seleccionó "otra", usar el valor del input custom
            $localidad = $request->localidad;
            if ($localidad === 'otra' && $request->has('localidad_custom')) {
                $localidad = $request->localidad_custom;
            }
            
            Universidad::create([
                'estado' => $request->estado,
                'municipio' => $request->municipio,
                'localidad' => $localidad,
                'carrera_id' => $request->carrera_id,
                'duracion' => $request->duracion,
                'tipo' => $request->tipo,
                'clave' => $request->clave,
                'direccion' => $request->direccion,
            ]);
            
            return redirect()->route('admin.universidades.index')
                ->with('success', 'Universidad registrada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al registrar universidad: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar la universidad: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar detalles de una universidad
     */
    public function show($id)
    {
        $universidad = Universidad::with('carrera')->findOrFail($id);
        return view('administrador.universidades.show', compact('universidad'));
    }
    
    /**
     * Formulario para editar universidad
     */
    public function edit($id)
    {
        $universidad = Universidad::findOrFail($id);
        $carreras = Carrera::orderBy('nombre')->get();
        
        $estados = Preparatoria::select('estado')
            ->distinct()
            ->orderBy('estado')
            ->pluck('estado');
        
        return view('administrador.universidades.edit', compact('universidad', 'carreras', 'estados'));
    }
    
    /**
     * Actualizar universidad
     */
    public function update(Request $request, $id)
    {
        $universidad = Universidad::findOrFail($id);
        
        $request->validate([
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'localidad' => 'nullable|string|max:100',
            'carrera_id' => 'nullable|exists:carreras,id',
            'duracion' => 'nullable|string|max:50',
            'tipo' => 'nullable|string|max:50',
            'clave' => 'required|string|max:20|unique:universidades,clave,' . $id,
            'direccion' => 'nullable|string',
        ]);
        
        try {
            $localidad = $request->localidad;
            if ($localidad === 'otra' && $request->has('localidad_custom')) {
                $localidad = $request->localidad_custom;
            }
            
            $universidad->update([
                'estado' => $request->estado,
                'municipio' => $request->municipio,
                'localidad' => $localidad,
                'carrera_id' => $request->carrera_id,
                'duracion' => $request->duracion,
                'tipo' => $request->tipo,
                'clave' => $request->clave,
                'direccion' => $request->direccion,
            ]);
            
            return redirect()->route('admin.universidades.index')
                ->with('success', 'Universidad actualizada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar universidad: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la universidad');
        }
    }
    
    /**
     * Eliminar universidad
     */
    public function destroy($id)
    {
        try {
            $universidad = Universidad::findOrFail($id);
            $nombre = $universidad->clave;
            $universidad->delete();
            
            return redirect()->route('admin.universidades.index')
                ->with('success', "Universidad '{$nombre}' eliminada exitosamente");
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar universidad: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Ocurrió un error al eliminar la universidad');
        }
    }
    
    /**
     * Obtener municipios por estado (API)
     */
    public function getMunicipios(Request $request){
        $estado = $request->get('estado');
        
        if (!$estado) {
            return response()->json([]);
        }
        
        $municipios = Preparatoria::where('estado', $estado)
            ->select('municipio')
            ->distinct()
            ->orderBy('municipio')
            ->pluck('municipio');
        
        return response()->json($municipios);
    }

    /**
     * Obtener localidades por estado y municipio (API)
     */
    public function getLocalidades(Request $request){
        $estado = $request->get('estado');
        $municipio = $request->get('municipio');
        
        if (!$estado || !$municipio) {
            return response()->json([]);
        }
        
        $localidades = Preparatoria::where('estado', $estado)
            ->where('municipio', $municipio)
            ->select('localidad')
            ->whereNotNull('localidad')
            ->where('localidad', '!=', '')
            ->distinct()
            ->orderBy('localidad')
            ->pluck('localidad');
        
        return response()->json($localidades);
    }
}