<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Asignatura;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Clase::with('asignatura');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre_clase', 'like', "%{$search}%")
                  ->orWhereHas('asignatura', function($q2) use ($search) {
                      $q2->where('nombre', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('asignatura_id')) {
            $query->where('id_asignatura', $request->asignatura_id);
        }
        
        $clases = $query->orderBy('id_asignatura', 'asc')
                        ->orderBy('num_clase', 'asc')
                        ->paginate(15);
        
        // Estadísticas
        $totalClases = Clase::count();
        $conVideo = Clase::whereNotNull('link')->where('link', '!=', '')->count();
        $conMaterial = Clase::whereNotNull('url')->where('url', '!=', '')->count();
        
        // Para selects
        $asignaturas = Asignatura::orderBy('nombre', 'asc')->get();
        
        return view('administrador.clases.index', compact(
            'clases', 
            'totalClases', 
            'conVideo', 
            'conMaterial',
            'asignaturas'
        ));
    }
    
    public function create()
    {
        $asignaturas = Asignatura::orderBy('nombre', 'asc')->get();
        $videos = Video::orderBy('materia')->orderBy('tema')->orderBy('titulo')->get();
        
        return view('administrador.clases.create', compact('asignaturas', 'videos'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_asignatura' => 'required|exists:asignatura,id',
            'num_clase' => 'required|integer|min:1',
            'nombre_clase' => 'required|string|max:255',
            'link' => 'nullable|string|max:500',  // ← Video link
            'url' => 'nullable|string|max:500',   // ← Material apoyo
        ]);
        
        try {
            // Verificar que el número de clase no esté duplicado para la misma asignatura
            $existe = Clase::where('id_asignatura', $request->id_asignatura)
                           ->where('num_clase', $request->num_clase)
                           ->exists();
            
            if ($existe) {
                return redirect()->back()->with('error', 'Ya existe una clase con el número ' . $request->num_clase . ' para esta asignatura')->withInput();
            }
            
            $clase = Clase::create([
                'id_asignatura' => $request->id_asignatura,
                'num_clase' => $request->num_clase,
                'nombre_clase' => $request->nombre_clase,
                'link' => $request->link,
                'url' => $request->url,
            ]);
            
            return redirect()->route('admin.clases.index')->with('success', 'Clase creada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al crear clase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear la clase: ' . $e->getMessage())->withInput();
        }
    }
    
    public function show($id){
        try {
            $clase = Clase::with('asignatura')->findOrFail($id);
            
            // Verificar si la petición espera JSON (para edición modal)
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $clase
                ]);
            }
            
            // Para peticiones normales, retornar la vista
            return view('administrador.clases.show', compact('clase'));
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al obtener los datos'
                ], 500);
            }
            return redirect()->route('admin.clases.index')->with('error', 'Clase no encontrada');
        }
    }
    
    public function edit($id)
    {
        $clase = Clase::with('asignatura')->findOrFail($id);
        $asignaturas = Asignatura::orderBy('nombre', 'asc')->get();
        $videos = Video::orderBy('materia')->orderBy('tema')->orderBy('titulo')->get();
        
        return view('administrador.clases.edit', compact('clase', 'asignaturas', 'videos'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_asignatura' => 'required|exists:asignatura,id',
            'num_clase' => 'required|integer|min:1',
            'nombre_clase' => 'required|string|max:255',
            'link' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500',
        ]);
        
        try {
            $clase = Clase::findOrFail($id);
            
            // Verificar duplicado excluyendo la clase actual
            $existe = Clase::where('id_asignatura', $request->id_asignatura)
                           ->where('num_clase', $request->num_clase)
                           ->where('id', '!=', $id)
                           ->exists();
            
            if ($existe) {
                return redirect()->back()->with('error', 'Ya existe una clase con el número ' . $request->num_clase . ' para esta asignatura')->withInput();
            }
            
            $clase->update([
                'id_asignatura' => $request->id_asignatura,
                'num_clase' => $request->num_clase,
                'nombre_clase' => $request->nombre_clase,
                'link' => $request->link,
                'url' => $request->url,
            ]);
            
            return redirect()->route('admin.clases.index')->with('success', 'Clase actualizada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar clase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar la clase: ' . $e->getMessage())->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $clase = Clase::findOrFail($id);
            $clase->delete();
            
            return redirect()->route('admin.clases.index')->with('success', 'Clase eliminada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar clase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la clase: ' . $e->getMessage());
        }
    }
    
    /**
     * API para obtener videos por materia/tema (para búsqueda en tiempo real)
     */
    public function getVideosApi(Request $request)
    {
        try {
            $query = Video::query();
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('materia', 'like', '%' . $search . '%')
                      ->orWhere('tema', 'like', '%' . $search . '%')
                      ->orWhere('titulo', 'like', '%' . $search . '%');
                });
            }
            
            if ($request->filled('materia')) {
                $query->where('materia', 'like', '%' . $request->materia . '%');
            }
            
            $videos = $query->orderBy('materia')->orderBy('tema')->get(['id', 'materia', 'tema', 'titulo', 'link', 'duracion']);
            
            return response()->json([
                'success' => true,
                'data' => $videos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => []
            ]);
        }
    }
    
    /**
     * API para obtener clases por asignatura
     */
    public function getClasesByAsignaturaApi($asignaturaId)
    {
        try {
            $clases = Clase::where('id_asignatura', $asignaturaId)
                ->orderBy('num_clase', 'asc')
                ->get(['id', 'num_clase', 'nombre_clase']);
            
            return response()->json([
                'success' => true,
                'data' => $clases
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => []
            ]);
        }
    }
}