<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CuponController extends Controller
{
    // ==================== MÉTODOS PRINCIPALES ====================
    
    // Listar todos los cupones (gestión)
    public function index(Request $request)
    {
        $query = Cupon::with(['usuarioGenero', 'usuarioUso']);
        
        // Filtro por búsqueda
        if ($request->filled('search')) {
            $query->where('codigo', 'LIKE', '%' . $request->search . '%');
        }
        
        // Filtro por estado
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }
        
        // Filtro por tipo de descuento
        if ($request->filled('tipo_descuento')) {
            $query->where('tipo_descuento', $request->tipo_descuento);
        }
        
        // Filtro por expiración (nuevo)
        if ($request->filled('expiracion_filter')) {
            switch ($request->expiracion_filter) {
                case 'expirados':
                    $query->where('fecha_expiracion', '<', now());
                    break;
                case 'no_expirados':
                    $query->where(function($q) {
                        $q->where('fecha_expiracion', '>=', now())
                          ->orWhereNull('fecha_expiracion');
                    });
                    break;
                case 'sin_expiracion':
                    $query->whereNull('fecha_expiracion');
                    break;
                case 'proximos_7_dias':
                    $query->whereBetween('fecha_expiracion', [now(), now()->addDays(7)]);
                    break;
            }
        }
        
        $cupones = $query->orderBy('id', 'desc')->paginate(15);
        
        // Estadísticas
        $totalCupones = Cupon::count();
        $cuponesUsados = Cupon::where('usado', 1)->count();
        $cuponesActivos = Cupon::where('estatus', 'activo')->count();
        $cuponesExpirados = Cupon::where('estatus', 'expirado')->count();
        $cuponesVencidos = Cupon::where('fecha_expiracion', '<', now())->count(); // Nuevo
        
        return view('administrador.cupones.index', compact(
            'cupones', 
            'totalCupones', 
            'cuponesUsados', 
            'cuponesActivos',
            'cuponesExpirados',
            'cuponesVencidos' // Nuevo
        ));
    }

    // Mostrar formulario de creación de cupón
    public function create()
    {
        $codigoGenerado = $this->generarCodigoUnico();
        $usuarios = User::where('rol', 'estudiante')->orderBy('correo')->get();
        
        return view('administrador.cupones.create', compact('usuarios', 'codigoGenerado'));
    }

    // Guardar nuevo cupón
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50|unique:cupones,codigo',
            'usuario_genero' => 'nullable|exists:usuario,id',
            'estatus' => 'required|in:activo,inactivo,expirado',
            'tipo_descuento' => 'required|in:porcentaje,cantidad_fija',
            'valor_descuento' => 'required|numeric|min:0',
            'fecha_expiracion' => 'nullable|date|after_or_equal:today', // Nueva validación
        ]);

        try {
            DB::beginTransaction();
            
            if ($request->tipo_descuento == 'porcentaje' && $request->valor_descuento > 100) {
                throw new \Exception('El porcentaje de descuento no puede ser mayor a 100%');
            }
            
            $cupon = Cupon::create([
                'codigo' => $request->codigo,
                'usado' => 0,
                'usuario_uso' => null,
                'usuario_genero' => $request->usuario_genero ?? auth()->id(),
                'estatus' => $request->estatus,
                'fecha_genero' => now(),
                'fecha_uso' => null,
                'fecha_expiracion' => $request->fecha_expiracion, // Nuevo campo
                'tipo_descuento' => $request->tipo_descuento,
                'valor_descuento' => $request->valor_descuento,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.cupones.index')
                ->with('success', 'Cupón creado correctamente. Código: ' . $request->codigo);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear cupón: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el cupón: ' . $e->getMessage());
        }
    }

    // Mostrar un cupón específico (API para modal)
    public function show($id)
    {
        try {
            $cupon = Cupon::with(['usuarioGenero', 'usuarioUso'])->findOrFail($id);
            
            // Formatear los datos para la respuesta JSON
            $data = [
                'id' => $cupon->id,
                'codigo' => $cupon->codigo,
                'usado' => $cupon->usado,
                'estatus' => $cupon->estatus,
                'tipo_descuento' => $cupon->tipo_descuento,
                'valor_descuento' => $cupon->valor_descuento,
                'fecha_genero' => $cupon->fecha_genero,
                'fecha_uso' => $cupon->fecha_uso,
                'fecha_expiracion' => $cupon->fecha_expiracion, // Nuevo campo
                'esta_expirado' => $cupon->isExpired(), // Nuevo campo
                'usuario_genero' => $cupon->usuarioGenero ? [
                    'id' => $cupon->usuarioGenero->id,
                    'name' => $this->getUserName($cupon->usuarioGenero),
                    'correo' => $cupon->usuarioGenero->correo
                ] : null,
                'usuario_uso' => $cupon->usuarioUso ? [
                    'id' => $cupon->usuarioUso->id,
                    'name' => $this->getUserName($cupon->usuarioUso),
                    'correo' => $cupon->usuarioUso->correo
                ] : null,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al mostrar cupón: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos del cupón'
            ], 500);
        }
    }

    // Mostrar formulario de edición de cupón
    public function edit($id)
    {
        $cupon = Cupon::with(['usuarioGenero', 'usuarioUso'])->findOrFail($id);
        $usuarios = User::orderBy('correo')->get();
        
        return view('administrador.cupones.edit', compact('cupon', 'usuarios'));
    }

    // Actualizar cupón
    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:50|unique:cupones,codigo,' . $id,
            'usuario_genero' => 'nullable|exists:usuario,id',
            'estatus' => 'required|in:activo,inactivo,expirado',
            'usado' => 'required|boolean',
            'tipo_descuento' => 'required|in:porcentaje,cantidad_fija',
            'valor_descuento' => 'required|numeric|min:0',
            'fecha_expiracion' => 'nullable|date', // Nueva validación
        ]);

        try {
            $cupon = Cupon::findOrFail($id);
            
            if ($request->tipo_descuento == 'porcentaje' && $request->valor_descuento > 100) {
                throw new \Exception('El porcentaje de descuento no puede ser mayor a 100%');
            }
            
            $data = [
                'codigo' => $request->codigo,
                'usuario_genero' => $request->usuario_genero,
                'estatus' => $request->estatus,
                'usado' => $request->usado,
                'tipo_descuento' => $request->tipo_descuento,
                'valor_descuento' => $request->valor_descuento,
                'fecha_expiracion' => $request->fecha_expiracion, // Nuevo campo
            ];
            
            // Si se marca como usado pero no tiene fecha de uso
            if ($request->usado == 1 && !$cupon->fecha_uso) {
                $data['fecha_uso'] = now();
                $data['usuario_uso'] = auth()->id();
            }
            
            // Si se marca como no usado, limpiar fecha de uso y usuario que lo usó
            if ($request->usado == 0) {
                $data['fecha_uso'] = null;
                $data['usuario_uso'] = null;
            }
            
            $cupon->update($data);
            
            return redirect()->route('admin.cupones.index')
                ->with('success', 'Cupón actualizado correctamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar cupón: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el cupón: ' . $e->getMessage());
        }
    }

    // Eliminar cupón
    public function destroy($id)
    {
        try {
            $cupon = Cupon::findOrFail($id);
            
            if ($cupon->usado == 1) {
                return redirect()->route('admin.cupones.index')
                    ->with('error', 'No se puede eliminar un cupón que ya ha sido usado');
            }
            
            $cupon->delete();
            
            return redirect()->route('admin.cupones.index')
                ->with('success', 'Cupón eliminado correctamente');
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar cupón: ' . $e->getMessage());
            return redirect()->route('admin.cupones.index')
                ->with('error', 'Error al eliminar el cupón: ' . $e->getMessage());
        }
    }

    // Regenerar código de cupón (AJAX)
    public function regenerarCodigo(Request $request, $id = null)
    {
        try {
            $nuevoCodigo = $this->generarCodigoUnico();
            
            if ($id) {
                $cupon = Cupon::findOrFail($id);
                $cupon->update(['codigo' => $nuevoCodigo]);
            }
            
            return response()->json([
                'success' => true,
                'codigo' => $nuevoCodigo,
                'message' => 'Código regenerado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al regenerar el código: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== MÉTODOS ADICIONALES ====================
    
    // Obtener nombre del usuario (accesorio)
    private function getUserName($user)
    {
        if (!$user) return 'N/A';
        
        // Si el usuario tiene relación con administrador o estudiante
        if ($user->administrador) {
            return $user->administrador->nombre_completo ?? $user->correo;
        }
        
        if ($user->estudiante) {
            return $user->estudiante->nombre_completo ?? $user->correo;
        }
        
        return $user->correo;
    }
    
    // Generar código único automático
    private function generarCodigoUnico()
    {
        do {
            $codigo = strtoupper(Str::random(15));
        } while (Cupon::where('codigo', $codigo)->exists());
        
        return $codigo;
    }
    
    // Generar cupones masivos
    public function generarMasivo(Request $request)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:100',
            'estatus' => 'required|in:activo,inactivo,expirado',
            'tipo_descuento' => 'required|in:porcentaje,cantidad_fija',
            'valor_descuento' => 'required|numeric|min:0',
            'fecha_expiracion' => 'nullable|date|after_or_equal:today', // Nueva validación
        ]);
        
        try {
            DB::beginTransaction();
            
            if ($request->tipo_descuento == 'porcentaje' && $request->valor_descuento > 100) {
                throw new \Exception('El porcentaje de descuento no puede ser mayor a 100%');
            }
            
            $generados = 0;
            
            for ($i = 0; $i < $request->cantidad; $i++) {
                do {
                    $codigo = strtoupper(Str::random(15));
                } while (Cupon::where('codigo', $codigo)->exists());
                
                Cupon::create([
                    'codigo' => $codigo,
                    'usado' => 0,
                    'usuario_uso' => null,
                    'usuario_genero' => auth()->id(),
                    'estatus' => $request->estatus,
                    'fecha_genero' => now(),
                    'fecha_uso' => null,
                    'fecha_expiracion' => $request->fecha_expiracion, // Nuevo campo
                    'tipo_descuento' => $request->tipo_descuento,
                    'valor_descuento' => $request->valor_descuento,
                ]);
                
                $generados++;
            }
            
            DB::commit();
            
            return redirect()->route('admin.cupones.index')
                ->with('success', "Se generaron {$generados} cupones correctamente");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al generar cupones masivos: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al generar cupones: ' . $e->getMessage());
        }
    }
    
    // Marcar cupón como usado manualmente
    public function marcarUsado($id)
    {
        try {
            $cupon = Cupon::findOrFail($id);
            
            if ($cupon->usado == 1) {
                return redirect()->route('admin.cupones.index')
                    ->with('error', 'El cupón ya estaba marcado como usado');
            }
            
            $cupon->update([
                'usado' => 1,
                'fecha_uso' => now(),
                'usuario_uso' => auth()->id(),
            ]);
            
            return redirect()->route('admin.cupones.index')
                ->with('success', 'Cupón marcado como usado correctamente');
                
        } catch (\Exception $e) {
            Log::error('Error al marcar cupón como usado: ' . $e->getMessage());
            return redirect()->route('admin.cupones.index')
                ->with('error', 'Error al marcar el cupón como usado');
        }
    }
    
    // Exportar cupones a CSV
    public function exportar(Request $request)
    {
        $query = Cupon::with(['usuarioGenero', 'usuarioUso']);
        
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }
        
        if ($request->filled('tipo_descuento')) {
            $query->where('tipo_descuento', $request->tipo_descuento);
        }
        
        $cupones = $query->get();
        
        $filename = 'cupones_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // Actualizar encabezados del CSV
        fputcsv($handle, ['ID', 'Código', 'Estado', 'Usado', 'Tipo Descuento', 'Valor Descuento', 'Generado por', 'Usado por', 'Fecha Generación', 'Fecha Uso', 'Fecha Expiración', '¿Expirado?']);
        
        foreach ($cupones as $cupon) {
            fputcsv($handle, [
                $cupon->id,
                $cupon->codigo,
                $cupon->estatus,
                $cupon->usado ? 'Sí' : 'No',
                $cupon->tipo_descuento == 'porcentaje' ? 'Porcentaje' : 'Cantidad fija',
                $cupon->tipo_descuento == 'porcentaje' ? $cupon->valor_descuento . '%' : '$' . number_format($cupon->valor_descuento, 2),
                $cupon->usuarioGenero?->correo ?? 'N/A',
                $cupon->usuarioUso?->correo ?? 'N/A',
                $cupon->fecha_genero?->format('d/m/Y H:i') ?? 'N/A',
                $cupon->fecha_uso?->format('d/m/Y H:i') ?? 'N/A',
                $cupon->fecha_expiracion?->format('d/m/Y H:i') ?? 'Sin expiración', // Nuevo campo
                $cupon->isExpired() ? 'Sí' : 'No', // Nuevo campo
            ]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
    
    // NUEVO MÉTODO: Actualizar automáticamente el estatus basado en expiración
    public function actualizarEstatusPorExpiracion()
    {
        try {
            // Actualizar cupones expirados que no están usados
            $actualizados = Cupon::where('usado', 0)
                ->where('fecha_expiracion', '<', now())
                ->where('estatus', 'activo')
                ->update(['estatus' => 'expirado']);
            
            return response()->json([
                'success' => true,
                'message' => "Se actualizaron {$actualizados} cupones a expirados"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar estatus por expiración: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los estatus'
            ], 500);
        }
    }
}