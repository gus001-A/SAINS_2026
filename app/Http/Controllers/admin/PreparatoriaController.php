<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preparatoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PreparatoriaController extends Controller{

    private const ESTADOS = [
        'AGUASCALIENTES', 'BAJA CALIFORNIA', 'BAJA CALIFORNIA SUR', 'CAMPECHE',
        'CHIAPAS', 'CHIHUAHUA', 'CIUDAD DE MÉXICO', 'COAHUILA', 'COLIMA',
        'DURANGO', 'ESTADO DE MÉXICO', 'GUANAJUATO', 'GUERRERO', 'HIDALGO',
        'JALISCO', 'MICHOACÁN', 'MORELOS', 'NAYARIT', 'NUEVO LEÓN',
        'OAXACA', 'PUEBLA', 'QUERÉTARO', 'QUINTANA ROO', 'SAN LUIS POTOSÍ',
        'SINALOA', 'SONORA', 'TABASCO', 'TAMAULIPAS', 'TLAXCALA',
        'VERACRUZ', 'YUCATÁN', 'ZACATECAS',
    ];

    public function index(Request $request)
    {
        $search = $request->get('search');
        $clave = $request->get('clave');
        $municipio = $request->get('municipio');
        $estado = $request->get('estado');
        $tipo = $request->get('tipo');
        $turno = $request->get('turno');
        $sort = $request->get('sort', 'centro_educativo');
        $order = $request->get('order', 'asc');

        // Consulta base para los filtros
        $query = Preparatoria::query()
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('centro_educativo', 'LIKE', "%{$search}%")
                    ->orWhere('clave', 'LIKE', "%{$search}%");
                });
            })
            ->when($clave, fn ($q, $clave) => $q->where('clave', 'LIKE', "%{$clave}%"))
            ->when($municipio, fn ($q, $municipio) => $q->where('municipio', 'LIKE', "%{$municipio}%"))
            ->when($estado, function($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->when($tipo, function($query, $tipo) {
                return $query->where('tipo', $tipo);
            })
            ->when($turno, function($query, $turno) {
                return $query->where('turno', $turno);
            });
        
        // Paginación (aplicamos ordenamiento después de los filtros)
        $preparatorias = $query->orderBy($sort, $order)->paginate(15);
        
        // ========== CONTADORES PARA LAS TARJETAS DE ESTADÍSTICAS ==========
        
        // Opción 1: Contadores con los filtros aplicados (dinámicos)
        $totalPreparatorias = (clone $query)->count();
        $publicas = (clone $query)->where('tipo', 'PUBLICO')->count();
        $privadas = (clone $query)->where('tipo', 'PRIVADO')->count();
        
        // Obtener estados únicos para filtros
        $estados = Preparatoria::select('estado')->distinct()->orderBy('estado')->pluck('estado');

        return \Inertia\Inertia::render('Admin/Preparatorias/Index', [
            'preparatorias' => $preparatorias->withQueryString(),
            'estados' => $estados,
            'stats' => [
                'total' => $totalPreparatorias,
                'publicas' => $publicas,
                'privadas' => $privadas,
            ],
            'filters' => [
                'search' => $search,
                'clave' => $clave,
                'municipio' => $municipio,
                'estado' => $estado,
                'tipo' => $tipo,
                'turno' => $turno,
                'sort' => $sort,
                'order' => $order,
            ],
        ]);
    }
    
    /**
     * Formulario para crear preparatoria
     */
    public function create(){
        return \Inertia\Inertia::render('Admin/Preparatorias/Create', [
            'estados' => self::ESTADOS,
        ]);
    }
    
    /**
     * Guardar nueva preparatoria
     */
    public function store(Request $request)
    {
        $request->validate([
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'localidad' => 'nullable|string|max:100',
            'ambito' => 'nullable|string|max:50',
            'tipo' => 'nullable|string|max:50',
            'servicio' => 'nullable|string|max:50',
            'clave' => 'nullable|string|max:20',
            'turno' => 'nullable|string|max:20',
            'centro_educativo' => 'required|string|max:200',
            'direccion' => 'nullable|string',
        ]);
        
        try {
            Preparatoria::create($this->payload($request));

            return redirect()->route('admin.preparatorias.index')
                ->with('success', 'Preparatoria registrada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al registrar preparatoria: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar la preparatoria');
        }
    }
    
    /**
     * Formulario para editar preparatoria
     */
    public function edit($id)
    {
        $preparatoria = Preparatoria::findOrFail($id);

        return \Inertia\Inertia::render('Admin/Preparatorias/Edit', [
            'preparatoria' => $preparatoria,
            'estados' => self::ESTADOS,
        ]);
    }
    
    /**
     * Actualizar preparatoria
     */
    public function update(Request $request, $id)
    {
        $preparatoria = Preparatoria::findOrFail($id);
        
        $request->validate([
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'localidad' => 'nullable|string|max:100',
            'ambito' => 'nullable|string|max:50',
            'tipo' => 'nullable|string|max:50',
            'servicio' => 'nullable|string|max:50',
            'clave' => 'nullable|string|max:20',
            'turno' => 'nullable|string|max:20',
            'centro_educativo' => 'required|string|max:200',
            'direccion' => 'nullable|string',
        ]);
        
        try {
            $preparatoria->update($this->payload($request));

            return redirect()->route('admin.preparatorias.index')
                ->with('success', 'Preparatoria actualizada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar preparatoria: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la preparatoria');
        }
    }
    
    /**
     * Eliminar preparatoria
     */
    public function destroy($id)
    {
        try {
            $preparatoria = Preparatoria::findOrFail($id);
            $nombre = $preparatoria->centro_educativo;
            $preparatoria->delete();
            
            return redirect()->route('admin.preparatorias.index')
                ->with('success', "Preparatoria '{$nombre}' eliminada exitosamente");
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar preparatoria: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Ocurrió un error al eliminar la preparatoria');
        }
    }
    
    /**
     * Normaliza el request: las columnas NOT NULL sin default no aceptan null
     * (el middleware ConvertEmptyStringsToNull convierte "" en null).
     */
    private function payload(Request $request): array
    {
        $data = $request->only([
            'estado', 'municipio', 'localidad', 'ambito', 'tipo',
            'servicio', 'clave', 'turno', 'centro_educativo', 'direccion',
        ]);

        foreach (['localidad', 'tipo', 'clave', 'municipio', 'centro_educativo', 'estado'] as $notNull) {
            $data[$notNull] = $data[$notNull] ?? '';
        }

        return $data;
    }

    /**
     * Obtener municipios por estado (API)
     */
    public function getMunicipios(Request $request)
    {
        $municipios = Preparatoria::where('estado', $request->estado)
            ->select('municipio')
            ->distinct()
            ->orderBy('municipio')
            ->pluck('municipio');
        
        return response()->json($municipios);
    }
}