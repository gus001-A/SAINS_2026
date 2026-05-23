<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InteraccionCallCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InteraccionCallCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Obtener los parámetros de filtro
            $estado = $request->get('estado');
            $fechaInicio = $request->get('fecha_inicio');
            $fechaFin = $request->get('fecha_fin');
            $busqueda = $request->get('busqueda');
            
            // Construir la consulta con las relaciones necesarias
            $interacciones = InteraccionCallCenter::with([
                'administrador.administrador', 
                'estudiante.estudiante'
            ])
            ->when($estado, function ($query, $estado) {
                return $query->where('estado_seguimiento', $estado);
            })
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('fecha_contacto', [$fechaInicio, $fechaFin]);
            })
            ->when($busqueda, function ($query) use ($busqueda) {
                return $query->where(function ($q) use ($busqueda) {
                    $q->whereHas('administrador', function ($subq) use ($busqueda) {
                        $subq->where('name', 'like', "%{$busqueda}%")
                            ->orWhereHas('administrador', function ($adminQuery) use ($busqueda) {
                                $adminQuery->where('nombre', 'like', "%{$busqueda}%")
                                    ->orWhere('apellido_paterno', 'like', "%{$busqueda}%")
                                    ->orWhere('apellido_materno', 'like', "%{$busqueda}%");
                            });
                    })->orWhereHas('estudiante', function ($subq) use ($busqueda) {
                        $subq->where('name', 'like', "%{$busqueda}%")
                            ->orWhereHas('estudiante', function ($estQuery) use ($busqueda) {
                                $estQuery->where('nombre', 'like', "%{$busqueda}%")
                                    ->orWhere('paterno', 'like', "%{$busqueda}%")
                                    ->orWhere('materno', 'like', "%{$busqueda}%");
                            });
                    })->orWhere('motivo_contacto', 'like', "%{$busqueda}%")
                        ->orWhere('resultado', 'like', "%{$busqueda}%")
                        ->orWhere('nota', 'like', "%{$busqueda}%");
                });
            })
            ->orderBy('fecha_contacto', 'desc')
            ->orderBy('hora_contacto', 'desc')
            ->paginate(15);
            
            // Procesar nombres manualmente para asegurar que se muestren correctamente
            foreach ($interacciones as $interaccion) {
                // Nombre del administrador
                if ($interaccion->administrador && $interaccion->administrador->administrador) {
                    $interaccion->admin_nombre_completo = trim(
                        ($interaccion->administrador->administrador->nombre ?? '') . ' ' . 
                        ($interaccion->administrador->administrador->apellido_paterno ?? '') . ' ' . 
                        ($interaccion->administrador->administrador->apellido_materno ?? '')
                    );
                } else {
                    $interaccion->admin_nombre_completo = $interaccion->administrador->name ?? 'N/A';
                }
                
                // Nombre del estudiante
                if ($interaccion->estudiante && $interaccion->estudiante->estudiante) {
                    $interaccion->est_nombre_completo = trim(
                        ($interaccion->estudiante->estudiante->nombre ?? '') . ' ' . 
                        ($interaccion->estudiante->estudiante->paterno ?? '') . ' ' . 
                        ($interaccion->estudiante->estudiante->materno ?? '')
                    );
                } else {
                    $interaccion->est_nombre_completo = $interaccion->estudiante->name ?? 'N/A';
                }
            }
            
            // Para los selects de filtros
            $estadosSeguimiento = [
                'pendiente' => 'Pendiente',
                'en_proceso' => 'En Proceso',
                'finalizado' => 'Finalizado'
            ];
            
            $tiposContacto = [
                'llamada' => 'Llamada',
                'email' => 'Email',
                'whatsapp' => 'WhatsApp'
            ];
            
            // Calcular estadísticas para las tarjetas
            $totalInteracciones = InteraccionCallCenter::count();
            $pendientes = InteraccionCallCenter::where('estado_seguimiento', 'pendiente')->count();
            $enProceso = InteraccionCallCenter::where('estado_seguimiento', 'en_proceso')->count();
            $finalizados = InteraccionCallCenter::where('estado_seguimiento', 'finalizado')->count();
            
            return view('administrador.interacciones_call_center.index', compact(
                'interacciones',
                'estado',
                'fechaInicio',
                'fechaFin',
                'busqueda',
                'estadosSeguimiento',
                'tiposContacto',
                'totalInteracciones',
                'pendientes',
                'enProceso',
                'finalizados'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error en index de interacciones: ' . $e->getMessage());
            return redirect()->route('admin.callcenter.index')
                ->with('error', 'Error al cargar las interacciones: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Obtener estudiantes con plan inactivo o sin plan
            $estudiantes = User::whereHas('estudiante', function($query) {
                $query->where('plan_activo', '!=', true)
                    ->orWhereNull('plan_activo');
            })->with('estudiante')->get();
            
            // Procesar nombres completos de estudiantes para el select
            foreach ($estudiantes as $estudiante) {
                if ($estudiante->estudiante) {
                    $estudiante->nombre_completo = trim(
                        ($estudiante->estudiante->nombre ?? '') . ' ' . 
                        ($estudiante->estudiante->paterno ?? '') . ' ' . 
                        ($estudiante->estudiante->materno ?? '')
                    );
                } else {
                    $estudiante->nombre_completo = $estudiante->name ?? 'N/A';
                }
            }
            
            $estadosSeguimiento = [
                'pendiente' => 'Pendiente',
                'en_proceso' => 'En Proceso',
                'finalizado' => 'Finalizado'
            ];
            
            $tiposContacto = [
                'llamada' => 'Llamada',
                'email' => 'Email',
                'whatsapp' => 'WhatsApp'
            ];
            
            return view('administrador.interacciones_call_center.create', compact('estudiantes', 'estadosSeguimiento', 'tiposContacto'));
            
        } catch (\Exception $e) {
            Log::error('Error en create de interacciones: ' . $e->getMessage());
            return redirect()->route('admin.callcenter.index')
                ->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_estudiante' => 'required|exists:usuario,id',
            'fecha_contacto' => 'required|date',
            'hora_contacto' => 'required',
            'tipo_contacto' => 'required|in:llamada,email,whatsapp',
            'estado_seguimiento' => 'required|in:pendiente,en_proceso,finalizado',
            'motivo_contacto' => 'required|string',
            'nota' => 'nullable|string',
            'resultado' => 'nullable|string',
            'proximo_contacto' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();
            
            $interaccion = InteraccionCallCenter::create([
                'id_usuario_contacta' => Auth::id(),
                'id_estudiante' => $request->id_estudiante,
                'fecha_contacto' => $request->fecha_contacto,
                'hora_contacto' => $request->hora_contacto,
                'tipo_contacto' => $request->tipo_contacto,
                'estado_seguimiento' => $request->estado_seguimiento,
                'motivo_contacto' => $request->motivo_contacto,
                'nota' => $request->nota,
                'resultado' => $request->resultado,
                'proximo_contacto' => $request->proximo_contacto,
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Interacción registrada exitosamente',
                    'data' => $interaccion
                ]);
            }
            
            return redirect()->route('admin.callcenter.index')
                ->with('success', 'Interacción registrada exitosamente.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            
            // Mensaje específico para error de campo muy largo
            if (str_contains($errorMessage, 'Data too long for column')) {
                $errorMessage = 'El texto ingresado es demasiado largo. Por favor, reduce la longitud del motivo, nota o resultado.';
            }
            
            Log::error('Error en store de interacción: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error al registrar la interacción: ' . $errorMessage)
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en store de interacción: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error al registrar la interacción: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $interaccion = InteraccionCallCenter::with([
                'administrador.administrador', 
                'estudiante.estudiante'
            ])->findOrFail($id);
            
            // Procesar nombres completos
            if ($interaccion->administrador && $interaccion->administrador->administrador) {
                $interaccion->admin_nombre_completo = trim(
                    ($interaccion->administrador->administrador->nombre ?? '') . ' ' . 
                    ($interaccion->administrador->administrador->apellido_paterno ?? '') . ' ' . 
                    ($interaccion->administrador->administrador->apellido_materno ?? '')
                );
            } else {
                $interaccion->admin_nombre_completo = $interaccion->administrador->name ?? 'N/A';
            }
            
            if ($interaccion->estudiante && $interaccion->estudiante->estudiante) {
                $interaccion->est_nombre_completo = trim(
                    ($interaccion->estudiante->estudiante->nombre ?? '') . ' ' . 
                    ($interaccion->estudiante->estudiante->paterno ?? '') . ' ' . 
                    ($interaccion->estudiante->estudiante->materno ?? '')
                );
            } else {
                $interaccion->est_nombre_completo = $interaccion->estudiante->name ?? 'N/A';
            }
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $interaccion
                ]);
            }
            
            return view('administrador.interacciones_call_center.show', compact('interaccion'));
            
        } catch (\Exception $e) {
            Log::error('Error en show de interacción: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar la interacción: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.callcenter.index')
                ->with('error', 'Error al cargar la interacción: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $interaccion = InteraccionCallCenter::findOrFail($id);
            $estudiantes = User::whereHas('estudiante')->with('estudiante')->get();
            
            // Procesar nombres completos de estudiantes
            foreach ($estudiantes as $estudiante) {
                if ($estudiante->estudiante) {
                    $estudiante->nombre_completo = trim(
                        ($estudiante->estudiante->nombre ?? '') . ' ' . 
                        ($estudiante->estudiante->paterno ?? '') . ' ' . 
                        ($estudiante->estudiante->materno ?? '')
                    );
                } else {
                    $estudiante->nombre_completo = $estudiante->name ?? 'N/A';
                }
            }
            
            $estadosSeguimiento = [
                'pendiente' => 'Pendiente',
                'en_proceso' => 'En Proceso',
                'finalizado' => 'Finalizado'
            ];
            
            $tiposContacto = [
                'llamada' => 'Llamada',
                'email' => 'Email',
                'whatsapp' => 'WhatsApp'
            ];
            
            return view('administrador.interacciones_call_center.edit', compact('interaccion', 'estudiantes', 'estadosSeguimiento', 'tiposContacto'));
            
        } catch (\Exception $e) {
            Log::error('Error en edit de interacción: ' . $e->getMessage());
            return redirect()->route('admin.callcenter.index')
                ->with('error', 'Error al cargar el formulario de edición: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_estudiante' => 'required|exists:usuario,id',
            'fecha_contacto' => 'required|date',
            'hora_contacto' => 'required',
            'tipo_contacto' => 'required|in:llamada,email,whatsapp',
            'estado_seguimiento' => 'required|in:pendiente,en_proceso,finalizado',
            'motivo_contacto' => 'required|string',
            'nota' => 'nullable|string',
            'resultado' => 'nullable|string',
            'proximo_contacto' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();
            
            $interaccion = InteraccionCallCenter::findOrFail($id);
            $interaccion->update([
                'id_estudiante' => $request->id_estudiante,
                'fecha_contacto' => $request->fecha_contacto,
                'hora_contacto' => $request->hora_contacto,
                'tipo_contacto' => $request->tipo_contacto,
                'estado_seguimiento' => $request->estado_seguimiento,
                'motivo_contacto' => $request->motivo_contacto,
                'nota' => $request->nota,
                'resultado' => $request->resultado,
                'proximo_contacto' => $request->proximo_contacto,
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Interacción actualizada exitosamente',
                    'data' => $interaccion
                ]);
            }
            
            return redirect()->route('admin.callcenter.index')
                ->with('success', 'Interacción actualizada exitosamente.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'Data too long for column')) {
                $errorMessage = 'El texto ingresado es demasiado largo. Por favor, reduce la longitud del motivo, nota o resultado.';
            }
            
            Log::error('Error en update de interacción: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error al actualizar la interacción: ' . $errorMessage)
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en update de interacción: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error al actualizar la interacción: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $interaccion = InteraccionCallCenter::findOrFail($id);
            $interaccion->delete();
            
            DB::commit();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Interacción eliminada exitosamente'
                ]);
            }
            
            return redirect()->route('admin.callcenter.index')
                ->with('success', 'Interacción eliminada exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en destroy de interacción: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.callcenter.index')
                ->with('error', 'Error al eliminar la interacción: ' . $e->getMessage());
        }
    }
    
    /**
     * Cambiar el estado de seguimiento de una interacción
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,finalizado'
        ]);
        
        try {
            $interaccion = InteraccionCallCenter::findOrFail($id);
            $interaccion->estado_seguimiento = $request->estado;
            $interaccion->save();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Estado actualizado exitosamente',
                    'estado' => $interaccion->estado_seguimiento
                ]);
            }
            
            return redirect()->back()->with('success', 'Estado actualizado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error en cambiarEstado: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar estado: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error al actualizar el estado.');
        }
    }
    
    /**
     * Exportar interacciones a Excel/CSV
     */
    public function exportar(Request $request)
    {
        try {
            $query = InteraccionCallCenter::with(['administrador.administrador', 'estudiante.estudiante']);
            
            // Aplicar filtros
            if ($request->estado) {
                $query->where('estado_seguimiento', $request->estado);
            }
            
            if ($request->fecha_inicio && $request->fecha_fin) {
                $query->whereBetween('fecha_contacto', [$request->fecha_inicio, $request->fecha_fin]);
            }
            
            $interacciones = $query->orderBy('fecha_contacto', 'desc')->get();
            
            // Preparar datos para exportar
            $data = [];
            foreach ($interacciones as $interaccion) {
                // Nombre del administrador
                if ($interaccion->administrador && $interaccion->administrador->administrador) {
                    $nombreAdmin = trim(
                        ($interaccion->administrador->administrador->nombre ?? '') . ' ' . 
                        ($interaccion->administrador->administrador->apellido_paterno ?? '')
                    );
                } else {
                    $nombreAdmin = $interaccion->administrador->name ?? 'N/A';
                }
                
                // Nombre del estudiante
                if ($interaccion->estudiante && $interaccion->estudiante->estudiante) {
                    $nombreEstudiante = trim(
                        ($interaccion->estudiante->estudiante->nombre ?? '') . ' ' . 
                        ($interaccion->estudiante->estudiante->paterno ?? '')
                    );
                } else {
                    $nombreEstudiante = $interaccion->estudiante->name ?? 'N/A';
                }
                
                $data[] = [
                    'ID' => $interaccion->id,
                    'Administrador' => $nombreAdmin,
                    'Estudiante' => $nombreEstudiante,
                    'Fecha' => $interaccion->fecha_contacto,
                    'Hora' => $interaccion->hora_contacto,
                    'Tipo' => ucfirst($interaccion->tipo_contacto ?? 'N/A'),
                    'Estado' => ucfirst(str_replace('_', ' ', $interaccion->estado_seguimiento ?? 'N/A')),
                    'Motivo' => $interaccion->motivo_contacto ?? 'N/A',
                    'Nota' => $interaccion->nota ?? 'N/A',
                    'Resultado' => $interaccion->resultado ?? 'N/A',
                    'Próximo Contacto' => $interaccion->proximo_contacto ?? 'N/A',
                    'Fecha Registro' => $interaccion->created_at ?? 'N/A',
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => count($data),
                'message' => 'Exportación generada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en exportar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar: ' . $e->getMessage()
            ], 500);
        }
    }
}