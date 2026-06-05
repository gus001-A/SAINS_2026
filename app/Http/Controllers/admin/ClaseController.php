<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Asignatura;
use App\Models\Video;
use App\Models\RecursoClase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator; 

class ClaseController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las clases con relaciones
        $query = Clase::with('asignatura', 'recursos');
        
        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre_clase', 'like', "%{$search}%")
                  ->orWhereHas('asignatura', function($q2) use ($search) {
                      $q2->where('nombre', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filtro por materia
        if ($request->filled('asignatura_id')) {
            $query->where('id_asignatura', $request->asignatura_id);
        }
        
        // Obtener todas las clases (sin paginar aún)
        $clasesCollection = $query->get();
        
        // ORDENAMIENTO
        $ordenCampo = $request->get('orden_campo', 'asignatura');
        $ordenDireccion = $request->get('orden_direccion', 'asc');
        
        // Ordenar la colección según el campo seleccionado
        if ($ordenCampo == 'id') {
            $clasesCollection = $ordenDireccion == 'asc' 
                ? $clasesCollection->sortBy('id') 
                : $clasesCollection->sortByDesc('id');
        } 
        elseif ($ordenCampo == 'asignatura') {
            $clasesCollection = $ordenDireccion == 'asc' 
                ? $clasesCollection->sortBy(function($item) {
                    return $item->asignatura ? $item->asignatura->nombre : '';
                }) 
                : $clasesCollection->sortByDesc(function($item) {
                    return $item->asignatura ? $item->asignatura->nombre : '';
                });
        }
        elseif ($ordenCampo == 'num_clase') {
            $clasesCollection = $ordenDireccion == 'asc' 
                ? $clasesCollection->sortBy('num_clase') 
                : $clasesCollection->sortByDesc('num_clase');
        }
        elseif ($ordenCampo == 'nombre_clase') {
            $clasesCollection = $ordenDireccion == 'asc' 
                ? $clasesCollection->sortBy('nombre_clase') 
                : $clasesCollection->sortByDesc('nombre_clase');
        }
        elseif ($ordenCampo == 'video') {
            $clasesCollection = $ordenDireccion == 'asc' 
                ? $clasesCollection->sortBy(function($item) {
                    return $item->link ? 1 : 0;
                }) 
                : $clasesCollection->sortByDesc(function($item) {
                    return $item->link ? 1 : 0;
                });
        }
        elseif ($ordenCampo == 'material') {
            $clasesCollection = $ordenDireccion == 'asc' 
                ? $clasesCollection->sortBy(function($item) {
                    return $item->url ? 1 : 0;
                }) 
                : $clasesCollection->sortByDesc(function($item) {
                    return $item->url ? 1 : 0;
                });
        }
        
        // Paginar la colección manualmente
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $currentItems = $clasesCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $clases = new LengthAwarePaginator(
            $currentItems,
            $clasesCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
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
            'asignaturas',
            'ordenCampo',
            'ordenDireccion'
        ));
    }
    
    public function create()
    {
        $asignaturas = Asignatura::orderBy('nombre', 'asc')->get();
        $videos = Video::orderBy('materia')->orderBy('tema')->orderBy('titulo')->get();
        $tiposRecursos = RecursoClase::TIPOS;
        
        return view('administrador.clases.create', compact('asignaturas', 'videos', 'tiposRecursos'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_asignatura' => 'required|exists:asignatura,id',
            'num_clase' => 'required|integer|min:1',
            'nombre_clase' => 'required|string|max:255',
            'link' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500',
            'recursos' => 'nullable|array',
            'recursos.*.titulo' => 'required_with:recursos.*|string|max:255',
            'recursos.*.tipo' => 'required_with:recursos.*|string',
            'recursos.*.url' => 'required_with:recursos.*|url',
            'recursos.*.descripcion' => 'nullable|string',
        ]);
        
        try {
            // Verificar duplicado de número de clase
            $existe = Clase::where('id_asignatura', $request->id_asignatura)
                           ->where('num_clase', $request->num_clase)
                           ->exists();
            
            if ($existe) {
                return redirect()->back()->with('error', 'Ya existe una clase con el número ' . $request->num_clase . ' para esta asignatura')->withInput();
            }
            
            // Crear la clase
            $clase = Clase::create([
                'id_asignatura' => $request->id_asignatura,
                'num_clase' => $request->num_clase,
                'nombre_clase' => $request->nombre_clase,
                'link' => $request->link,
                'url' => $request->url,
            ]);
            
            // Guardar recursos adicionales
            if ($request->has('recursos')) {
                foreach ($request->recursos as $index => $recursoData) {
                    if (!empty($recursoData['titulo']) && !empty($recursoData['url'])) {
                        RecursoClase::create([
                            'id_clase' => $clase->id,
                            'titulo' => $recursoData['titulo'],
                            'tipo' => $recursoData['tipo'],
                            'url' => $recursoData['url'],
                            'descripcion' => $recursoData['descripcion'] ?? null,
                            'orden' => $index
                        ]);
                    }
                }
            }
            
            return redirect()->route('admin.clases.index')->with('success', 'Clase creada exitosamente con ' . ($request->recursos ? count(array_filter($request->recursos, function($r) { return !empty($r['titulo']); })) : 0) . ' recursos adicionales');
        } catch (\Exception $e) {
            Log::error('Error al crear clase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear la clase: ' . $e->getMessage())->withInput();
        }
    }
    
    public function show($id)
    {
        try {
            $clase = Clase::with('asignatura', 'recursos')->findOrFail($id);
            $tiposRecursos = RecursoClase::TIPOS;
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $clase
                ]);
            }
            
            return view('administrador.clases.show', compact('clase', 'tiposRecursos'));
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
        $clase = Clase::with('asignatura', 'recursos')->findOrFail($id);
        $asignaturas = Asignatura::orderBy('nombre', 'asc')->get();
        $videos = Video::orderBy('materia')->orderBy('tema')->orderBy('titulo')->get();
        $tiposRecursos = RecursoClase::TIPOS;
        
        return view('administrador.clases.edit', compact('clase', 'asignaturas', 'videos', 'tiposRecursos'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_asignatura' => 'required|exists:asignatura,id',
            'num_clase' => 'required|integer|min:1',
            'nombre_clase' => 'required|string|max:255',
            'link' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500',
            'recursos' => 'nullable|array',
            'recursos.*.titulo' => 'required_with:recursos.*|string|max:255',
            'recursos.*.tipo' => 'required_with:recursos.*|string',
            'recursos.*.url' => 'required_with:recursos.*|url',
            'recursos.*.descripcion' => 'nullable|string',
            'recursos_eliminar' => 'nullable|array',
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
            
            // Actualizar la clase
            $clase->update([
                'id_asignatura' => $request->id_asignatura,
                'num_clase' => $request->num_clase,
                'nombre_clase' => $request->nombre_clase,
                'link' => $request->link,
                'url' => $request->url,
            ]);
            
            // Eliminar recursos marcados para eliminar
            if ($request->has('recursos_eliminar')) {
                RecursoClase::whereIn('id', $request->recursos_eliminar)->delete();
            }
            
            // Actualizar o crear recursos existentes
            if ($request->has('recursos')) {
                foreach ($request->recursos as $index => $recursoData) {
                    if (!empty($recursoData['titulo']) && !empty($recursoData['url'])) {
                        if (isset($recursoData['id']) && $recursoData['id']) {
                            // Actualizar recurso existente
                            $recurso = RecursoClase::find($recursoData['id']);
                            if ($recurso && $recurso->id_clase == $clase->id) {
                                $recurso->update([
                                    'titulo' => $recursoData['titulo'],
                                    'tipo' => $recursoData['tipo'],
                                    'url' => $recursoData['url'],
                                    'descripcion' => $recursoData['descripcion'] ?? null,
                                    'orden' => $index
                                ]);
                            }
                        } else {
                            // Crear nuevo recurso
                            RecursoClase::create([
                                'id_clase' => $clase->id,
                                'titulo' => $recursoData['titulo'],
                                'tipo' => $recursoData['tipo'],
                                'url' => $recursoData['url'],
                                'descripcion' => $recursoData['descripcion'] ?? null,
                                'orden' => $index
                            ]);
                        }
                    }
                }
            }
            
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
            // Los recursos se eliminarán automáticamente por cascade
            $clase->delete();
            
            return redirect()->route('admin.clases.index')->with('success', 'Clase eliminada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar clase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la clase: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener el siguiente número de clase disponible para una asignatura
     */
    public function getSiguienteNumero($asignaturaId)
    {
        try {
            $maxNumero = Clase::where('id_asignatura', $asignaturaId)->max('num_clase');
            $siguiente = $maxNumero ? $maxNumero + 1 : 1;
            
            return response()->json([
                'success' => true,
                'siguiente_numero' => $siguiente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el número'
            ], 500);
        }
    }
    
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