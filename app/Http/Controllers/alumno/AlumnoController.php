<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Universidad;
use App\Models\Carrera;
use App\Models\User;
use App\Models\Pago;
use App\Models\Cupon;
use App\Models\Pregunta;
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
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class AlumnoController extends Controller
{
    // ✅ CONSTANTES
    const MAX_INTENTOS_BASICO = 3;
    const MIN_PREGUNTAS_BASICO = 3;
    const MAX_PREGUNTAS_BASICO = 5;
    const PRECIO_CURSO = 800;

    // ========== PANEL PRINCIPAL ==========
    
    public function dashboard()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        $perfilCompleto = $estudiante ? true : false;
        $tieneFoto = false;
        
        if (!$estudiante) {
            return redirect()->route('estudiante.completar-perfil')
                ->with('warning', 'Por favor completa tu perfil para continuar');
        }
        
        if ($estudiante && $estudiante->foto) {
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

    // ========== PERFIL Y FOTO ==========
    
    public function perfil()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.dashboard')->with('warning', 'Por favor completa tu perfil primero');
        }
        
        $universidades = Universidad::with('carrera')->get();
        
        return view('estudiante.perfil', compact('user', 'estudiante', 'universidades'));
    }

    public function completarPerfilForm()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
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
            
            $estudiante->update([
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
            ]);
            
            if ($request->correo !== $user->correo) {
                $user->correo = $request->correo;
                $user->save();
            }
            
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

    // ========== FOTO DE PERFIL ==========
    
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

    // ========== CLASES PREMIUM ==========
    
    public function clasesPremium()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        $asignaturas = Asignatura::whereHas('clases', function($query) {
            $query->whereNotNull('link');
        })->with(['clases' => function($query) {
            $query->whereNotNull('link')->orderBy('num_clase', 'asc');
        }])->get();
        
        $vistasIds = [];
        if ($estudiante) {
            $vistasIds = ProgresoVideo::where('estudiante_id', $estudiante->id)
                ->where('completado', true)
                ->pluck('video_id')
                ->toArray();
        }
        
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
        
        $tieneAccesoPremium = $estudiante && $estudiante->plan_activo;
        
        return view('estudiante.clases-premium', compact('estudiante', 'asignaturas', 'vistasIds', 'examenesPorArea', 'tieneAccesoPremium'));
    }

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
            
            $clase = Clase::find($claseId);
            if (!$clase) {
                return response()->json(['success' => false, 'message' => 'Clase no encontrada']);
            }
            
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
            
            if ($duracionVideo > 0 && $video->duracion == '00:00:00') {
                $video->duracion = $this->formatearSegundos($duracionVideo);
                $video->save();
            }
            
            $completado = ($porcentajeVisto >= 80);
            
            $progreso = ProgresoVideo::where('estudiante_id', $estudiante->id)
                ->where('video_id', $video->id)
                ->first();
            
            if ($progreso) {
                if ($esPrimerRegistro) {
                    $progreso->veces_visto = ($progreso->veces_visto ?? 0) + 1;
                }
                
                $progreso->ultimo_segundo = $ultimoSegundo;
                
                if ($completado && !$progreso->completado) {
                    $progreso->completado = true;
                }
                
                $progreso->save();
            } else {
                ProgresoVideo::create([
                    'estudiante_id' => $estudiante->id,
                    'video_id' => $video->id,
                    'fecha_visto' => now(),
                    'completado' => $completado,
                    'ultimo_segundo' => $ultimoSegundo,
                    'veces_visto' => 1
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Progreso registrado',
                'completado' => $completado
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en registrarProgresoVideo: ' . $e->getMessage());
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

    // ========== CHECKOUT Y PAGOS ==========
    
    public function checkout()
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.completar-perfil')->with('warning', 'Primero completa tu perfil');
        }
        
        if ($estudiante->plan_activo) {
            return redirect()->route('estudiante.clases-premium')->with('info', 'Ya tienes acceso al curso premium');
        }
        
        $pagoPendiente = Pago::where('alumno_pago', $estudiante->id)
            ->whereIn('estatus', ['pendiente', 'procesando', 'revisando'])
            ->first();
        
        if ($pagoPendiente) {
            return redirect()->route('estudiante.checkout-pendiente', $pagoPendiente->id)
                ->with('warning', 'Ya tienes un pago pendiente');
        }
        
        $precioOriginal = self::PRECIO_CURSO;
        $precioFinal = self::PRECIO_CURSO;
        $montoDescuento = 0;
        $porcentajeDescuento = null;
        $cuponAplicado = session('cupon_aplicado');
        
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
        
        return view('estudiante.checkout', compact('estudiante', 'precios'));
    }

    public function checkoutPendiente($pagoId = null)
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.completar-perfil')->with('warning', 'Primero completa tu perfil');
        }
        
        if ($estudiante->plan_activo) {
            return redirect()->route('estudiante.clases-premium')->with('info', 'Ya tienes acceso premium');
        }
        
        if ($pagoId) {
            $pagoPendiente = Pago::where('id', $pagoId)
                ->where('alumno_pago', $estudiante->id)
                ->whereNotIn('estatus', ['cancelado', 'completado', 'rechazado'])
                ->first();
        } else {
            $pagoPendiente = Pago::where('alumno_pago', $estudiante->id)
                ->whereNotIn('estatus', ['cancelado', 'completado', 'rechazado'])
                ->first();
        }
        
        if (!$pagoPendiente) {
            return redirect()->route('estudiante.checkout')->with('info', 'No tienes pagos pendientes');
        }
        
        return view('estudiante.checkout-pendiente', compact('estudiante', 'pagoPendiente'));
    }

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
        
        $pagoExistente = Pago::where('alumno_pago', $estudiante->id)
            ->whereNotIn('estatus', ['cancelado', 'completado', 'rechazado'])
            ->first();
        
        if ($pagoExistente) {
            return redirect()->route('estudiante.checkout-pendiente', $pagoExistente->id)
                ->with('warning', 'Ya tienes una solicitud de pago pendiente');
        }
        
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
                        $detalleCupon = "Cupón: $codigoCupon | Descuento del {$cupon->valor_descuento}% | Ahorro: $" . number_format($montoDescuento, 2) . " MXN";
                    } else {
                        $montoDescuento = min($cupon->valor_descuento, $precioOriginal);
                        $detalleCupon = "Cupón: $codigoCupon | Descuento de $" . number_format($cupon->valor_descuento, 2) . " MXN | Ahorro: $" . number_format($montoDescuento, 2) . " MXN";
                    }
                    $precioFinal = max(0, $precioOriginal - $montoDescuento);
                }
            }
        }
        
        try {
            DB::beginTransaction();
            
            $referencia = $this->generarReferenciaPago($request->metodo_pago, $estudiante->id);
            
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
            
            $pago = Pago::create([
                'alumno_pago' => $estudiante->id,
                'tipo_pago' => $request->metodo_pago,
                'monto_pago' => round($precioFinal, 2),
                'estatus' => 'pendiente',
                'referencia_pago' => $referencia,
                'fecha_pago' => now(),
                'nota_usuario' => $notaCompleta,
            ]);
            
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
            session()->forget('cupon_aplicado');
            
            return redirect()->route('estudiante.ficha-pago', $pago->id)
                ->with('success', 'Solicitud de pago registrada. Descarga tu ficha para realizar el pago.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar solicitud de pago: ' . $e->getMessage());
            return back()->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function aplicarCupon(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50'
        ]);
        
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        
        if (!empty($estudiante->cupon)) {
            return redirect()->back()->with('error_cupon', 'Ya tienes un cupón asociado a tu cuenta');
        }
        
        if (session('cupon_aplicado')) {
            return redirect()->back()->with('error_cupon', 'Ya tienes un cupón aplicado');
        }
        
        $codigo = strtoupper($request->codigo);
        $cupon = Cupon::where('codigo', $codigo)->first();
        
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
        
        session(['cupon_aplicado' => $codigo]);
        
        if ($cupon->tipo_descuento === 'porcentaje') {
            $mensaje = "¡Cupón aplicado! " . $cupon->valor_descuento . "% de descuento";
        } else {
            $mensaje = "¡Cupón aplicado! $" . number_format($cupon->valor_descuento, 2) . " de descuento";
        }
        
        return redirect()->back()->with('success_cupon', $mensaje);
    }

    public function eliminarCupon()
    {
        session()->forget('cupon_aplicado');
        return redirect()->route('estudiante.checkout')->with('success', 'Cupón eliminado correctamente');
    }

    public function cancelarPago($pagoId)
    {
        try {
            $estudiante = Estudiante::where('usuario', Auth::id())->first();
            
            if (!$estudiante) {
                return redirect()->route('estudiante.dashboard')->with('error', 'Estudiante no encontrado');
            }
            
            $pago = Pago::where('id', $pagoId)
                ->where('alumno_pago', $estudiante->id)
                ->where('estatus', 'pendiente')
                ->first();
            
            if (!$pago) {
                return redirect()->route('estudiante.checkout')->with('error', 'No se encontró un pago pendiente para cancelar');
            }
            
            $pago->estatus = 'cancelado';
            $pago->save();
            
            session()->forget('cupon_aplicado');
            
            return redirect()->route('estudiante.checkout')->with('success', 'Pago cancelado correctamente. Ya puedes generar uno nuevo.');
            
        } catch (\Exception $e) {
            Log::error('Error al cancelar pago: ' . $e->getMessage());
            return redirect()->route('estudiante.checkout')->with('error', 'Error al cancelar el pago: ' . $e->getMessage());
        }
    }

    public function mostrarFichaPago($pagoId)
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        $pago = Pago::where('id', $pagoId)->where('alumno_pago', $estudiante->id)->firstOrFail();
        
        return view('estudiante.ficha-pago', compact('pago'));
    }

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
                'nota_usuario' => $request->nota_usuario,
                'estatus' => 'revisando'
            ]);
            
            return redirect()->route('estudiante.pago-exito', $pago->id)
                ->with('success', '¡Comprobante subido! Tu pago está en revisión.');
            
        } catch (\Exception $e) {
            Log::error('Error al subir comprobante: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al subir el comprobante');
        }
    }

    public function pagoExito($pagoId)
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        $pago = Pago::where('id', $pagoId)->where('alumno_pago', $estudiante->id)->firstOrFail();
        
        return view('estudiante.pago-exito', compact('pago', 'estudiante'));
    }

    public function misPagos()
    {
        $estudiante = Estudiante::where('usuario', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Primero completa tu perfil');
        }
        
        // Obtener el último pago del estudiante (o null si no existe)
        $pago = Pago::where('alumno_pago', $estudiante->id)
            ->orderBy('id', 'desc')
            ->first();  // Cambiado de get() a first()
        
        // Enviar la variable $pago en lugar de $pagos
        return view('estudiante.mis-pagos', compact('pago'));
    }

    private function generarReferenciaPago($metodo, $estudianteId)
    {
        $prefix = match($metodo) {
            'transferencia' => 'TRA',
            'oxxo' => 'OXX',
            default => 'PAG'
        };
        return $prefix . date('Ymd') . str_pad($estudianteId, 6, '0', STR_PAD_LEFT) . rand(100, 999);
    }

    // ==================== MERCADO PAGO (INTEGRACIÓN COMPLETA) ====================

    /**
     * Crear preferencia de pago con Mercado Pago
     */
    public function crearPreferenciaMercadoPago(Request $request)
    {
        try {
            Log::info('=== INICIANDO CREACIÓN DE PREFERENCIA MP ===');
            
            $estudiante = Estudiante::where('usuario', Auth::id())->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estudiante no encontrado'
                ], 404);
            }
            
            // Calcular precio final
            $precioOriginal = self::PRECIO_CURSO;
            $precioFinal = $precioOriginal;
            $cuponAplicado = session('cupon_aplicado');
            
            if ($cuponAplicado) {
                $cupon = Cupon::where('codigo', $cuponAplicado)->first();
                if ($cupon && !$cupon->usado && $cupon->estatus === 'activo') {
                    $expirado = $cupon->fecha_expiracion && Carbon::now()->greaterThan($cupon->fecha_expiracion);
                    if (!$expirado) {
                        if ($cupon->tipo_descuento === 'porcentaje') {
                            $precioFinal = $precioOriginal - (($precioOriginal * $cupon->valor_descuento) / 100);
                        } else {
                            $precioFinal = max(0, $precioOriginal - $cupon->valor_descuento);
                        }
                    }
                }
            }
            
            $accessToken = config('mercadopago.access_token');
            
            if (!$accessToken || $accessToken === '') {
                Log::error('Access Token no configurado');
                return response()->json([
                    'success' => false,
                    'message' => 'Token de Mercado Pago no configurado'
                ], 500);
            }
            
            // Obtener la URL base completa
            $baseUrl = url('/');
            
            // Construir URLs absolutas
            $successUrl = $baseUrl . route('estudiante.pago.mercadopago.success', [], false);
            $failureUrl = $baseUrl . route('estudiante.pago.mercadopago.failure', [], false);
            $pendingUrl = $baseUrl . route('estudiante.pago.mercadopago.pending', [], false);
            
            Log::info('URLs de retorno:', [
                'success' => $successUrl,
                'failure' => $failureUrl,
                'pending' => $pendingUrl
            ]);
            
            // Crear la preferencia
            $client = new \GuzzleHttp\Client();
            
            $data = [
                'items' => [
                    [
                        'id' => 'curso_sains_2026',
                        'title' => 'Curso Premium SAINS 2026',
                        'description' => 'Acceso completo al curso premium de preparación para examen de admisión',
                        'quantity' => 1,
                        'currency_id' => 'MXN',
                        'unit_price' => round($precioFinal, 2)
                    ]
                ],
                'payer' => [
                    'email' => Auth::user()->correo,
                    'name' => $estudiante->nombre ?? 'Estudiante',
                    'surname' => $estudiante->paterno ?? 'SAINS',
                ],
                'back_urls' => [
                    'success' => $successUrl,
                    'failure' => $failureUrl,
                    'pending' => $pendingUrl
                ],
                'auto_return' => 'approved',
                'notification_url' => $baseUrl . route('estudiante.pago.mercadopago.webhook', [], false),
                'payment_methods' => [
                    'excluded_payment_types' => [
                        ['id' => 'ticket'],
                        ['id' => 'atm']
                    ],
                    'installments' => 12
                ],
                'external_reference' => 'curso_' . $estudiante->id . '_' . time()
            ];
            
            Log::info('Enviando a MP:', $data);
            
            $response = $client->post('https://api.mercadopago.com/checkout/preferences', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            
            $result = json_decode($response->getBody(), true);
            
            Log::info('Respuesta MP:', $result);
            
            if (isset($result['id'])) {
                // Guardar en sesión
                session(['pago_en_proceso' => [
                    'estudiante_id' => $estudiante->id,
                    'monto' => round($precioFinal, 2),
                    'cupon' => $cuponAplicado,
                    'preference_id' => $result['id'],
                    'fecha_inicio' => now()->toDateTimeString()
                ]]);
                
                return response()->json([
                    'success' => true,
                    'preference_id' => $result['id'],
                    'init_point' => $result['init_point']
                ]);
            }
            
            $errorMsg = $result['message'] ?? 'Error desconocido';
            Log::error('Error MP:', $result);
            
            return response()->json([
                'success' => false,
                'message' => $errorMsg
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Error en crearPreferenciaMercadoPago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Pago exitoso - Mercado Pago
     */
    public function pagoExitoMercadoPago(Request $request)
    {
        try {
            $payment_id = $request->get('payment_id');
            $preference_id = $request->get('preference_id');
            $collection_status = $request->get('collection_status');
            
            Log::info('Pago exitoso MP recibido:', [
                'payment_id' => $payment_id,
                'preference_id' => $preference_id,
                'collection_status' => $collection_status
            ]);
            
            // Obtener información de la sesión
            $pagoInfo = session('pago_en_proceso');
            
            if (!$pagoInfo) {
                return redirect()->route('estudiante.checkout')
                    ->with('error', 'No se encontró información del pago. Por favor contacta a soporte.');
            }
            
            $estudiante = Estudiante::find($pagoInfo['estudiante_id']);
            
            if (!$estudiante) {
                return redirect()->route('estudiante.checkout')
                    ->with('error', 'Estudiante no encontrado.');
            }
            
            // Verificar el estado del pago con la API de MP
            $accessToken = config('mercadopago.access_token');
            $response = Http::withToken($accessToken)
                ->get("https://api.mercadopago.com/v1/payments/{$payment_id}");
            
            if ($response->successful()) {
                $paymentData = $response->json();
                
                if ($paymentData['status'] === 'approved') {
                    DB::beginTransaction();
                    
                    try {
                        // Verificar que no exista un pago ya procesado
                        $pagoExistente = Pago::where('referencia_pago', $payment_id)->first();
                        if ($pagoExistente) {
                            DB::rollBack();
                            return redirect()->route('estudiante.pago-exito', $pagoExistente->id)
                                ->with('info', 'Este pago ya había sido procesado anteriormente.');
                        }
                        
                        // Crear registro de pago
                        $pago = Pago::create([
                            'alumno_pago' => $estudiante->id,
                            'tipo_pago' => 'mercadopago',
                            'monto_pago' => $pagoInfo['monto'],
                            'estatus' => 'completado',
                            'referencia_pago' => $payment_id,
                            'fecha_pago' => now(),
                            'nota_usuario' => "Pago realizado con Mercado Pago\nID Transacción: $payment_id\nPreference ID: $preference_id"
                        ]);
                        
                        // Activar plan del estudiante
                        $estudiante->plan_activo = true;
                        $estudiante->fecha_activacion = now();
                        $estudiante->save();
                        
                        // Marcar cupón como usado si existe
                        if ($pagoInfo['cupon']) {
                            $cupon = Cupon::where('codigo', $pagoInfo['cupon'])->first();
                            if ($cupon && !$cupon->usado) {
                                $cupon->usado = true;
                                $cupon->usuario_uso = $estudiante->usuario;
                                $cupon->fecha_uso = now();
                                $cupon->save();
                                
                                // Actualizar el campo cupon en el estudiante
                                $estudiante->cupon = $pagoInfo['cupon'];
                                $estudiante->save();
                            }
                        }
                        
                        // Limpiar sesión
                        session()->forget(['pago_en_proceso', 'cupon_aplicado']);
                        
                        DB::commit();
                        
                        return redirect()->route('estudiante.pago-exito', $pago->id)
                            ->with('success', '¡Pago completado exitosamente! Bienvenido al curso premium.');
                        
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error al guardar pago exitoso: ' . $e->getMessage());
                        throw $e;
                    }
                } else {
                    Log::warning('Pago no aprobado', ['status' => $paymentData['status']]);
                    return redirect()->route('estudiante.checkout')
                        ->with('error', 'El pago no fue aprobado. Estado: ' . $paymentData['status']);
                }
            }
            
            return redirect()->route('estudiante.checkout')
                ->with('error', 'No se pudo verificar el estado del pago. Por favor contacta a soporte.');
                
        } catch (\Exception $e) {
            Log::error('Error en pagoExitoMercadoPago: ' . $e->getMessage());
            return redirect()->route('estudiante.checkout')
                ->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Pago fallido - Mercado Pago
     */
    public function pagoFallidoMercadoPago(Request $request)
    {
        Log::info('Pago fallido MP:', $request->all());
        
        $errorMessage = $request->get('message', 'El pago no pudo ser procesado.');
        
        return redirect()->route('estudiante.checkout')
            ->with('error', 'Pago fallido: ' . $errorMessage . ' Por favor intenta nuevamente con otra tarjeta.');
    }

    /**
     * Pago pendiente - Mercado Pago
     */
    public function pagoPendienteMercadoPago(Request $request)
    {
        Log::info('Pago pendiente MP:', $request->all());
        
        return redirect()->route('estudiante.checkout')
            ->with('warning', 'Tu pago está siendo procesado. Recibirás un correo de confirmación cuando sea aprobado.');
    }

    /**
     * Webhook - Notificaciones de Mercado Pago
     */
    public function webhookMercadoPago(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('Webhook MP recibido:', $data);
            
            // Procesar notificación de pago
            if (isset($data['type']) && $data['type'] == 'payment') {
                $payment_id = $data['data']['id'];
                
                // Consultar información del pago
                $accessToken = config('mercadopago.access_token');
                $response = Http::withToken($accessToken)
                    ->get("https://api.mercadopago.com/v1/payments/{$payment_id}");
                
                if ($response->successful()) {
                    $payment = $response->json();
                    Log::info('Webhook - Detalle del pago:', ['payment' => $payment]);
                    
                    // Aquí puedes actualizar el estado del pago en tu BD si es necesario
                    // Por ejemplo, si el pago está aprobado y por alguna razón no se procesó antes
                    if ($payment['status'] === 'approved') {
                        // Buscar si ya existe el pago
                        $pagoExistente = Pago::where('referencia_pago', $payment_id)->first();
                        
                        if (!$pagoExistente) {
                            // Procesar el pago pendiente
                            Log::info('Webhook: Procesando pago pendiente ID: ' . $payment_id);
                            // Aquí puedes llamar a tu lógica de procesamiento de pago
                        }
                    }
                }
            }
            
            return response()->json(['status' => 'ok'], 200);
            
        } catch (\Exception $e) {
            Log::error('Error en webhook MP: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    // ========== ESTADÍSTICAS Y PROGRESO ==========
    
    public function getProgresoApi()
    {
        try {
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

    public function getEstadisticas()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
            }
            
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
            Log::error('Error en getEstadisticas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    public function getUltimosExamenes()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'examenes' => []]);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json(['success' => true, 'examenes' => []]);
            }
            
            $examenes = ExamenRealizado::where('estudiante', $estudiante->id)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
            
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
            
            return response()->json([
                'success' => true,
                'examenes' => $examenesFormateados
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en getUltimosExamenes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'examenes' => []
            ], 500);
        }
    }

    public function getHistorialExamenes()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'examenes' => []]);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json(['success' => true, 'examenes' => []]);
            }
            
            $examenes = ExamenRealizado::where('estudiante', $estudiante->id)
                ->orderBy('id', 'desc')
                ->get();
            
            $examenesFormateados = $examenes->map(function($examen) {
                $examenGenerado = ExamenGenerado::find($examen->examen);
                $tipoExamen = $examenGenerado ? $examenGenerado->tipo_examen : 'Simulador';
                
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
                    'calificacion' => round($examen->calificacion ?? 0, 2),
                    'intento' => $examen->intento ?? 1,
                    'tipo_examen' => $tipoExamen,
                    'aprobado' => ($examen->calificacion ?? 0) >= 70
                ];
            });
            
            return response()->json([
                'success' => true,
                'examenes' => $examenesFormateados
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en getHistorialExamenes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'examenes' => []
            ], 500);
        }
    }

    // ========== TIEMPO DE ESTUDIO ==========
    
    public function heartbeat(Request $request)
    {
        try {
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
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json([
                    'success' => true,
                    'hoy' => ['segundos' => 0, 'minutos' => 0, 'horas' => 0],
                    'total' => ['segundos' => 0, 'minutos' => 0, 'horas' => 0]
                ]);
            }
            
            $hoy = TiempoEstudio::getTiempoHoy($estudiante->id);
            $totalSegundos = TiempoEstudio::where('estudiante_id', $estudiante->id)->sum('segundos_estudiados');
            
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
            Log::error('Error en getTiempoEstudio: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tiempo de estudio'
            ], 500);
        }
    }

    // ========== EXÁMENES ==========
    
    public function simulador(Request $request = null, $examenId = null)
    {
        if (is_numeric($request) && $examenId === null) {
            $examenId = $request;
            $request = null;
        }
        
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.completar-perfil')->with('warning', 'Primero completa tu perfil');
        }
        
        if (!$estudiante->plan_activo) {
            return $this->simuladorBasico($estudiante, $examenId);
        }
        
        return $this->simuladorPremium($estudiante, $examenId);
    }

    private function simuladorPremium($estudiante, $examenId = null)
    {
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
        $intentosRestantes = 'Ilimitados';
        $maxPreguntas = null;
        
        return view('estudiante.simulador', compact('examen', 'preguntas', 'estudiante', 'intento', 'examenes', 'intentosRestantes', 'maxPreguntas'));
    }

    private function simuladorBasico($estudiante, $examenId = null)
    {
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
        
        $intentosRealizados = ExamenRealizado::where('estudiante', $estudiante->id)
            ->where('examen', $examen->id)
            ->count();
        
        $intentosRestantes = max(0, self::MAX_INTENTOS_BASICO - $intentosRealizados);
        
        if ($intentosRealizados >= self::MAX_INTENTOS_BASICO) {
            return redirect()->route('estudiante.simulador')
                ->with('error_limit', "Has alcanzado el límite de " . self::MAX_INTENTOS_BASICO . " intentos. ¡Actualiza a Premium!");
        }
        
        $todasPreguntas = $examen->preguntas()->get();
        
        if ($todasPreguntas->isEmpty()) {
            return redirect()->route('estudiante.simulador')->with('error', 'El simulador no tiene preguntas configuradas');
        }
        
        $numPreguntas = rand(self::MIN_PREGUNTAS_BASICO, min(self::MAX_PREGUNTAS_BASICO, $todasPreguntas->count()));
        $preguntas = $todasPreguntas->random($numPreguntas);
        
        $intento = $intentosRealizados + 1;
        $maxPreguntas = self::MAX_PREGUNTAS_BASICO;
        
        $mensajeBasico = "Modo Básico: " . self::MAX_PREGUNTAS_BASICO . " preguntas | Intentos restantes: " . $intentosRestantes . "/" . self::MAX_INTENTOS_BASICO;
        
        session()->flash('modo_basico', $mensajeBasico);
        
        return view('estudiante.simulador', compact('examen', 'preguntas', 'estudiante', 'intento', 'examenes', 'intentosRestantes', 'maxPreguntas'));
    }

    public function cargarSimulador($id)
    {
        return $this->simulador(null, $id);
    }
    
    public function responderSimulador(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
            }
            
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json(['success' => false, 'message' => 'Primero completa tu perfil'], 400);
            }
            
            $examen_id = $request->examen_id;
            $respuestas = $request->respuestas;
            $tiempoUtilizadoSegundos = intval($request->tiempo_utilizado_segundos ?? 0);
            
            if (!$examen_id || !$respuestas || count($respuestas) == 0) {
                return response()->json(['success' => false, 'message' => 'Datos incompletos'], 400);
            }
            
            $examen = ExamenGenerado::find($examen_id);
            if (!$examen) {
                return response()->json(['success' => false, 'message' => 'Examen no encontrado'], 404);
            }
            
            $intentosRealizados = ExamenRealizado::where('estudiante', $estudiante->id)
                ->where('examen', $examen->id)
                ->count();
            
            if (!$estudiante->plan_activo) {
                if ($intentosRealizados >= self::MAX_INTENTOS_BASICO) {
                    return response()->json(['success' => false, 'message' => "Límite de " . self::MAX_INTENTOS_BASICO . " intentos alcanzado"], 403);
                }
                
                $preguntasRespondidas = count($respuestas);
                if ($preguntasRespondidas > self::MAX_PREGUNTAS_BASICO) {
                    return response()->json(['success' => false, 'message' => "Máximo " . self::MAX_PREGUNTAS_BASICO . " preguntas"], 403);
                }
            }
            
            if ($estudiante->plan_activo) {
                $preguntasExamen = $examen->preguntas()->get();
            } else {
                $preguntasIds = array_keys($respuestas);
                $preguntasExamen = $examen->preguntas()->whereIn('preguntas.id', $preguntasIds)->get();
            }
            
            $totalPreguntas = $preguntasExamen->count();
            $aciertos = 0;
            $respuestasGuardadas = [];
            
            foreach ($preguntasExamen as $pregunta) {
                $respuestaSeleccionada = $respuestas[$pregunta->id] ?? null;
                $esCorrecta = ($respuestaSeleccionada === 'correcta');
                
                if ($esCorrecta) $aciertos++;
                
                $textoRespuesta = $this->getTextoRespuesta($pregunta, $respuestaSeleccionada);
                
                $respuestasGuardadas[] = [
                    'pregunta_id' => $pregunta->id,
                    'respuesta' => $textoRespuesta,
                    'estatus' => $esCorrecta ? 'correcta' : 'incorrecta'
                ];
            }
            
            $calificacion = $totalPreguntas > 0 ? round(($aciertos / $totalPreguntas) * 100) : 0;
            $intento = $intentosRealizados + 1;
            
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
                'tiempo' => $tiempoFormateado,
                'respuestas' => json_encode(['respuestas' => $respuestasGuardadas])
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Examen completado',
                'calificacion' => $calificacion,
                'aciertos' => $aciertos,
                'total' => $totalPreguntas,
                'intento' => $intento,
                'redirect' => route('estudiante.resultados', $examenRealizado->id)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en responderSimulador: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al procesar el examen'], 500);
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
        
        $respuestasData = json_decode($examenRealizado->respuestas, true);
        $respuestasLista = $respuestasData['respuestas'] ?? [];
        
        $respuestasMap = [];
        foreach ($respuestasLista as $respuestaItem) {
            $respuestasMap[$respuestaItem['pregunta_id']] = [
                'respuesta' => $respuestaItem['respuesta'],
                'estatus' => $respuestaItem['estatus']
            ];
        }
        
        $preguntasIds = array_keys($respuestasMap);
        $preguntas = Pregunta::whereIn('id', $preguntasIds)->get()->keyBy('id');
        
        $preguntasConRespuestas = [];
        foreach ($respuestasMap as $preguntaId => $respuestaInfo) {
            $pregunta = $preguntas->get($preguntaId);
            
            $preguntasConRespuestas[] = (object)[
                'id' => $preguntaId,
                'texto' => $pregunta ? ($pregunta->pregunta ?? 'Pregunta sin texto') : 'Pregunta no encontrada',
                'respuesta_correcta' => $pregunta ? ($pregunta->respuesta_correcta ?? null) : null,
                'respuesta_usuario' => $respuestaInfo['respuesta'],
                'estatus' => $respuestaInfo['estatus'],
                'es_correcta' => $respuestaInfo['estatus'] === 'correcta',
                'justificacion' => $pregunta ? ($pregunta->justificacion ?? null) : null
            ];
        }
        
        $mejorCalificacion = ExamenRealizado::where('estudiante', $estudiante->id)
            ->where('examen', $examenRealizado->examen)
            ->max('calificacion') ?? 0;
        
        return view('estudiante.resultados', compact('examenRealizado', 'estudiante', 'mejorCalificacion', 'preguntasConRespuestas'));
    }

    // ========== MÉTODOS AUXILIARES ==========
    
    private function getTextoRespuesta($pregunta, $respuestaSeleccionada)
    {
        if (!$respuestaSeleccionada) {
            return 'No respondida';
        }
        
        if ($respuestaSeleccionada === 'correcta') {
            return $pregunta->respuesta_correcta ?? 'Respuesta correcta no disponible';
        }
        
        if ($respuestaSeleccionada === 'incorrecta1') {
            return $pregunta->respuesta1 ?? 'Opción no disponible';
        }
        
        if ($respuestaSeleccionada === 'incorrecta2') {
            return $pregunta->respuesta2 ?? 'Opción no disponible';
        }
        
        return $respuestaSeleccionada;
    }

    public function getRecomendacionesUniversidades(){
        try {
            $user = Auth::user();
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            
            if (!$estudiante) {
                return response()->json(['success' => false, 'message' => 'Estudiante no encontrado']);
            }
            
            $examenesRealizados = ExamenRealizado::where('estudiante', $estudiante->id)->get();
            $promedio = $examenesRealizados->avg('calificacion') ?? 0;
            $mejorCalificacion = $examenesRealizados->max('calificacion') ?? 0;
            
            $universidadInteres = null;
            $carreraInteres = null;
            
            if ($estudiante->universidad_interes) {
                $universidadInteres = Universidad::with('carrera')->find($estudiante->universidad_interes);
                if ($universidadInteres && $universidadInteres->carrera) {
                    $carreraInteres = $universidadInteres->carrera;
                }
            }
            
            $progresoCarrera = null;
            if ($carreraInteres) {
                $calificacionMinima = $carreraInteres->calificacion_minima ?? 0;
                $diferenciaFaltante = $calificacionMinima > 0 ? max(0, $calificacionMinima - $promedio) : 0;
                $porcentajeProgreso = $calificacionMinima > 0 ? min(100, round(($promedio / $calificacionMinima) * 100)) : 100;
                
                $estado = $calificacionMinima == 0 ? 'sin_requisito' : ($promedio >= $calificacionMinima ? 'cumple' : ($promedio >= $calificacionMinima * 0.7 ? 'cerca' : 'lejos'));
                
                $progresoCarrera = [
                    'id' => $carreraInteres->id,
                    'nombre' => $carreraInteres->nombre,
                    'calificacion_minima' => $calificacionMinima,
                    'calificacion_minima_formateada' => ($calificacionMinima > 0) ? $calificacionMinima . '%' : 'No definido',
                    'estado' => $estado,
                    'diferencia_faltante' => $diferenciaFaltante,
                    'porcentaje_progreso' => $porcentajeProgreso
                ];
            }
            
            return response()->json([
                'success' => true,
                'promedio' => round($promedio, 1),
                'mejor_calificacion' => round($mejorCalificacion, 1),
                'total_examenes' => $examenesRealizados->count(),
                'tiene_universidad_interes' => !is_null($estudiante->universidad_interes),
                'carrera_interes' => $progresoCarrera
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en getRecomendacionesUniversidades: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener recomendaciones'], 500);
        }
    }

    // ========== MÉTODOS PARA EXÁMENES DE MATERIA ==========

    /**
     * Muestra el formulario del examen de materia
     */
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

    /**
     * Procesa las respuestas del examen de materia
     */
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
            if ($preguntasExamen->isEmpty()) {
                $preguntasExamen = DB::table('preguntas')
                    ->join('apoyo_preguntas', 'preguntas.id', '=', 'apoyo_preguntas.pregunta')
                    ->where('apoyo_preguntas.examen', $examen->id)
                    ->select('preguntas.*')
                    ->get();
            }
            
            $totalPreguntas = $preguntasExamen->count();
            $aciertos = 0;
            $respuestasGuardadas = [];
            
            foreach ($preguntasExamen as $pregunta) {
                $respuestaSeleccionada = $respuestas[$pregunta->id] ?? null;
                $esCorrecta = ($respuestaSeleccionada === 'correcta');
                
                if ($esCorrecta) {
                    $aciertos++;
                }
                
                $textoRespuesta = $this->getTextoRespuesta($pregunta, $respuestaSeleccionada);
                
                $respuestasGuardadas[] = [
                    'pregunta_id' => $pregunta->id,
                    'respuesta' => $textoRespuesta,
                    'estatus' => $esCorrecta ? 'correcta' : 'incorrecta'
                ];
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
                'tiempo' => $tiempoFormateado,
                'respuestas' => json_encode([
                    'respuestas' => $respuestasGuardadas
                ])
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

    // ========== MÉTODOS PARA EXÁMENES FINALES DEL CURSO ==========

    /**
     * Muestra el formulario del examen final del curso
     */
    public function examenCurso($examenId)
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante || !$estudiante->plan_activo) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Necesitas un plan activo para acceder al examen final');
        }
        
        $examen = ExamenGenerado::where('id', $examenId)
                            ->where(function($query) {
                                $query->where('tipo_examen', 'Curso')
                                      ->orWhere('tipo_examen', 'curso');
                            })
                            ->first();
        
        if (!$examen) {
            return redirect()->route('estudiante.clases-premium')->with('error', 'Examen final no encontrado');
        }
        
        $preguntas = $examen->preguntas()->get();
        
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
     * Procesa las respuestas del examen final del curso
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
            $respuestasGuardadas = [];
            
            foreach ($preguntasExamen as $pregunta) {
                $respuestaSeleccionada = $respuestas[$pregunta->id] ?? null;
                $esCorrecta = ($respuestaSeleccionada === 'correcta');
                
                if ($esCorrecta) {
                    $aciertos++;
                }
                
                $textoRespuesta = $this->getTextoRespuesta($pregunta, $respuestaSeleccionada);
                
                $respuestasGuardadas[] = [
                    'pregunta_id' => $pregunta->id,
                    'respuesta' => $textoRespuesta,
                    'estatus' => $esCorrecta ? 'correcta' : 'incorrecta'
                ];
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
                'tiempo' => $tiempoFormateado,
                'respuestas' => json_encode([
                    'respuestas' => $respuestasGuardadas
                ])
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

    /**
     * Ver respuestas detalladas de un examen realizado
     */
    public function verRespuestasExamen($examenRealizadoId)
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        
        if (!$estudiante) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Estudiante no encontrado');
        }
        
        $examenRealizado = ExamenRealizado::where('id', $examenRealizadoId)
                                        ->where('estudiante', $estudiante->id)
                                        ->first();
        
        if (!$examenRealizado) {
            return redirect()->route('estudiante.dashboard')->with('error', 'Examen no encontrado');
        }
        
        $respuestas = json_decode($examenRealizado->respuestas, true);
        
        $preguntasIds = array_column($respuestas['respuestas'] ?? [], 'pregunta_id');
        $preguntas = Pregunta::whereIn('id', $preguntasIds)->get()->keyBy('id');
        
        $preguntasConRespuestas = [];
        foreach ($respuestas['respuestas'] ?? [] as $respuestaItem) {
            $pregunta = $preguntas->get($respuestaItem['pregunta_id']);
            
            $preguntasConRespuestas[] = (object)[
                'id' => $respuestaItem['pregunta_id'],
                'texto' => $pregunta ? $pregunta->pregunta : 'Pregunta no encontrada',
                'respuesta_correcta' => $pregunta ? $pregunta->respuesta_correcta : null,
                'respuesta_usuario' => $respuestaItem['respuesta'],
                'estatus' => $respuestaItem['estatus'],
                'es_correcta' => $respuestaItem['estatus'] === 'correcta',
                'justificacion' => $pregunta ? $pregunta->justificacion : null
            ];
        }
        
        return view('estudiante.ver-respuestas', compact('examenRealizado', 'preguntasConRespuestas'));
    }

    /**
     * Obtener recursos adicionales de una clase
     */
    public function getRecursosClase($claseId)
    {
        try {
            $clase = Clase::with('recursos')->find($claseId);
            
            if (!$clase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clase no encontrada'
                ]);
            }
            
            $recursos = $clase->recursos->map(function($recurso) {
                return [
                    'id' => $recurso->id,
                    'titulo' => $recurso->titulo,
                    'tipo' => $recurso->tipo,
                    'url' => $recurso->url,
                    'descripcion' => $recurso->descripcion,
                    'tipo_nombre' => $recurso->tipo_nombre,
                    'icono' => $recurso->icono,
                    'color' => $recurso->color
                ];
            });
            
            return response()->json([
                'success' => true,
                'recursos' => $recursos
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en getRecursosClase: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener recursos'
            ]);
        }
    }

    /**
     * Validar un cupón de descuento (desde el panel del estudiante)
     */
    public function validarCupon(Request $request)
    {
        try {
            $request->validate([
                'cupon' => 'required|string|max:50'
            ]);
            
            $codigo = strtoupper(trim($request->cupon));
            
            $cupon = Cupon::where('codigo', $codigo)->first();
            
            if (!$cupon) {
                return response()->json([
                    'valid' => false,
                    'message' => 'El cupón no existe'
                ]);
            }
            
            if ($cupon->usado) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Este cupón ya ha sido utilizado'
                ]);
            }
            
            if ($cupon->estatus !== 'activo') {
                return response()->json([
                    'valid' => false,
                    'message' => 'Este cupón no está activo'
                ]);
            }
            
            if ($cupon->fecha_expiracion && Carbon::now()->greaterThan($cupon->fecha_expiracion)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Este cupón ha expirado'
                ]);
            }
            
            return response()->json([
                'valid' => true,
                'descuento' => $cupon->valor_descuento,
                'tipo' => $cupon->tipo_descuento,
                'message' => 'Cupón válido!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al validar cupón: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'message' => 'Error al validar el cupón: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener universidades para el estudiante
     */
    public function getUniversidades()
    {
        $universidades = Universidad::with('carrera')->orderBy('clave')->get();
        return response()->json([
            'success' => true,
            'universidades' => $universidades
        ]);
    }
}