<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Preparatoria;
use App\Models\Universidad;
use App\Models\Cupon;
use App\Models\TiempoEstudio;
use App\Models\ExamenRealizado;
use App\Models\ProgresoVideo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; 


class EstudianteController extends Controller
{

    public function create()
    {
        // Obtener estados únicos de preparatorias
        $estadosPrepa = Preparatoria::select('estado')
            ->distinct()
            ->orderBy('estado')
            ->pluck('estado');
        
        // Obtener estados únicos de universidades
        $estadosUniversidad = Universidad::select('estado')
            ->distinct()
            ->orderBy('estado')
            ->pluck('estado');
        
        // Para el select de escuelas (se cargarán por AJAX)
        $preparatorias = Preparatoria::orderBy('centro_educativo')->get();
        $universidades = Universidad::orderBy('clave')->get();
        $cuponesDisponibles = Cupon::where('estatus', 'activo')
            ->where('usado', false)
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'tipo_descuento', 'valor_descuento']);
    
        return view('administrador.estudiantes.create', compact(
            'estadosPrepa', 
            'estadosUniversidad', 
            'preparatorias', 
            'universidades',
            'cuponesDisponibles'  
        ));
    }
        
    /**
     * Guarda un nuevo estudiante en el sistema
     */
    public function store(Request $request)
    {
        $request->validate([
            // Datos del usuario
            'email' => 'required|email|unique:usuario,correo',
            'password' => 'required|min:6|confirmed',
            
            // Datos del estudiante
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+$/',
            'paterno' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+$/',
            'materno' => 'nullable|string|max:100|regex:/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+$/',
            'fecha_nacimiento' => 'required|date|before:today|after:1920-01-01',
            'sexo' => 'required|in:M,F',
            'telefono' => 'required|string|max:15|regex:/^[0-9+\-\s]+$/',
            'telefono_casa' => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'escuela_procedencia' => 'required|exists:preparatorias,id',
            'universidad_interes' => 'required|exists:universidades,id',
            'plan_activo' => 'boolean',
            'cupon_id' => 'nullable|exists:cupones,id',
        ], [
            'email.unique' => 'Este correo electrónico ya está registrado',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingresa un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre solo puede contener letras',
            'paterno.required' => 'El apellido paterno es obligatorio',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'sexo.required' => 'Debes seleccionar el sexo',
            'telefono.required' => 'El teléfono es obligatorio',
            'escuela_procedencia.required' => 'Debes seleccionar la escuela de procedencia',
            'universidad_interes.required' => 'Debes seleccionar la universidad de interés',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Validar cupón si se seleccionó
            $cupon = null;
            $codigoCupon = null;
            
            if ($request->cupon_id) {
                $cupon = Cupon::where('id', $request->cupon_id)
                    ->where('estatus', 'activo')
                    ->where('usado', false)
                    ->first();
                
                if (!$cupon) {
                    throw new \Exception('El cupón seleccionado no es válido o ya fue utilizado');
                }
                
                $codigoCupon = $cupon->codigo;
            }
            
            // 1. Crear el usuario
            $user = User::create([
                'correo' => $request->email,
                'contraseña' => Hash::make($request->password),
                'rol' => 'estudiante'
            ]);
            
            // 2. Crear el estudiante
            $estudiante = Estudiante::create([
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono,
                'telefono_casa' => $request->telefono_casa,
                'escuela_procedencia' => $request->escuela_procedencia,
                'universidad_interes' => $request->universidad_interes,
                'plan_activo' => $request->has('plan_activo'),
                'cupon' => $codigoCupon,
                'fecha_inscripcion' => now(),
                'usuario' => $user->id
            ]);
            
            // 3. Marcar el cupón como usado
            if ($cupon) {
                $cupon->update([
                    'usado' => true,
                    'usuario_uso' => $user->id,
                    'fecha_uso' => now()
                ]);
            }
            
            DB::commit();
            
            $mensaje = 'Estudiante registrado exitosamente. Correo: ' . $user->correo;
            if ($cupon) {
                $mensaje .= ' - Cupón aplicado: ' . $cupon->codigo;
            }
            
            return redirect()->route('admin.estudiantes.index')
                ->with('success', $mensaje);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar estudiante: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar el estudiante: ' . $e->getMessage());
        }
    }
    
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sexo = $request->get('sexo');
        $plan_activo = $request->get('plan_activo');
        $estado_prepa = $request->get('estado_prepa');
        $estado_universidad = $request->get('estado_universidad');
        
        // CONSULTAR DESDE USER como en administradores
        $estudiantes = User::where('rol', 'estudiante')
            ->with(['estudiante.escuelaProcedencia', 'estudiante.universidadInteres'])
            ->when($search, function($query, $search) {
                return $query->where('correo', 'LIKE', "%{$search}%")
                    ->orWhereHas('estudiante', function($q) use ($search) {
                        $q->where('nombre', 'LIKE', "%{$search}%")
                          ->orWhere('paterno', 'LIKE', "%{$search}%")
                          ->orWhere('materno', 'LIKE', "%{$search}%")
                          ->orWhere('telefono', 'LIKE', "%{$search}%")
                          ->orWhere('cupon', 'LIKE', "%{$search}%");
                    });
            })
            ->when($sexo, function($query, $sexo) {
                return $query->whereHas('estudiante', function($q) use ($sexo) {
                    $q->where('sexo', $sexo);
                });
            })
            ->when($plan_activo !== null && $plan_activo !== '', function($query) use ($plan_activo) {
                return $query->whereHas('estudiante', function($q) use ($plan_activo) {
                    $q->where('plan_activo', $plan_activo);
                });
            })
            ->when($estado_prepa, function($query, $estado_prepa) {
                return $query->whereHas('estudiante.escuelaProcedencia', function($q) use ($estado_prepa) {
                    $q->where('estado', $estado_prepa);
                });
            })
            ->when($estado_universidad, function($query, $estado_universidad) {
                return $query->whereHas('estudiante.universidadInteres', function($q) use ($estado_universidad) {
                    $q->where('estado', $estado_universidad);
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
        
        // Estadísticas para las tarjetas
        $totalEstudiantes = User::where('rol', 'estudiante')->count();
        
        $activos = User::where('rol', 'estudiante')
            ->whereHas('estudiante', function($q) {
                $q->where('plan_activo', true);
            })->count();
        
        $inactivos = User::where('rol', 'estudiante')
            ->whereHas('estudiante', function($q) {
                $q->where('plan_activo', false);
            })->count();
        
        $conCupon = User::where('rol', 'estudiante')
            ->whereHas('estudiante', function($q) {
                $q->whereNotNull('cupon')
                  ->where('cupon', '!=', '')
                  ->where('cupon', '!=', 'null');
            })->count();
        
        // Obtener lista única de estados para los filtros
        $estadosPrepa = Preparatoria::select('estado')->distinct()->orderBy('estado')->pluck('estado');
        $estadosUniversidad = Universidad::select('estado')->distinct()->orderBy('estado')->pluck('estado');
        
        return view('administrador.estudiantes.index', compact(
            'estudiantes', 
            'totalEstudiantes', 
            'activos', 
            'inactivos', 
            'conCupon',
            'estadosPrepa',
            'estadosUniversidad'
        ));
    }
    
    public function show($id)
    {
        // Buscar desde User con relación estudiante
        $user = User::with(['estudiante.escuelaProcedencia', 'estudiante.universidadInteres', 'estudiante.pagos'])
            ->findOrFail($id);
        
        if (!$user->estudiante) {
            return redirect()->route('admin.estudiantes.index')
                ->with('error', 'Estudiante no encontrado');
        }
        
        $estudiante = $user->estudiante;
        $usuario = $user;
        
        // ========== TIEMPO DE ESTUDIO ==========
        $estadisticasTiempo = TiempoEstudio::getEstadisticasCompletas($estudiante->id);
        
        $tiempoTotalHoras = $estadisticasTiempo['total_horas'] ?? 0;
        $tiempoTotalMinutos = $estadisticasTiempo['total_minutos'] ?? 0;
        $totalSesiones = $estadisticasTiempo['total_sesiones'] ?? 0;
        $diasActivos = $estadisticasTiempo['dias_estudiados'] ?? 0;
        
        // ========== ÚLTIMA ACTIVIDAD (MEJORADO - MÚLTIPLES FUENTES) ==========
        $ultimaActividad = '—';
        $fechaUltimaActividad = null;
        
        // 1. Revisar tiempo de estudio
        $ultimoTiempo = TiempoEstudio::where('estudiante_id', $estudiante->id)
            ->whereNotNull('ultima_actividad')
            ->orderBy('ultima_actividad', 'desc')
            ->first();
        
        if ($ultimoTiempo && $ultimoTiempo->ultima_actividad) {
            $fechaUltimaActividad = $ultimoTiempo->ultima_actividad;
            $ultimaActividad = $ultimoTiempo->ultima_actividad->diffForHumans();
        }
        
        // 2. Si no hay, revisar exámenes realizados
        if ($ultimaActividad === '—') {
            $ultimoExamen = ExamenRealizado::where('estudiante', $estudiante->id)
                ->whereNotNull('fecha_fin')
                ->orderBy('fecha_fin', 'desc')
                ->first();
            
            if ($ultimoExamen && $ultimoExamen->fecha_fin) {
                $fechaUltimaActividad = Carbon::parse($ultimoExamen->fecha_fin);
                $ultimaActividad = $fechaUltimaActividad->diffForHumans();
            }
        }
        
        // 3. Si no hay, revisar progreso de videos
        if ($ultimaActividad === '—') {
            $ultimoVideo = ProgresoVideo::where('estudiante_id', $estudiante->id)
                ->whereNotNull('fecha_visto')
                ->orderBy('fecha_visto', 'desc')
                ->first();
            
            if ($ultimoVideo && $ultimoVideo->fecha_visto) {
                $fechaUltimaActividad = Carbon::parse($ultimoVideo->fecha_visto);
                $ultimaActividad = $fechaUltimaActividad->diffForHumans();
            }
        }
        
        // 4. Si no hay actividad, usar fecha de inscripción
        if ($ultimaActividad === '—' && $estudiante->fecha_inscripcion) {
            $fechaUltimaActividad = Carbon::parse($estudiante->fecha_inscripcion);
            $ultimaActividad = 'Desde ' . $fechaUltimaActividad->format('d/m/Y');
        }
        
        // Estudio diario últimos 7 días - CON DÍAS EN ESPAÑOL
        $estudioDiario = TiempoEstudio::where('estudiante_id', $estudiante->id)
            ->where('fecha', '>=', Carbon::now()->subDays(7))
            ->orderBy('fecha', 'asc')
            ->get()
            ->map(function($item) {
                $minutos = (int)($item->minutos_estudiados ?? 0);
                $horas = $minutos > 0 ? round($minutos / 60, 1) : 0;
                
                // Convertir fecha a día de semana en ESPAÑOL
                $fecha = Carbon::parse($item->fecha);
                $diaEspanol = '';
                
                // Usar Carbon con locale 'es' para obtener el día en español
                try {
                    Carbon::setLocale('es');
                    $diaEspanol = ucfirst($fecha->isoFormat('dddd'));
                } catch (\Exception $e) {
                    // Fallback manual si falla Carbon
                    $diasMap = [
                        'Monday' => 'Lunes',
                        'Tuesday' => 'Martes', 
                        'Wednesday' => 'Miércoles',
                        'Thursday' => 'Jueves',
                        'Friday' => 'Viernes',
                        'Saturday' => 'Sábado',
                        'Sunday' => 'Domingo',
                        'Mon' => 'Lunes',
                        'Tue' => 'Martes',
                        'Wed' => 'Miércoles',
                        'Thu' => 'Jueves',
                        'Fri' => 'Viernes',
                        'Sat' => 'Sábado',
                        'Sun' => 'Domingo'
                    ];
                    $diaIngles = $fecha->format('l');
                    $diaEspanol = $diasMap[$diaIngles] ?? $fecha->format('D');
                }
                
                return (object)[
                    'dia' => $diaEspanol,
                    'horas_estudiadas' => $horas,
                    'minutos_estudiados' => $minutos,
                    'segundos' => (int)($item->segundos_estudiados ?? 0)
                ];
            });
        
        // Si no hay datos, generar últimos 7 días con ceros
        if ($estudioDiario->isEmpty()) {
            $estudioDiario = collect();
            for ($i = 6; $i >= 0; $i--) {
                $fecha = Carbon::now()->subDays($i);
                Carbon::setLocale('es');
                $diaEspanol = ucfirst($fecha->isoFormat('dddd'));
                
                $estudioDiario->push((object)[
                    'dia' => $diaEspanol,
                    'horas_estudiadas' => 0,
                    'minutos_estudiados' => 0,
                    'segundos' => 0
                ]);
            }
        }
        
        // ========== EXÁMENES CON TIPOS DESDE ExamenGenerado ==========
        $examenes = ExamenRealizado::where('estudiante', $estudiante->id)
            ->with('examenGenerado')
            ->orderBy('fecha_fin', 'desc')
            ->orderBy('hora_fin', 'desc')
            ->get()
            ->map(function($examenRealizado) {
                $examenGen = $examenRealizado->examenGenerado;
                $tipoExamen = $examenGen->tipo_examen ?? 'general';
                $nombreReferencia = '';
                
                switch (strtolower($tipoExamen)) {
                    case 'materia':
                        $nombreReferencia = $examenGen->materia->nombre ?? 'Materia';
                        $badgeColor = 'primary';
                        $badgeIcon = 'fa-book';
                        $tipoTexto = 'Por Materia';
                        break;
                        
                    case 'curso':
                        $nombreReferencia = $examenGen->curso->nombre ?? 'Curso';
                        $badgeColor = 'success';
                        $badgeIcon = 'fa-graduation-cap';
                        $tipoTexto = 'Por Curso';
                        break;
                        
                    case 'simulacion':
                    case 'simulación':
                        $nombreReferencia = $examenGen->titulo ?? 'Simulación';
                        $badgeColor = 'danger';
                        $badgeIcon = 'fa-flask';
                        $tipoTexto = 'Simulación';
                        break;
                        
                    default:
                        $nombreReferencia = $examenGen->titulo ?? 'Examen';
                        $badgeColor = 'secondary';
                        $badgeIcon = 'fa-puzzle-piece';
                        $tipoTexto = ucfirst($tipoExamen);
                        break;
                }
                
                $examenRealizado->tipo_examen = $tipoExamen;
                $examenRealizado->nombre_referencia = $nombreReferencia;
                $examenRealizado->badge_color = $badgeColor;
                $examenRealizado->badge_icon = $badgeIcon;
                $examenRealizado->tipo_texto = $tipoTexto;
                $examenRealizado->examen_nombre = $examenGen->titulo ?? 'Examen';
                
                return $examenRealizado;
            });
        
        // Estadísticas por tipo de examen
        $examenesPorTipo = [
            'simulacion' => $examenes->filter(function($e) {
                return in_array(strtolower($e->tipo_examen), ['simulacion', 'simulación']);
            })->count(),
            'materia' => $examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'materia';
            })->count(),
            'curso' => $examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'curso';
            })->count(),
        ];
        
        $promedioCalificaciones = $examenes->isNotEmpty() ? round($examenes->avg('calificacion')) : 0;
        
        // Promedio por tipo de examen
        $promedioPorTipo = [
            'simulacion' => $examenes->filter(function($e) {
                return in_array(strtolower($e->tipo_examen), ['simulacion', 'simulación']);
            })->isNotEmpty() ? round($examenes->filter(function($e) {
                return in_array(strtolower($e->tipo_examen), ['simulacion', 'simulación']);
            })->avg('calificacion')) : 0,
            
            'materia' => $examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'materia';
            })->isNotEmpty() ? round($examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'materia';
            })->avg('calificacion')) : 0,
            
            'curso' => $examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'curso';
            })->isNotEmpty() ? round($examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'curso';
            })->avg('calificacion')) : 0,
        ];
        
        // ========== PROGRESO DE VIDEOS ==========
        $totalVideos = Video::count();
        $progresosVideos = ProgresoVideo::where('estudiante_id', $estudiante->id)->get();
        $vistosCompletos = $progresosVideos->where('completado', true)->count();
        $videosEnProgreso = $progresosVideos->where('completado', false)->count();
        $porcentajeProgreso = $totalVideos > 0 ? round(($vistosCompletos / $totalVideos) * 100) : 0;
        
        // Últimos videos vistos
        $ultimosVideos = ProgresoVideo::where('estudiante_id', $estudiante->id)
            ->with('video')
            ->whereNotNull('fecha_visto')
            ->orderBy('fecha_visto', 'desc')
            ->limit(5)
            ->get();
        
        // Videos que NO ha visto
        $videosIdsVistos = $progresosVideos->pluck('video_id')->toArray();
        $videosFaltantes = Video::whereNotIn('id', $videosIdsVistos)
            ->orderBy('materia')
            ->orderBy('tema')
            ->get();
        
        $duracionEstimada = 3600;
        
        return view('administrador.estudiantes.show', compact(
            'estudiante', 
            'usuario',
            'tiempoTotalHoras',
            'tiempoTotalMinutos',
            'estudioDiario',
            'totalSesiones',
            'ultimaActividad',
            'fechaUltimaActividad',
            'diasActivos',
            'examenes',
            'examenesPorTipo',
            'promedioPorTipo',
            'promedioCalificaciones',
            'totalVideos',
            'vistosCompletos',
            'videosEnProgreso',
            'porcentajeProgreso',
            'ultimosVideos',
            'videosFaltantes',
            'duracionEstimada'
        ));
    }
    /**
     * Obtiene los datos de un estudiante en formato JSON para el modal
     */
    public function getEstudianteJson($id)
    {
        // Buscar desde User con relación estudiante
        $user = User::with(['estudiante.escuelaProcedencia', 'estudiante.universidadInteres'])
            ->findOrFail($id);
        
        if (!$user->estudiante) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }
        
        $estudiante = $user->estudiante;
        
        // Preparar los datos para JSON
        $data = [
            'success' => true,
            'data' => [
                'id' => $estudiante->id,
                'nombre' => $estudiante->nombre,
                'paterno' => $estudiante->paterno,
                'materno' => $estudiante->materno,
                'fecha_nacimiento' => $estudiante->fecha_nacimiento,
                'sexo' => $estudiante->sexo,
                'telefono' => $estudiante->telefono,
                'telefono_casa' => $estudiante->telefono_casa,
                'plan_activo' => $estudiante->plan_activo,
                'cupon' => $estudiante->cupon,
                'fecha_inscripcion' => $estudiante->fecha_inscripcion,
                'user' => [
                    'correo' => $user->correo,
                    'rol' => $user->rol
                ],
                'escuela_procedencia' => $estudiante->escuelaProcedencia ? [
                    'id' => $estudiante->escuelaProcedencia->id,
                    'estado' => $estudiante->escuelaProcedencia->estado,
                    'municipio' => $estudiante->escuelaProcedencia->municipio,
                    'localidad' => $estudiante->escuelaProcedencia->localidad,
                    'centro_educativo' => $estudiante->escuelaProcedencia->centro_educativo,
                    'clave' => $estudiante->escuelaProcedencia->clave,
                    'tipo' => $estudiante->escuelaProcedencia->tipo,
                    'servicio' => $estudiante->escuelaProcedencia->servicio,
                    'turno' => $estudiante->escuelaProcedencia->turno,
                    'ambito' => $estudiante->escuelaProcedencia->ambito,
                    'direccion' => $estudiante->escuelaProcedencia->direccion,
                ] : null,
                'universidad_interes' => $estudiante->universidadInteres ? [
                    'id' => $estudiante->universidadInteres->id,
                    'clave' => $estudiante->universidadInteres->clave,
                    'direccion' => $estudiante->universidadInteres->direccion,
                    'tipo' => $estudiante->universidadInteres->tipo,
                    'duracion' => $estudiante->universidadInteres->duracion,
                    'estado' => $estudiante->universidadInteres->estado,
                    'municipio' => $estudiante->universidadInteres->municipio,
                    'localidad' => $estudiante->universidadInteres->localidad,
                ] : null,
            ]
        ];
        
        return response()->json($data);
    }
    
    /**
     * Muestra el formulario para editar un estudiante
     */
    public function edit($id)
    {
        // Buscar el usuario con todas las relaciones necesarias
        $user = User::with(['estudiante.escuelaProcedencia', 'estudiante.universidadInteres'])->findOrFail($id);
        
        // Verificar que el usuario sea un estudiante
        if (!$user->estudiante) {
            return redirect()->route('admin.estudiantes.index')
                ->with('error', 'Este usuario no es un estudiante válido');
        }
        
        $estudiante = $user->estudiante;
        
        // Crear un objeto stdClass con los datos de forma segura
        $estudianteData = new \stdClass();
        
        // Datos del usuario
        $estudianteData->id = $user->id;
        $estudianteData->correo = $user->correo;
        $estudianteData->rol = $user->rol;
        
        // Datos del estudiante (asegurando que sean strings o valores escalares)
        $estudianteData->nombre = $estudiante->nombre ?? '';
        $estudianteData->paterno = $estudiante->paterno ?? '';
        $estudianteData->materno = $estudiante->materno ?? '';
        $estudianteData->fecha_nacimiento = $estudiante->fecha_nacimiento ?? '';
        $estudianteData->sexo = $estudiante->sexo ?? '';
        $estudianteData->telefono = $estudiante->telefono ?? '';
        $estudianteData->telefono_casa = $estudiante->telefono_casa ?? '';
        $estudianteData->escuela_procedencia = $estudiante->escuela_procedencia ?? '';
        $estudianteData->universidad_interes = $estudiante->universidad_interes ?? '';
        $estudianteData->plan_activo = $estudiante->plan_activo ?? false;
        $estudianteData->cupon = $estudiante->cupon ?? '';
        
        // Datos de la escuela procedencia (como strings)
        if ($estudiante->escuelaProcedencia) {
            $estudianteData->escuela_procedencia_nombre = $estudiante->escuelaProcedencia->centro_educativo ?? '';
            $estudianteData->escuela_procedencia_estado = $estudiante->escuelaProcedencia->estado ?? '';
            $estudianteData->escuela_procedencia_municipio = $estudiante->escuelaProcedencia->municipio ?? '';
            $estudianteData->escuela_procedencia_localidad = $estudiante->escuelaProcedencia->localidad ?? '';
        } else {
            $estudianteData->escuela_procedencia_nombre = '';
            $estudianteData->escuela_procedencia_estado = '';
            $estudianteData->escuela_procedencia_municipio = '';
            $estudianteData->escuela_procedencia_localidad = '';
        }
        
        // Datos de la universidad de interés (como strings)
        if ($estudiante->universidadInteres) {
            $estudianteData->universidad_interes_nombre = $estudiante->universidadInteres->clave . ' - ' . ($estudiante->universidadInteres->direccion ?? '');
            $estudianteData->universidad_interes_estado = $estudiante->universidadInteres->estado ?? '';
            $estudianteData->universidad_interes_municipio = $estudiante->universidadInteres->municipio ?? '';
            $estudianteData->universidad_interes_localidad = $estudiante->universidadInteres->localidad ?? '';
        } else {
            $estudianteData->universidad_interes_nombre = '';
            $estudianteData->universidad_interes_estado = '';
            $estudianteData->universidad_interes_municipio = '';
            $estudianteData->universidad_interes_localidad = '';
        }
        
        // Obtener cupones disponibles (excluyendo el actual si ya tiene uno)
        $cuponesDisponibles = Cupon::where('estatus', 'activo')
            ->where('usado', false)
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'tipo_descuento', 'valor_descuento']);
        
        // Estados para filtros anidados
        $estadosPrepa = Preparatoria::select('estado')->distinct()->orderBy('estado')->pluck('estado');
        $estadosUniversidad = Universidad::select('estado')->distinct()->orderBy('estado')->pluck('estado');
        
        $preparatorias = Preparatoria::orderBy('centro_educativo')->get();
        $universidades = Universidad::orderBy('clave')->get();
        
        return view('administrador.estudiantes.edit', compact(
            'estudianteData', 
            'user', 
            'estadosPrepa', 
            'estadosUniversidad', 
            'preparatorias', 
            'universidades',
            'cuponesDisponibles'
        ));
    }
    
    /**
     * Actualiza los datos de un estudiante
     */
    public function update(Request $request, $id)
    {
        // Buscar el usuario con su estudiante
        $user = User::with('estudiante')->findOrFail($id);
        $estudiante = $user->estudiante;
        
        if (!$estudiante) {
            return redirect()->route('admin.estudiantes.index')
                ->with('error', 'Estudiante no encontrado');
        }
        
        $request->validate([
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+$/',
            'paterno' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+$/',
            'materno' => 'nullable|string|max:100|regex:/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+$/',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F',
            'telefono' => 'required|string|max:15|regex:/^[0-9+\-\s]+$/',
            'telefono_casa' => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'escuela_procedencia' => 'required|exists:preparatorias,id',
            'universidad_interes' => 'required|exists:universidades,id',
            'plan_activo' => 'boolean',
            'cupon_id' => 'nullable|exists:cupones,id',
            'email' => 'required|email|unique:usuario,correo,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Validar nuevo cupón si se seleccionó y es diferente al actual
            $nuevoCupon = null;
            $codigoCupon = $estudiante->cupon;
            
            if ($request->cupon_id) {
                $nuevoCupon = Cupon::where('id', $request->cupon_id)
                    ->where('estatus', 'activo')
                    ->where('usado', false)
                    ->first();
                
                if (!$nuevoCupon) {
                    throw new \Exception('El cupón seleccionado no es válido o ya fue utilizado');
                }
                
                $codigoCupon = $nuevoCupon->codigo;
                
                // Marcar el nuevo cupón como usado
                $nuevoCupon->update([
                    'usado' => true,
                    'usuario_uso' => $user->id,
                    'fecha_uso' => now()
                ]);
            } elseif ($request->cupon_id === '' && $estudiante->cupon) {
                // Si se eliminó el cupón, no hacer nada (el cupón ya está usado)
                $codigoCupon = null;
            }
            
            // Actualizar usuario
            $userData = ['correo' => $request->email];
            if ($request->filled('password')) {
                $userData['contraseña'] = Hash::make($request->password);
            }
            $user->update($userData);
            
            // Actualizar estudiante
            $estudiante->update([
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono,
                'telefono_casa' => $request->telefono_casa,
                'escuela_procedencia' => $request->escuela_procedencia,
                'universidad_interes' => $request->universidad_interes,
                'plan_activo' => $request->has('plan_activo'),
                'cupon' => $codigoCupon,
            ]);
            
            DB::commit();
            
            $mensaje = 'Estudiante actualizado exitosamente';
            if ($nuevoCupon) {
                $mensaje .= ' - Cupón aplicado: ' . $nuevoCupon->codigo;
            }
            
            return redirect()->route('admin.estudiantes.index')
                ->with('success', $mensaje);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar estudiante: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el estudiante: ' . $e->getMessage());
        }
    }
    
    /**
     * Elimina un estudiante
     */
    public function destroy($id)
    {
        try {
            $user = User::with('estudiante')->findOrFail($id);
            
            if (!$user->estudiante) {
                return redirect()->route('admin.estudiantes.index')
                    ->with('error', 'Estudiante no encontrado');
            }
            
            $nombre = $user->estudiante->nombre . ' ' . $user->estudiante->paterno;
            
            DB::beginTransaction();
            
            // Eliminar el estudiante
            $user->estudiante->delete();
            
            // Eliminar el usuario
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('admin.estudiantes.index')
                ->with('success', "Estudiante {$nombre} eliminado exitosamente");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar estudiante: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Ocurrió un error al eliminar el estudiante: ' . $e->getMessage());
        }
    }
    
    /**
     * Restablece la contraseña de un estudiante
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        try {
            $user = User::findOrFail($id);
            
            $user->update([
                'contraseña' => Hash::make($request->new_password)
            ]);
            
            return redirect()->route('admin.estudiantes.show', $id)
                ->with('success', 'Contraseña restablecida exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al restablecer contraseña: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Ocurrió un error al restablecer la contraseña');
        }
    }

    // ========== MÉTODOS PARA FILTROS ANIDADOS (AJAX) ==========

    /**
     * Obtiene municipios por estado (Preparatorias)
     */
    public function getMunicipiosPrepa(Request $request)
    {
        $municipios = Preparatoria::where('estado', $request->estado)
            ->select('municipio')
            ->distinct()
            ->orderBy('municipio')
            ->pluck('municipio');
        
        return response()->json($municipios);
    }

    /**
     * Obtiene localidades por estado y municipio (Preparatorias)
     */
    public function getLocalidadesPrepa(Request $request)
    {
        $localidades = Preparatoria::where('estado', $request->estado)
            ->where('municipio', $request->municipio)
            ->select('localidad')
            ->distinct()
            ->orderBy('localidad')
            ->pluck('localidad');
        
        return response()->json($localidades);
    }

    /**
     * Obtiene preparatorias por estado, municipio y localidad
     */
    public function getPreparatorias(Request $request)
    {
        $query = Preparatoria::where('estado', $request->estado)
            ->where('municipio', $request->municipio);
        
        if ($request->has('localidad') && $request->localidad && $request->localidad !== 'todas') {
            $query->where('localidad', $request->localidad);
        }
        
        $preparatorias = $query->orderBy('centro_educativo')
            ->get(['id', 'centro_educativo', 'clave', 'turno']);
        
        return response()->json($preparatorias);
    }

    // ========== MÉTODOS PARA UNIVERSIDADES ==========

    /**
     * Obtiene municipios por estado (Universidades)
     */
    public function getMunicipiosUniversidad(Request $request)
    {
        $municipios = Universidad::where('estado', $request->estado)
            ->select('municipio')
            ->distinct()
            ->orderBy('municipio')
            ->pluck('municipio');
        
        return response()->json($municipios);
    }

    /**
     * Obtiene localidades por estado y municipio (Universidades)
     */
    public function getLocalidadesUniversidad(Request $request)
    {
        $localidades = Universidad::where('estado', $request->estado)
            ->where('municipio', $request->municipio)
            ->select('localidad')
            ->distinct()
            ->orderBy('localidad')
            ->pluck('localidad');
        
        return response()->json($localidades);
    }

    /**
     * Obtiene universidades por estado, municipio y localidad
     */
    public function getUniversidades(Request $request)
    {
        $query = Universidad::where('estado', $request->estado)
            ->where('municipio', $request->municipio);
        
        if ($request->has('localidad') && $request->localidad && $request->localidad !== 'todas') {
            $query->where('localidad', $request->localidad);
        }
        
        $universidades = $query->orderBy('clave')
            ->get(['id', 'clave', 'direccion', 'tipo', 'duracion']);
        
        // Formatear el nombre de la universidad
        $universidades = $universidades->map(function($uni) {
            $uni->nombre_completo = $uni->clave . ' - ' . $uni->direccion;
            if ($uni->tipo) {
                $uni->nombre_completo .= ' (' . $uni->tipo . ')';
            }
            return $uni;
        });
        
        return response()->json($universidades);
    }

    // ========== MÉTODOS PARA CUPONES ==========

    /**
     * Obtiene los cupones disponibles para mostrar en el select
     */
    public function getCuponesDisponibles()
    {
        $cupones = Cupon::where('estatus', 'activo')
            ->where('usado', false)
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'tipo_descuento', 'valor_descuento']);
        
        return response()->json($cupones);
    }

    public function validarCupon(Request $request)
    {
        $request->validate([
            'cupon_id' => 'required|exists:cupones,id'
        ]);
        
        $cupon = Cupon::find($request->cupon_id);
        
        if (!$cupon || $cupon->estatus !== 'activo' || $cupon->usado) {
            return response()->json([
                'valido' => false,
                'mensaje' => 'El cupón no es válido o ya ha sido utilizado'
            ]);
        }
        
        $descuentoTexto = $cupon->tipo_descuento == 'porcentaje' 
            ? "{$cupon->valor_descuento}% de descuento"
            : "$" . number_format($cupon->valor_descuento, 2) . " de descuento";
        
        return response()->json([
            'valido' => true,
            'mensaje' => "¡Cupón válido! {$descuentoTexto}"
        ]);
    }

    /**
     * Genera un reporte PDF con todos los detalles del estudiante
     */
    public function generarReportePDF($id)
    {
        // Buscar desde User con relación estudiante
        $user = User::with(['estudiante.escuelaProcedencia', 'estudiante.universidadInteres', 'estudiante.pagos'])
            ->findOrFail($id);
        
        if (!$user->estudiante) {
            return redirect()->route('admin.estudiantes.index')
                ->with('error', 'Estudiante no encontrado');
        }
        
        $estudiante = $user->estudiante;
        $usuario = $user;
        
        // ========== TIEMPO DE ESTUDIO ==========
        $estadisticasTiempo = TiempoEstudio::getEstadisticasCompletas($estudiante->id);
        
        $tiempoTotalHoras = $estadisticasTiempo['total_horas'] ?? 0;
        $tiempoTotalMinutos = $estadisticasTiempo['total_minutos'] ?? 0;
        $totalSesiones = $estadisticasTiempo['total_sesiones'] ?? 0;
        $diasActivos = $estadisticasTiempo['dias_estudiados'] ?? 0;
        
        // Última actividad
        $ultimoRegistro = TiempoEstudio::where('estudiante_id', $estudiante->id)
            ->whereNotNull('ultima_actividad')
            ->orderBy('ultima_actividad', 'desc')
            ->first();
        $ultimaActividad = $ultimoRegistro ? $ultimoRegistro->ultima_actividad->format('d/m/Y H:i') : '—';
        
        // Estudio diario últimos 7 días
        $estudioDiario = TiempoEstudio::where('estudiante_id', $estudiante->id)
            ->where('fecha', '>=', Carbon::now()->subDays(7))
            ->orderBy('fecha', 'asc')
            ->get()
            ->map(function($item) {
                $minutos = (int)($item->minutos_estudiados ?? 0);
                $horas = $minutos > 0 ? round($minutos / 60, 1) : 0;
                
                return (object)[
                    'dia' => Carbon::parse($item->fecha)->format('D'),
                    'fecha' => Carbon::parse($item->fecha)->format('d/m/Y'),
                    'horas_estudiadas' => $horas,
                    'minutos_estudiados' => $minutos,
                    'segundos' => (int)($item->segundos_estudiados ?? 0)
                ];
            });
        
        if ($estudioDiario->isEmpty()) {
            $estudioDiario = collect();
            for ($i = 6; $i >= 0; $i--) {
                $fecha = Carbon::now()->subDays($i);
                $estudioDiario->push((object)[
                    'dia' => $fecha->format('D'),
                    'fecha' => $fecha->format('d/m/Y'),
                    'horas_estudiadas' => 0,
                    'minutos_estudiados' => 0,
                    'segundos' => 0
                ]);
            }
        }
        
        // ========== EXÁMENES ==========
        $examenes = ExamenRealizado::where('estudiante', $estudiante->id)
            ->with('examenGenerado')
            ->orderBy('fecha_fin', 'desc')
            ->get()
            ->map(function($examenRealizado) {
                $examenGen = $examenRealizado->examenGenerado;
                $tipoExamen = $examenGen->tipo_examen ?? 'general';
                $nombreReferencia = '';
                
                switch (strtolower($tipoExamen)) {
                    case 'materia':
                        $nombreReferencia = $examenGen->materia->nombre ?? 'Materia';
                        $tipoTexto = 'Por Materia';
                        break;
                    case 'curso':
                        $nombreReferencia = $examenGen->curso->nombre ?? 'Curso';
                        $tipoTexto = 'Por Curso';
                        break;
                    case 'simulacion':
                    case 'simulación':
                        $nombreReferencia = $examenGen->titulo ?? 'Simulación';
                        $tipoTexto = 'Simulación';
                        break;
                    default:
                        $nombreReferencia = $examenGen->titulo ?? 'Examen';
                        $tipoTexto = ucfirst($tipoExamen);
                        break;
                }
                
                $examenRealizado->tipo_examen = $tipoExamen;
                $examenRealizado->nombre_referencia = $nombreReferencia;
                $examenRealizado->tipo_texto = $tipoTexto;
                $examenRealizado->examen_nombre = $examenGen->titulo ?? 'Examen';
                $examenRealizado->fecha_completa = $examenRealizado->fecha_fin 
                    ? Carbon::parse($examenRealizado->fecha_fin)->format('d/m/Y') 
                    : '—';
                
                return $examenRealizado;
            });
        
        // Estadísticas por tipo
        $examenesPorTipo = [
            'simulacion' => $examenes->filter(function($e) {
                return in_array(strtolower($e->tipo_examen), ['simulacion', 'simulación']);
            })->count(),
            'materia' => $examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'materia';
            })->count(),
            'curso' => $examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'curso';
            })->count(),
        ];
        
        $promedioCalificaciones = $examenes->isNotEmpty() ? round($examenes->avg('calificacion'), 1) : 0;
        
        $promedioPorTipo = [
            'simulacion' => $examenes->filter(function($e) {
                return in_array(strtolower($e->tipo_examen), ['simulacion', 'simulación']);
            })->isNotEmpty() ? round($examenes->filter(function($e) {
                return in_array(strtolower($e->tipo_examen), ['simulacion', 'simulación']);
            })->avg('calificacion'), 1) : 0,
            'materia' => $examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'materia';
            })->isNotEmpty() ? round($examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'materia';
            })->avg('calificacion'), 1) : 0,
            'curso' => $examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'curso';
            })->isNotEmpty() ? round($examenes->filter(function($e) {
                return strtolower($e->tipo_examen) == 'curso';
            })->avg('calificacion'), 1) : 0,
        ];
        
        // ========== PROGRESO DE VIDEOS ==========
        $totalVideos = Video::count();
        $progresosVideos = ProgresoVideo::where('estudiante_id', $estudiante->id)->get();
        $vistosCompletos = $progresosVideos->where('completado', true)->count();
        $videosEnProgreso = $progresosVideos->where('completado', false)->count();
        $porcentajeProgreso = $totalVideos > 0 ? round(($vistosCompletos / $totalVideos) * 100) : 0;
        
        $ultimosVideos = ProgresoVideo::where('estudiante_id', $estudiante->id)
            ->with('video')
            ->whereNotNull('fecha_visto')
            ->orderBy('fecha_visto', 'desc')
            ->limit(5)
            ->get();
        
        // Datos para el PDF
        $data = compact(
            'estudiante',
            'usuario',
            'tiempoTotalHoras',
            'tiempoTotalMinutos',
            'estudioDiario',
            'totalSesiones',
            'ultimaActividad',
            'diasActivos',
            'examenes',
            'examenesPorTipo',
            'promedioPorTipo',
            'promedioCalificaciones',
            'totalVideos',
            'vistosCompletos',
            'videosEnProgreso',
            'porcentajeProgreso',
            'ultimosVideos'
        );
        
        // Cargar vista para PDF
        $pdf = \PDF::loadView('administrador.estudiantes.reporte-pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Descargar PDF
        $nombreArchivo = 'reporte_estudiante_' . $estudiante->id . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($nombreArchivo);
    }
}