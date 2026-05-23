<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\User;
use App\Models\Pago;
use App\Models\Cupon;
use App\Models\ExamenGenerado;
use App\Models\TiempoEstudio;
use App\Models\ProgresoVideo;
use App\Models\Video;
use App\Models\Asignatura;
use App\Models\Clase;
use App\Models\ExamenRealizado;
use App\Models\AreaPregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlumnoController extends Controller
{
    // Panel principal del alumno
    public function dashboard()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        $perfilCompleto = $estudiante ? true : false;
        $tieneFoto = false;
        if (!$estudiante) {
            return redirect()->route('estudiante.completar-perfil')
                ->with('warning', 'Por favor completa tu perfil para continuar');
        }else if ($estudiante && $estudiante->foto) {
            $tieneFoto = true;
        }
        
        return view('estudiante.dashboard', compact('user', 'estudiante', 'perfilCompleto', 'tieneFoto'));
    }

    public function progreso()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        $asignaturas = Asignatura::with(['clases' => function($query) {
            $query->orderBy('num_clase', 'asc');
        }])->get();
        
        return view('estudiante.progreso', compact('estudiante', 'asignaturas'));
    }

    // ========== MÉTODOS PARA FOTO DE PERFIL ==========
    
    public function subirFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user = Auth::user();
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Primero completa tu perfil'
                ], 400);
            }
            
            if ($estudiante->foto && Storage::disk('public')->exists($estudiante->foto)) {
                Storage::disk('public')->delete($estudiante->foto);
            }
            
            $fotoPath = $request->file('foto')->store('fotos_perfil', 'public');
            
            $estudiante->foto = $fotoPath;
            $estudiante->save();
            
            return response()->json([
                'success' => true,
                'message' => '¡Foto de perfil actualizada exitosamente!',
                'foto_url' => Storage::url($fotoPath)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al subir foto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la foto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFoto()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante || !$estudiante->foto) {
            return response()->json([
                'success' => false,
                'message' => 'No hay foto de perfil',
                'tiene_foto' => false
            ]);
        }
        
        return response()->json([
            'success' => true,
            'tiene_foto' => true,
            'foto_url' => Storage::url($estudiante->foto),
            'foto_path' => $estudiante->foto
        ]);
    }

    public function eliminarFoto()
    {
        try {
            $user = Auth::user();
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estudiante no encontrado'
                ], 404);
            }
            
            if ($estudiante->foto && Storage::disk('public')->exists($estudiante->foto)) {
                Storage::disk('public')->delete($estudiante->foto);
                $estudiante->foto = null;
                $estudiante->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Foto eliminada correctamente'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No hay foto para eliminar'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar foto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la foto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEstudiante()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        return response()->json([
            'success' => true,
            'estudiante' => $estudiante,
            'tiene_perfil' => $estudiante ? true : false,
            'tiene_foto' => $estudiante && $estudiante->foto ? true : false,
            'foto_url' => $estudiante && $estudiante->foto ? Storage::url($estudiante->foto) : null
        ]);
    }

    // ========== MÉTODOS PARA ESTADÍSTICAS ==========

    public function getProgresoApi()
    {
        try {
            Log::info('📊 getProgresoApi() - Iniciando...');
            
            $user = Auth::user();
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => true,
                    'total_examenes' => 0,
                    'lecciones_vistas' => 0,
                    'mejor_puntaje' => 0,
                    'horas_estudio' => 0,
                    'aprobados' => 0,
                    'reprobados' => 0,
                    'progreso' => 0,
                    'promedio' => 0
                ]);
            }
            
            $examenesRealizados = ExamenRealizado::where('estudiante', $estudiante->id)->get();
            $totalExamenes = $examenesRealizados->count();
            $mejorPuntaje = $examenesRealizados->max('calificacion') ?? 0;
            $aprobados = $examenesRealizados->where('calificacion', '>=', 70)->count();
            $reprobados = $examenesRealizados->where('calificacion', '<', 70)->count();
            $promedio = $totalExamenes > 0 ? round($examenesRealizados->avg('calificacion'), 1) : 0;
            $progreso = $totalExamenes > 0 ? min(100, round(($totalExamenes / 20) * 100)) : 0;
            
            $leccionesVistas = ProgresoVideo::where('estudiante_id', $estudiante->id)
                ->where('completado', true)
                ->count();
            
            $tiempoEstudio = TiempoEstudio::where('estudiante_id', $estudiante->id)->sum('segundos_estudiados');
            $horasEstudio = round($tiempoEstudio / 3600, 1);
            
            return response()->json([
                'success' => true,
                'total_examenes' => $totalExamenes,
                'lecciones_vistas' => $leccionesVistas,
                'mejor_puntaje' => $mejorPuntaje,
                'horas_estudio' => $horasEstudio,
                'aprobados' => $aprobados,
                'reprobados' => $reprobados,
                'progreso' => $progreso,
                'promedio' => $promedio
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en getProgresoApi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    // ========== MÉTODOS PARA ESTADÍSTICAS CORREGIDOS ==========

    public function getEstadisticas()
    {
        try {
            Log::info('📊 getEstadisticas() - Iniciando...');
            
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                Log::warning('Estudiante no encontrado para usuario: ' . $user->id);
                return response()->json([
                    'success' => true,
                    'total_examenes' => 0,
                    'lecciones_vistas' => 0,
                    'mejor_puntaje' => 0,
                    'horas_estudio' => 0,
                    'aprobados' => 0,
                    'reprobados' => 0,
                    'progreso' => 0,
                    'promedio' => 0
                ]);
            }
            
            // Obtener exámenes realizados
            $examenesRealizados = ExamenRealizado::where('estudiante', $estudiante->id)->get();
            $totalExamenes = $examenesRealizados->count();
            $mejorPuntaje = $examenesRealizados->max('calificacion') ?? 0;
            $aprobados = $examenesRealizados->where('calificacion', '>=', 70)->count();
            $reprobados = $examenesRealizados->where('calificacion', '<', 70)->count();
            $promedio = $totalExamenes > 0 ? round($examenesRealizados->avg('calificacion'), 1) : 0;
            
            // Calcular progreso (basado en exámenes completados)
            $progreso = $totalExamenes > 0 ? min(100, round(($totalExamenes / 20) * 100)) : 0;
            
            // Obtener lecciones vistas (videos completados)
            $leccionesVistas = ProgresoVideo::where('estudiante_id', $estudiante->id)
                ->where('completado', true)
                ->count();
            
            // Obtener tiempo de estudio
            $tiempoEstudio = TiempoEstudio::where('estudiante_id', $estudiante->id)->sum('segundos_estudiados');
            $horasEstudio = round($tiempoEstudio / 3600, 1);
            
            Log::info('✅ Estadísticas calculadas:', [
                'estudiante_id' => $estudiante->id,
                'total_examenes' => $totalExamenes,
                'lecciones_vistas' => $leccionesVistas,
                'mejor_puntaje' => $mejorPuntaje,
                'aprobados' => $aprobados,
                'promedio' => $promedio
            ]);
            
            return response()->json([
                'success' => true,
                'total_examenes' => $totalExamenes,
                'lecciones_vistas' => $leccionesVistas,
                'mejor_puntaje' => $mejorPuntaje,
                'horas_estudio' => $horasEstudio,
                'aprobados' => $aprobados,
                'reprobados' => $reprobados,
                'progreso' => $progreso,
                'promedio' => $promedio
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en getEstadisticas: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUltimosExamenes()
    {
        try {
            Log::info('📋 getUltimosExamenes() - Iniciando...');
            
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'examenes' => []]);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                Log::warning('Estudiante no encontrado');
                return response()->json([
                    'success' => true,
                    'examenes' => []
                ]);
            }
            
            Log::info('Estudiante ID: ' . $estudiante->id);
            
            // Obtener últimos 5 exámenes ordenados por ID descendente
            $examenes = ExamenRealizado::where('estudiante', $estudiante->id)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
            
            Log::info('Exámenes encontrados: ' . $examenes->count());
            
            $examenesFormateados = $examenes->map(function($examen) {
                $fecha = $examen->fecha_inicio;
                if (!$fecha || $fecha == '0000-00-00' || $fecha == '1970-01-01') {
                    $fecha = date('Y-m-d');
                }
                
                return [
                    'id' => $examen->id,
                    'fecha' => Carbon::parse($fecha)->format('d/m/Y'),
                    'calificacion' => round($examen->calificacion ?? 0, 1)
                ];
            });
            
            Log::info('✅ Últimos exámenes cargados:', ['count' => $examenesFormateados->count()]);
            
            return response()->json([
                'success' => true,
                'examenes' => $examenesFormateados
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en getUltimosExamenes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'examenes' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getHistorialExamenes()
{
    try {
        Log::info('📜 getHistorialExamenes() - Iniciando...');
        
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'examenes' => []]);
        }
        
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante) {
            return response()->json([
                'success' => true,
                'examenes' => []
            ]);
        }
        
        // Obtener TODOS los exámenes realizados por el estudiante
        $examenes = ExamenRealizado::where('estudiante', $estudiante->id)
            ->orderBy('id', 'desc')
            ->get();
        
        Log::info('Exámenes encontrados en BD: ' . $examenes->count());
        
        // Mapear correctamente los campos
        $examenesFormateados = $examenes->map(function($examen) {
            // Obtener el tipo de examen desde ExamenGenerado
            $examenGenerado = ExamenGenerado::find($examen->examen);
            $tipoExamen = $examenGenerado ? $examenGenerado->tipo_examen : 'Simulador';
            
            // Formatear fecha correctamente
            $fecha = $examen->fecha_inicio;
            $fechaFormateada = '';
            
            if ($fecha && $fecha !== '0000-00-00' && $fecha !== '1970-01-01') {
                try {
                    $fechaFormateada = Carbon::parse($fecha)->format('d/m/Y');
                } catch (\Exception $e) {
                    $fechaFormateada = date('d/m/Y');
                }
            } else {
                $fechaFormateada = date('d/m/Y');
            }
            
            return [
                'id' => $examen->id,
                'examen_id' => $examen->examen,
                'fecha' => $fechaFormateada,
                'fecha_inicio' => $examen->fecha_inicio,
                'hora_inicio' => $examen->hora_inicio ?? '--:--:--',
                'hora_fin' => $examen->hora_fin ?? '--:--:--',
                'fecha_fin' => $examen->fecha_fin,
                'tiempo' => $examen->tiempo ?? '00:00:00',
                'calificacion' => round($examen->calificacion ?? 0, 2),
                'intento' => $examen->intento ?? 1,
                'tipo_examen' => $tipoExamen,
                'nombre_examen' => $examenGenerado ? ($examenGenerado->nombre ?? $this->getNombreExamen($tipoExamen, null)) : 'Examen SAINS',
                'aprobado' => ($examen->calificacion ?? 0) >= 70
            ];
        });
        
        Log::info('✅ Exámenes formateados: ' . $examenesFormateados->count());
        
        return response()->json([
            'success' => true,
            'examenes' => $examenesFormateados
        ]);
        
    } catch (\Exception $e) {
        Log::error('❌ Error en getHistorialExamenes: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'success' => false,
            'examenes' => [],
            'message' => $e->getMessage()
        ], 500);
    }
}

  private function getNombreExamen($tipo, $examenGenerado)
{
    $tipos = [
        'Materia' => 'Examen por Materia',
        'materia' => 'Examen por Materia',
        'Curso' => 'Examen Final del Curso',
        'curso' => 'Examen Final del Curso',
        'Simulación' => 'Simulador SAINS',
        'simulacion' => 'Simulador SAINS',
        'Simulador' => 'Simulador SAINS',
        'simulador' => 'Simulador SAINS'
    ];
    
    return $tipos[$tipo] ?? 'Examen SAINS';
}
    // ========== MÉTODOS PARA TIEMPO DE ESTUDIO ==========
    
    public function heartbeat(Request $request)
    {
        try {
            Log::info('💓 heartbeat() - Iniciando...');
            
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estudiante no encontrado'
                ]);
            }
            
            $tiempo = TiempoEstudio::agregarTiempo($estudiante->id, 60);
            
            Log::info('✅ Heartbeat registrado - Minutos hoy: ' . ($tiempo->minutos_estudiados ?? 0));
            
            return response()->json([
                'success' => true,
                'message' => 'Actividad registrada',
                'plan_activo' => $estudiante->plan_activo,
                'tiempo_hoy' => [
                    'minutos' => $tiempo->minutos_estudiados ?? 0,
                    'horas' => $tiempo->horas_estudiadas ?? 0
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en heartbeat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar actividad'
            ], 500);
        }
    }

    public function getTiempoEstudio()
    {
        try {
            Log::info('⏱️ getTiempoEstudio() - Iniciando...');
            
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                Log::warning('Estudiante no encontrado');
                return response()->json([
                    'success' => true,
                    'hoy' => ['segundos' => 0, 'minutos' => 0, 'horas' => 0],
                    'total' => ['segundos' => 0, 'minutos' => 0, 'horas' => 0]
                ]);
            }
            
            $hoy = TiempoEstudio::getTiempoHoy($estudiante->id);
            $totalSegundos = TiempoEstudio::where('estudiante_id', $estudiante->id)->sum('segundos_estudiados');
            
            Log::info('✅ Tiempo estudio cargado:', [
                'hoy_minutos' => $hoy['minutos'],
                'total_segundos' => $totalSegundos,
                'total_minutos' => round($totalSegundos / 60)
            ]);
            
            return response()->json([
                'success' => true,
                'hoy' => [
                    'segundos' => $hoy['segundos'],
                    'minutos' => $hoy['minutos'],
                    'horas' => $hoy['horas']
                ],
                'total' => [
                    'segundos' => $totalSegundos,
                    'minutos' => round($totalSegundos / 60),
                    'horas' => round($totalSegundos / 3600, 1)
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en getTiempoEstudio: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tiempo de estudio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function examenMateria($examenId)
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante || !$estudiante->plan_activo) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Necesitas un plan activo para acceder al examen');
        }
        
        $examen = ExamenGenerado::where('id', $examenId)
                            ->where(function($query) {
                                $query->where('tipo_examen', 'Materia')
                                      ->orWhere('tipo_examen', 'materia');
                            })
                            ->first();
        
        if (!$examen) {
            return redirect()->route('estudiante.clases-premium')->with('error', 'Examen de materia no encontrado');
        }
        
        $preguntas = $examen->preguntas()->get();
        
        $preguntasDB = DB::table('preguntas')
            ->join('apoyo_preguntas', 'preguntas.id', '=', 'apoyo_preguntas.pregunta')
            ->where('apoyo_preguntas.examen', $examen->id)
            ->select('preguntas.*')
            ->get();
        
        if ($preguntas->isEmpty() && !$preguntasDB->isEmpty()) {
            $preguntas = $preguntasDB;
        }
        
        if ($preguntas->isEmpty()) {
            return redirect()->route('estudiante.clases-premium')->with('error', 'Este examen no tiene preguntas configuradas');
        }
        
        $asignaturaNombre = 'Materia';
        if ($preguntas->isNotEmpty()) {
            $primeraPregunta = $preguntas->first();
            if (is_object($primeraPregunta) && isset($primeraPregunta->id_area)) {
                $area = AreaPregunta::find($primeraPregunta->id_area);
                if ($area) {
                    $asignaturaNombre = $area->nombre;
                }
            }
        }
        
        $intentosRealizados = ExamenRealizado::where('estudiante', $estudiante->id)
                                        ->where('examen', $examen->id)
                                        ->count();
        $intento = $intentosRealizados + 1;
        $mejorCalificacion = ExamenRealizado::where('estudiante', $estudiante->id)
                                        ->where('examen', $examen->id)
                                        ->max('calificacion');
        
        return view('estudiante.examen-materia', compact('examen', 'preguntas', 'estudiante', 'intento', 'mejorCalificacion', 'asignaturaNombre'));
    }

    public function responderExamenMateria(Request $request)
    {
        try {
            Log::info('========== INICIANDO RESPONDER EXAMEN MATERIA ==========');
            
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Primero completa tu perfil'
                ], 400);
            }
            
            if (!$estudiante->plan_activo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Necesitas un plan activo para realizar el examen'
                ], 403);
            }
            
            $examen_id = $request->examen_id;
            $respuestas = $request->respuestas;
            $tiempoUtilizadoSegundos = intval($request->tiempo_utilizado_segundos ?? 0);
            
            if (!$examen_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se especificó el examen'
                ], 400);
            }
            
            if (!$respuestas || count($respuestas) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron respuestas'
                ], 400);
            }
            
            $examen = ExamenGenerado::find($examen_id);
            if (!$examen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Examen no encontrado'
                ], 404);
            }
            
            $preguntasExamen = $examen->preguntas()->get();
            $totalPreguntas = $preguntasExamen->count();
            $aciertos = 0;
            
            foreach ($preguntasExamen as $pregunta) {
                $respuestaUsuario = $respuestas[$pregunta->id] ?? null;
                $opcionCorrecta = $pregunta->respuesta_correcta;
                
                if ($respuestaUsuario && $respuestaUsuario === $opcionCorrecta) {
                    $aciertos++;
                }
            }
            
            $calificacion = $totalPreguntas > 0 ? round(($aciertos / $totalPreguntas) * 100) : 0;
            
            $intento = ExamenRealizado::where('estudiante', $estudiante->id)
                                    ->where('examen', $examen->id)
                                    ->count() + 1;
            
            $horas = floor($tiempoUtilizadoSegundos / 3600);
            $minutos = floor(($tiempoUtilizadoSegundos % 3600) / 60);
            $segundos = $tiempoUtilizadoSegundos % 60;
            $tiempoFormateado = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);
            
            $ahora = Carbon::now();
            $fechaActual = $ahora->toDateString();
            $horaFin = $ahora->toTimeString();
            $horaInicioCalculada = $ahora->copy()->subSeconds($tiempoUtilizadoSegundos);
            $horaInicioStr = $horaInicioCalculada->toTimeString();
            
            $examenRealizado = ExamenRealizado::create([
                'estudiante' => $estudiante->id,
                'examen' => $examen->id,
                'intento' => $intento,
                'calificacion' => $calificacion,
                'fecha_inicio' => $fechaActual,
                'hora_inicio' => $horaInicioStr,
                'fecha_fin' => $fechaActual,
                'hora_fin' => $horaFin,
                'tiempo' => $tiempoFormateado
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Examen completado exitosamente',
                'calificacion' => $calificacion,
                'aciertos' => $aciertos,
                'total' => $totalPreguntas,
                'intento' => $intento,
                'tiempo_utilizado' => $tiempoFormateado,
                'redirect' => route('estudiante.resultados', $examenRealizado->id)
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en responderExamenMateria: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el examen: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== MÉTODOS PARA SIMULADOR DE EXAMEN ==========

    public function simulador(Request $request = null, $examenId = null)
    {
        if (is_numeric($request) && $examenId === null) {
            $examenId = $request;
            $request = null;
        }
        
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante || !$estudiante->plan_activo) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Necesitas un plan activo para acceder al simulador');
        }
        
        $examenes = ExamenGenerado::where('tipo_examen', 'Simulación')
                            ->orWhere('tipo_examen', 'simulacion')
                            ->orWhere('tipo_examen', 'Simulador')
                            ->orWhere('tipo_examen', 'simulador')
                            ->get();
        
        if ($examenes->isEmpty()) {
            return redirect()->route('estudiante.dashboard')->with('error', 'No hay simuladores disponibles');
        }
        
        $examen = $examenId ? $examenes->find($examenId) : $examenes->first();
        
        if (!$examen) {
            return redirect()->route('estudiante.simulador')->with('error', 'Examen no encontrado');
        }
        
        $preguntas = $examen->preguntas()->get();
        
        if ($preguntas->isEmpty()) {
            return redirect()->route('estudiante.simulador')->with('error', 'El simulador no tiene preguntas configuradas');
        }
        
        $intentosRealizados = ExamenRealizado::where('estudiante', $estudiante->id)
                                        ->where('examen', $examen->id)
                                        ->count();
        $intento = $intentosRealizados + 1;
        
        return view('estudiante.simulador', compact('examen', 'preguntas', 'estudiante', 'intento', 'examenes'));
    }
    
    public function cargarSimulador($id)
    {
        return $this->simulador(null, $id);
    }
    
    public function responderSimulador(Request $request)
    {
        try {
            Log::info('========== INICIANDO RESPONDER SIMULADOR ==========');
            
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Primero completa tu perfil'
                ], 400);
            }
            
            if (!$estudiante->plan_activo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Necesitas un plan activo para realizar el simulador'
                ], 403);
            }
            
            $examen_id = $request->examen_id;
            $respuestas = $request->respuestas;
            $tiempoUtilizadoSegundos = intval($request->tiempo_utilizado_segundos ?? 0);
            
            if (!$examen_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se especificó el examen'
                ], 400);
            }
            
            if (!$respuestas || count($respuestas) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron respuestas'
                ], 400);
            }
            
            $examen = ExamenGenerado::find($examen_id);
            if (!$examen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Examen no encontrado'
                ], 404);
            }
            
            $preguntasExamen = $examen->preguntas()->get();
            $totalPreguntas = $preguntasExamen->count();
            $aciertos = 0;
            
            foreach ($preguntasExamen as $pregunta) {
                $respuestaUsuario = $respuestas[$pregunta->id] ?? null;
                if ($respuestaUsuario === 'correcta') {
                    $aciertos++;
                }
            }
            
            $calificacion = $totalPreguntas > 0 ? round(($aciertos / $totalPreguntas) * 100) : 0;
            
            $intento = ExamenRealizado::where('estudiante', $estudiante->id)
                                    ->where('examen', $examen->id)
                                    ->count() + 1;
            
            $horas = floor($tiempoUtilizadoSegundos / 3600);
            $minutos = floor(($tiempoUtilizadoSegundos % 3600) / 60);
            $segundos = $tiempoUtilizadoSegundos % 60;
            $tiempoFormateado = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);
            
            $ahora = Carbon::now();
            $fechaActual = $ahora->toDateString();
            $horaFin = $ahora->toTimeString();
            $horaInicioCalculada = $ahora->copy()->subSeconds($tiempoUtilizadoSegundos);
            $horaInicioStr = $horaInicioCalculada->toTimeString();
            
            $examenRealizado = ExamenRealizado::create([
                'estudiante' => $estudiante->id,
                'examen' => $examen->id,
                'intento' => $intento,
                'calificacion' => $calificacion,
                'fecha_inicio' => $fechaActual,
                'hora_inicio' => $horaInicioStr,
                'fecha_fin' => $fechaActual,
                'hora_fin' => $horaFin,
                'tiempo' => $tiempoFormateado
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Examen completado exitosamente',
                'calificacion' => $calificacion,
                'aciertos' => $aciertos,
                'total' => $totalPreguntas,
                'intento' => $intento,
                'tiempo_utilizado' => $tiempoFormateado,
                'redirect' => route('estudiante.resultados', $examenRealizado->id)
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en responderSimulador: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el examen: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function resultados($id)
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        $examenRealizado = ExamenRealizado::where('id', $id)
                                          ->where('estudiante', $estudiante->id)
                                          ->with('examenGenerado')
                                          ->first();
        
        if (!$examenRealizado) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Resultado no encontrado');
        }
        
        $intentosAnteriores = ExamenRealizado::where('estudiante', $estudiante->id)
                                             ->where('examen', $examenRealizado->examen)
                                             ->where('id', '!=', $id)
                                             ->orderBy('id', 'desc')
                                             ->get();
        
        $mejorCalificacion = ExamenRealizado::where('estudiante', $estudiante->id)
                                            ->where('examen', $examenRealizado->examen)
                                            ->max('calificacion') ?? 0;
        
        return view('estudiante.resultados', compact('examenRealizado', 'estudiante', 'intentosAnteriores', 'mejorCalificacion'));
    }

    // ========== MÉTODOS PARA PERFIL Y PAGOS ==========

    /**
     * Mostrar formulario para completar perfil
     */
    public function completarPerfilForm()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        // Si ya tiene perfil, redirigir al dashboard
        if ($estudiante) {
            return redirect()->route('estudiante.dashboard')
                ->with('info', 'Ya tienes un perfil completado');
        }
        
        return view('estudiante.completar-perfil');
    }

    public function completarPerfil(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'escuela_procedencia' => 'nullable|exists:preparatorias,id',
            'universidad_interes' => 'nullable|exists:universidades,id',
            'telefono_casa' => 'nullable|string|max:20',
            'cupon' => 'nullable|string|max:50',
        ]);

        try {
            $user = Auth::user();
            
            $existePerfil = Estudiante::where('usuario', $user->id)->exists();
            if ($existePerfil) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes un perfil completado'
                ], 400);
            }
            
            $estudiante = Estudiante::create([
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono,
                'telefono_casa' => $request->telefono_casa,
                'escuela_procedencia' => $request->escuela_procedencia,
                'cupon' => $request->cupon,
                'fecha_inscripcion' => now(),
                'plan_activo' => false,
                'universidad_interes' => $request->universidad_interes,
                'foto' => null,
                'usuario' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => '¡Perfil completado exitosamente!',
                'redirect' => route('estudiante.dashboard')
            ]);

        } catch (\Exception $e) {
            Log::error('Error al completar perfil: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al completar perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registrarPago(Request $request)
    {
        $request->validate([
            'tipo_pago' => 'required|string',
            'referencia_pago' => 'required|string',
            'monto_pago' => 'required|numeric|min:1',
            'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'nota_usuario' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Primero completa tu perfil'
                ], 400);
            }
            
            $comprobantePath = $request->file('comprobante')->store('pagos', 'public');
            
            $pago = Pago::create([
                'estudiante_id' => $estudiante->id,
                'tipo_pago' => $request->tipo_pago,
                'referencia_pago' => $request->referencia_pago,
                'monto_pago' => $request->monto_pago,
                'comprobante' => $comprobantePath,
                'nota_usuario' => $request->nota_usuario,
                'estado' => 'pendiente',
                'fecha_solicitud' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente. Será validado por nuestro equipo.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al registrar pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar pago: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clasesPremium()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante || !$estudiante->plan_activo) {
            return redirect()->route('estudiante.progreso')->with('error', 'Debes tener un plan premium para acceder a estas clases');
        }
        
        $asignaturas = Asignatura::whereHas('clases', function($query) {
            $query->whereNotNull('link');
        })->with(['clases' => function($query) {
            $query->whereNotNull('link')
                ->orderBy('num_clase', 'asc');
        }])->get();
        
        $vistasIds = ProgresoVideo::where('estudiante_id', $estudiante->id)
            ->where('completado', true)
            ->pluck('video_id')
            ->toArray();
        
        $examenesMateria = ExamenGenerado::where('tipo_examen', 'Materia')
                                        ->orWhere('tipo_examen', 'materia')
                                        ->get();
        
        $examenesPorArea = [];
        
        foreach($asignaturas as $asignatura) {
            $area = AreaPregunta::where('nombre', $asignatura->nombre)->first();
            
            if ($area) {
                foreach($examenesMateria as $examen) {
                    $tienePreguntas = DB::table('apoyo_preguntas as ap')
                        ->join('preguntas as p', 'ap.pregunta', '=', 'p.id')
                        ->where('ap.examen', $examen->id)
                        ->where('p.id_area', $area->id)
                        ->exists();
                    
                    if ($tienePreguntas) {
                        $examenesPorArea[$asignatura->id] = $examen;
                        break;
                    }
                }
            }
        }
        
        return view('estudiante.clases-premium', compact('estudiante', 'asignaturas', 'vistasIds', 'examenesPorArea'));
    }

    /**
     * Registrar progreso de video
     */
    public function registrarProgresoVideo(Request $request)
    {
        try {
            $user = Auth::user();
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json(['success' => false, 'message' => 'Estudiante no encontrado']);
            }
            
            $claseId = $request->video_id;
            $ultimoSegundo = $request->ultimo_segundo ?? '00:00:00';
            $duracionVideo = intval($request->duracion_video ?? 0);
            $porcentajeVisto = intval($request->porcentaje_visto ?? 0);
            $esPrimerRegistro = $request->es_primer_registro ?? false;
            
            if (!$claseId) {
                return response()->json(['success' => false, 'message' => 'ID de clase no proporcionado']);
            }
            
            // Buscar la clase
            $clase = Clase::find($claseId);
            if (!$clase) {
                return response()->json(['success' => false, 'message' => 'Clase no encontrada']);
            }
            
            // Buscar o crear el video
            $video = Video::firstOrCreate(
                ['link' => $clase->link],
                [
                    'materia' => $clase->asignatura->nombre ?? 'General',
                    'tema' => $clase->nombre_clase,
                    'titulo' => $clase->nombre_clase,
                    'duracion' => $this->formatearSegundos($duracionVideo),
                    'plan' => true
                ]
            );
            
            // Actualizar duración si es necesario
            if ($duracionVideo > 0 && $video->duracion == '00:00:00') {
                $video->duracion = $this->formatearSegundos($duracionVideo);
                $video->save();
            }
            
            // Determinar si está completado (80% o más)
            $completado = ($porcentajeVisto >= 80);
            
            // Buscar progreso existente
            $progreso = ProgresoVideo::where('estudiante_id', $estudiante->id)
                ->where('video_id', $video->id)
                ->first();
            
            if ($progreso) {
                // Si es el primer registro de esta sesión, actualizar veces_visto
                if ($esPrimerRegistro) {
                    $progreso->veces_visto = ($progreso->veces_visto ?? 0) + 1;
                    \Log::info('Incrementando veces_visto a: ' . $progreso->veces_visto);
                }
                
                // Siempre actualizar el último segundo
                $progreso->ultimo_segundo = $ultimoSegundo;
                
                // Marcar como completado si alcanzó 80% y no lo estaba
                if ($completado && !$progreso->completado) {
                    $progreso->completado = true;
                }
                
                $progreso->save();
            } else {
                // PRIMERA VEZ que se ve el video - Crear nuevo registro
                ProgresoVideo::create([
                    'estudiante_id' => $estudiante->id,
                    'video_id' => $video->id,
                    'fecha_visto' => now(),
                    'completado' => $completado,
                    'ultimo_segundo' => $ultimoSegundo,
                    'veces_visto' => 1
                ]);
                \Log::info('Nuevo progreso creado - fecha_visto: ' . now());
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Progreso registrado',
                'completado' => $completado
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en registrarProgresoVideo: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function formatearSegundos($segundos)
    {
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segs = $segundos % 60;
        return sprintf("%02d:%02d:%02d", $horas, $minutos, $segs);
    }

    // ========== MÉTODOS PARA PERFIL Y ACTUALIZACIÓN ==========

    /**
     * Mostrar página de perfil del estudiante
     */
    public function perfil()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.dashboard')->with('warning', 'Por favor completa tu perfil primero');
        }
        
        return view('estudiante.perfil', compact('user', 'estudiante'));
    }

    /**
     * Actualizar perfil del estudiante (incluye correo)
     */
    public function actualizarPerfil(Request $request)
    {
        try {
            $user = Auth::user();
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perfil de estudiante no encontrado'
                ], 404);
            }
            
            $request->validate([
                'nombre' => 'required|string|max:255',
                'paterno' => 'nullable|string|max:255',
                'materno' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'fecha_nacimiento' => 'nullable|date',
                'sexo' => 'nullable|in:M,F',
                'correo' => 'required|email|max:255|unique:usuario,correo,' . $user->id . ',id'
            ]);
            
            // Actualizar datos del estudiante
            $estudiante->update([
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
            ]);
            
            // Actualizar correo del usuario
            if ($request->correo !== $user->correo) {
                $user->correo = $request->correo;
                $user->save();
            }
            
            Log::info('Perfil actualizado para estudiante ID: ' . $estudiante->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado correctamente',
                'nuevo_correo' => $user->correo
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar contraseña del usuario
     */
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password_nueva' => 'required|string|min:6|confirmed'
        ]);

        try {
            $user = Auth::user();
            
            if (!\Hash::check($request->password_actual, $user->contraseña)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 400);
            }
            
            $user->contraseña = \Hash::make($request->password_nueva);
            $user->save();
            
            Log::info('Contraseña cambiada para usuario ID: ' . $user->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Contraseña cambiada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar contraseña: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar la contraseña: ' . $e->getMessage()
            ], 500);
        }
    }



        // ========== MÉTODOS PARA EXÁMENES FINALES DEL CURSO ==========

    /**
     * Mostrar examen final del curso
     */
    public function examenCurso($examenId)
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante || !$estudiante->plan_activo) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Necesitas un plan activo para acceder al examen final');
        }
        
        $examen = ExamenGenerado::where('id', $examenId)
                            ->where('tipo_examen', 'Curso')
                            ->first();
        
        if (!$examen) {
            return redirect()->route('estudiante.clases-premium')->with('error', 'Examen final no encontrado');
        }
        
        $preguntas = $examen->preguntas()->get();
        
        // Si no tiene preguntas asociadas directamente, buscar por apoyo_preguntas
        if ($preguntas->isEmpty()) {
            $preguntas = DB::table('preguntas')
                ->join('apoyo_preguntas', 'preguntas.id', '=', 'apoyo_preguntas.pregunta')
                ->where('apoyo_preguntas.examen', $examen->id)
                ->select('preguntas.*')
                ->get();
        }
        
        if ($preguntas->isEmpty()) {
            return redirect()->route('estudiante.clases-premium')->with('error', 'Este examen no tiene preguntas configuradas');
        }
        
        $intentosRealizados = ExamenRealizado::where('estudiante', $estudiante->id)
                                        ->where('examen', $examen->id)
                                        ->count();
        $intento = $intentosRealizados + 1;
        $mejorCalificacion = ExamenRealizado::where('estudiante', $estudiante->id)
                                        ->where('examen', $examen->id)
                                        ->max('calificacion');
        
        return view('estudiante.examen-curso', compact('examen', 'preguntas', 'estudiante', 'intento', 'mejorCalificacion'));
    }

    /**
     * Procesar respuestas del examen final del curso
     */
    public function responderExamenCurso(Request $request)
    {
        try {
            Log::info('========== INICIANDO RESPONDER EXAMEN CURSO ==========');
            
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Primero completa tu perfil'
                ], 400);
            }
            
            if (!$estudiante->plan_activo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Necesitas un plan activo para realizar el examen final'
                ], 403);
            }
            
            $examen_id = $request->examen_id;
            $respuestas = $request->respuestas;
            $tiempoUtilizadoSegundos = intval($request->tiempo_utilizado_segundos ?? 0);
            
            if (!$examen_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se especificó el examen'
                ], 400);
            }
            
            if (!$respuestas || count($respuestas) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron respuestas'
                ], 400);
            }
            
            $examen = ExamenGenerado::find($examen_id);
            if (!$examen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Examen no encontrado'
                ], 404);
            }
            
            // Obtener preguntas del examen
            $preguntasExamen = $examen->preguntas()->get();
            if ($preguntasExamen->isEmpty()) {
                $preguntasExamen = DB::table('preguntas')
                    ->join('apoyo_preguntas', 'preguntas.id', '=', 'apoyo_preguntas.pregunta')
                    ->where('apoyo_preguntas.examen', $examen->id)
                    ->select('preguntas.*')
                    ->get();
            }
            
            $totalPreguntas = $preguntasExamen->count();
            $aciertos = 0;
            
            foreach ($preguntasExamen as $pregunta) {
                $respuestaUsuario = $respuestas[$pregunta->id] ?? null;
                
                // La respuesta correcta es SIEMPRE el valor 'correcta'
                if ($respuestaUsuario && $respuestaUsuario === 'correcta') {
                    $aciertos++;
                }
            }
                        
            $calificacion = $totalPreguntas > 0 ? round(($aciertos / $totalPreguntas) * 100) : 0;
            
            $intento = ExamenRealizado::where('estudiante', $estudiante->id)
                                    ->where('examen', $examen->id)
                                    ->count() + 1;
            
            $horas = floor($tiempoUtilizadoSegundos / 3600);
            $minutos = floor(($tiempoUtilizadoSegundos % 3600) / 60);
            $segundos = $tiempoUtilizadoSegundos % 60;
            $tiempoFormateado = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);
            
            $ahora = Carbon::now();
            $fechaActual = $ahora->toDateString();
            $horaFin = $ahora->toTimeString();
            $horaInicioCalculada = $ahora->copy()->subSeconds($tiempoUtilizadoSegundos);
            $horaInicioStr = $horaInicioCalculada->toTimeString();
            
            $examenRealizado = ExamenRealizado::create([
                'estudiante' => $estudiante->id,
                'examen' => $examen->id,
                'intento' => $intento,
                'calificacion' => $calificacion,
                'fecha_inicio' => $fechaActual,
                'hora_inicio' => $horaInicioStr,
                'fecha_fin' => $fechaActual,
                'hora_fin' => $horaFin,
                'tiempo' => $tiempoFormateado
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Examen final completado exitosamente',
                'calificacion' => $calificacion,
                'aciertos' => $aciertos,
                'total' => $totalPreguntas,
                'intento' => $intento,
                'tiempo_utilizado' => $tiempoFormateado,
                'redirect' => route('estudiante.resultados', $examenRealizado->id)
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en responderExamenCurso: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el examen final: ' . $e->getMessage()
            ], 500);
        }
    }




    // ========== MÉTODOS PARA CHECKOUT Y CUPONES ==========

    const PRECIO_CURSO = 800; 
    
        /**
     * Procesar la solicitud de pago y generar el registro
     */
    public function procesarSolicitudPago(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:transferencia,oxxo'
        ]);
        
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->back()->with('error', 'Primero completa tu perfil');
        }
        
        if ($estudiante->plan_activo) {
            return redirect()->route('estudiante.dashboard')->with('info', 'Ya tienes un plan activo');
        }
        
        // ========== VERIFICAR SI YA TIENE UN PAGO PENDIENTE ==========
        $pagoExistente = Pago::where('alumno_pago', $estudiante->id)
                            ->where('estatus', 'pendiente')
                            ->first();
        
        if ($pagoExistente) {
            return redirect()->route('estudiante.ficha-pago', $pagoExistente->id)
                ->with('warning', 'Ya tienes un pago pendiente. No puedes generar otro hasta que se valide o rechace el anterior.');
        }
        // ========== FIN DE LA VERIFICACIÓN ==========
        
        $codigoCupon = session('cupon_aplicado');
        $precioOriginal = self::PRECIO_CURSO;
        $montoDescuento = 0;
        $precioFinal = $precioOriginal;
        $detalleCupon = null;
        
        if ($codigoCupon) {
            $cupon = Cupon::where('codigo', $codigoCupon)->first();
            if ($cupon && !$cupon->usado && $cupon->estatus === 'activo') {
                $expirado = $cupon->fecha_expiracion && Carbon::now()->greaterThan($cupon->fecha_expiracion);
                if (!$expirado) {
                    if ($cupon->tipo_descuento === 'porcentaje') {
                        $montoDescuento = ($precioOriginal * $cupon->valor_descuento) / 100;
                        $detalleCupon = sprintf(
                            "Cupón: %s | Descuento del %d%% | Ahorro: $%.2f MXN",
                            $codigoCupon,
                            $cupon->valor_descuento,
                            $montoDescuento
                        );
                    } else {
                        $montoDescuento = min($cupon->valor_descuento, $precioOriginal);
                        $detalleCupon = sprintf(
                            "Cupón: %s | Descuento de $%.2f MXN | Ahorro: $%.2f MXN",
                            $codigoCupon,
                            $cupon->valor_descuento,
                            $montoDescuento
                        );
                    }
                    $precioFinal = max(0, $precioOriginal - $montoDescuento);
                } else {
                    $detalleCupon = "Cupón: $codigoCupon (EXPIRADO) - No aplicó descuento";
                }
            } else {
                $detalleCupon = "Cupón: $codigoCupon (NO VÁLIDO) - No aplicó descuento";
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Generar referencia de pago
            $referencia = $this->generarReferenciaPago($request->metodo_pago, $estudiante->id);
            
            // Construir la nota completa con todos los detalles
            $notaCompleta = "=== SOLICITUD DE PAGO ===\n";
            $notaCompleta .= "Fecha: " . now()->format('d/m/Y H:i:s') . "\n";
            $notaCompleta .= "Monto original: $" . number_format($precioOriginal, 2) . " MXN\n";
            
            if ($detalleCupon) {
                $notaCompleta .= "--- DESCUENTO APLICADO ---\n";
                $notaCompleta .= $detalleCupon . "\n";
                $notaCompleta .= "Monto con descuento: $" . number_format($precioFinal, 2) . " MXN\n";
            } else {
                $notaCompleta .= "Sin cupón aplicado\n";
                $notaCompleta .= "Monto a pagar: $" . number_format($precioFinal, 2) . " MXN\n";
            }
            
            $notaCompleta .= "---\n";
            $notaCompleta .= "Método de pago: " . strtoupper($request->metodo_pago) . "\n";
            $notaCompleta .= "Referencia: $referencia\n";
            $notaCompleta .= "Estado: Pendiente de pago";
            
            // Crear el registro de pago
            $pago = Pago::create([
                'alumno_pago' => $estudiante->id,
                'tipo_pago' => $request->metodo_pago,
                'monto_pago' => round($precioFinal, 2),
                'estatus' => 'pendiente',
                'referencia_pago' => $referencia,
                'fecha_pago' => now(),
                'nota_usuario' => $notaCompleta,
            ]);
            
            // Marcar cupón como usado
            if ($codigoCupon) {
                $cupon = Cupon::where('codigo', $codigoCupon)->first();
                if ($cupon && !$cupon->usado && $cupon->estatus === 'activo') {
                    $expirado = $cupon->fecha_expiracion && Carbon::now()->greaterThan($cupon->fecha_expiracion);
                    if (!$expirado) {
                        $cupon->update([
                            'usado' => true,
                            'usuario_uso' => $estudiante->usuario,
                            'fecha_uso' => now()
                        ]);
                        $estudiante->update(['cupon' => $codigoCupon]);
                    }
                }
            }
            
            DB::commit();
            
            // Limpiar sesión del cupón
            session()->forget('cupon_aplicado');
            
            // Redirigir a la ficha de pago
            return redirect()->route('estudiante.ficha-pago', $pago->id)
                ->with('success', 'Solicitud de pago registrada. Descarga tu ficha para realizar el pago.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar solicitud de pago: ' . $e->getMessage());
            return back()->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Aplicar cupón (por POST normal)
     */
    public function aplicarCupon(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50'
        ]);
        
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        
        // Validar si ya tiene cupón en perfil
        if (!empty($estudiante->cupon)) {
            return redirect()->back()->with('error_cupon', 'Ya tienes un cupón asociado a tu cuenta');
        }
        
        // Validar si ya hay cupón en sesión
        if (session('cupon_aplicado')) {
            return redirect()->back()->with('error_cupon', 'Ya tienes un cupón aplicado');
        }
        
        $codigo = strtoupper($request->codigo);
        $cupon = Cupon::where('codigo', $codigo)->first();
        
        // Validaciones
        if (!$cupon) {
            return redirect()->back()->with('error_cupon', 'El cupón no existe');
        }
        
        if ($cupon->usado) {
            return redirect()->back()->with('error_cupon', 'Este cupón ya ha sido utilizado');
        }
        
        if ($cupon->fecha_expiracion && Carbon::now()->greaterThan($cupon->fecha_expiracion)) {
            return redirect()->back()->with('error_cupon', 'Este cupón ha expirado');
        }
        
        if ($cupon->estatus !== 'activo') {
            return redirect()->back()->with('error_cupon', 'Este cupón no está activo');
        }
        
        // Guardar cupón en sesión
        session(['cupon_aplicado' => $codigo]);
        
        // Calcular mensaje de éxito
        if ($cupon->tipo_descuento === 'porcentaje') {
            $mensaje = "¡Cupón aplicado! " . $cupon->valor_descuento . "% de descuento";
        } else {
            $mensaje = "¡Cupón aplicado! $" . number_format($cupon->valor_descuento, 2) . " de descuento";
        }
        
        return redirect()->back()->with('success_cupon', $mensaje);
    }
    /**
     * Mostrar ficha de pago (imagen estática)
     */
    public function mostrarFichaPago($pagoId)
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        $pago = Pago::where('id', $pagoId)->where('alumno_pago', $estudiante->id)->firstOrFail();
        
        return view('estudiante.ficha-pago', compact('pago'));
    }

    /**
     * Descargar ficha de pago (imagen estática)
     */
    public function descargarFichaPago($pagoId)
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        $pago = Pago::where('id', $pagoId)->where('alumno_pago', $estudiante->id)->firstOrFail();
        
        $rutaImagen = public_path('images/FICHA_PAGO.jpeg');
        
        if (!file_exists($rutaImagen)) {
            return back()->with('error', 'La ficha de pago no está disponible');
        }
        
        return response()->download($rutaImagen, 'ficha_pago_' . $pago->referencia_pago . '.jpeg');
    }

    /**
     * Subir comprobante de pago
     */
    public function subirComprobantePago(Request $request)
    {
        $request->validate([
            'pago_id' => 'required|exists:pagos,id',
            'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'nota_usuario' => 'nullable|string|max:500'
        ]);

        try {
            $estudiante = Estudiante::where('usuario', Auth::id())->first();
            $pago = Pago::where('id', $request->pago_id)->where('alumno_pago', $estudiante->id)->first();
            
            if (!$pago) {
                return redirect()->back()->with('error', 'Pago no encontrado');
            }
            
            $file = $request->file('comprobante');
            $nombreArchivo = 'comprobante_' . $pago->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('comprobantes', $nombreArchivo, 'public');
            
            $pago->update([
                'comprobante' => $ruta,
                'nota_usuario' => $request->nota_usuario
            ]);
            
            return redirect()->route('estudiante.pago-exito', $pago->id)
                ->with('success', '¡Comprobante subido! Tu pago está en revisión.');
            
        } catch (\Exception $e) {
            Log::error('Error al subir comprobante: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al subir el comprobante');
        }
    }

    /**
     * Página de éxito después de subir comprobante
     */
    public function pagoExito($pagoId)
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        $pago = Pago::where('id', $pagoId)->where('alumno_pago', $estudiante->id)->firstOrFail();
        
        return view('estudiante.pago-exito', compact('pago', 'estudiante'));
    }

    /**
     * Mis pagos (historial) - Ahora solo un pago
     */
    public function misPagos()
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Primero completa tu perfil');
        }
        
        // Obtener el pago más reciente (solo debe haber uno activo)
        $pago = Pago::where('alumno_pago', $estudiante->id)
                    ->orderBy('id', 'desc')
                    ->first();
        
        return view('estudiante.mis-pagos', compact('pago', 'estudiante'));
    }

    /**
     * Generar referencia única de pago
     */
    private function generarReferenciaPago($metodo, $estudianteId)
    {
        $prefix = match($metodo) {
            'transferencia' => 'TRA',
            'oxxo' => 'OXX',
            default => 'PAG'
        };
        return $prefix . date('Ymd') . str_pad($estudianteId, 6, '0', STR_PAD_LEFT) . rand(100, 999);
    }


    /**
     * Mostrar página de checkout (compra del curso)
     */
    public function checkout()
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.dashboard')->with('warning', 'Primero completa tu perfil');
        }
        
        if ($estudiante->plan_activo) {
            return redirect()->route('estudiante.dashboard')->with('info', 'Ya cuentas con un plan activo');
        }
        
        // ========== VERIFICAR SI TIENE UN PAGO PENDIENTE ==========
        $pagoPendiente = Pago::where('alumno_pago', $estudiante->id)
                            ->where('estatus', 'pendiente')
                            ->first();
        
        if ($pagoPendiente) {
            // Redirigir a la ficha del pago pendiente
            return redirect()->route('estudiante.ficha-pago', $pagoPendiente->id)
                ->with('warning', 'Ya tienes un pago pendiente. Completa tu pago o espera la validación.');
        }
        // ========== FIN DE LA VERIFICACIÓN ==========
        
        $precioOriginal = self::PRECIO_CURSO;
        $precioFinal = self::PRECIO_CURSO;
        $montoDescuento = 0;
        $porcentajeDescuento = null;
        $cuponAplicado = session('cupon_aplicado');
        
        // Si el estudiante tiene cupón en su perfil, usarlo automáticamente
        if (empty($cuponAplicado) && !empty($estudiante->cupon)) {
            $cupon = Cupon::where('codigo', $estudiante->cupon)->first();
            if ($cupon && !$cupon->usado && $cupon->estatus === 'activo') {
                $expirado = $cupon->fecha_expiracion && Carbon::now()->greaterThan($cupon->fecha_expiracion);
                if (!$expirado) {
                    session(['cupon_aplicado' => $estudiante->cupon]);
                    $cuponAplicado = $estudiante->cupon;
                }
            }
        }
        
        // Calcular descuento si hay cupón
        if ($cuponAplicado) {
            $cupon = Cupon::where('codigo', $cuponAplicado)->first();
            if ($cupon && !$cupon->usado && $cupon->estatus === 'activo') {
                $expirado = $cupon->fecha_expiracion && Carbon::now()->greaterThan($cupon->fecha_expiracion);
                
                if (!$expirado) {
                    if ($cupon->tipo_descuento === 'porcentaje') {
                        $montoDescuento = ($precioOriginal * $cupon->valor_descuento) / 100;
                        $porcentajeDescuento = $cupon->valor_descuento;
                    } else {
                        $montoDescuento = $cupon->valor_descuento;
                    }
                    $precioFinal = max(0, $precioOriginal - $montoDescuento);
                } else {
                    session()->forget('cupon_aplicado');
                    $cuponAplicado = null;
                }
            } else {
                session()->forget('cupon_aplicado');
                $cuponAplicado = null;
            }
        }
        
        $precios = [
            'precio_original' => $precioOriginal,
            'precio_final' => round($precioFinal, 2),
            'monto_descuento' => round($montoDescuento, 2),
            'tiene_descuento' => $montoDescuento > 0,
            'cupon_aplicado' => $cuponAplicado,
            'porcentaje_descuento' => $porcentajeDescuento
        ];
        
        return view('estudiante.checkout', compact('estudiante', 'precios', 'pagoPendiente'));
    }
}