<?php
use App\Http\Controllers\Auth\ForgotPasswordController;  
use App\Http\Controllers\Auth\ResetPasswordController;   
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EstudianteController;
use App\Http\Controllers\Admin\PreparatoriaController;
use App\Http\Controllers\Admin\UniversidadController;
use App\Http\Controllers\Admin\PreguntaController;
use App\Http\Controllers\Admin\CuponController;
use App\Http\Controllers\Admin\PagoController;
use App\Http\Controllers\Admin\ExamenGeneradoController;
use App\Http\Controllers\Admin\InteraccionCallCenterController;
use App\Http\Controllers\Admin\AsignaturaController;
use App\Http\Controllers\Admin\CarreraController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\ClaseController;
use App\Http\Controllers\Alumno\AlumnoController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\GoogleController;



// ============================================
// RUTAS PÚBLICAS
// ============================================
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/terminos-y-condiciones', function () {
    return view('terms');
})->name('terms');

// Procesar login/registro (AJAX)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//AUTENTIFICACIÓN CON GOOGLE
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Heartbeat público
Route::post('/heartbeat', function() {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
})->name('heartbeat');

// Test de correo (solo para desarrollo)
if (app()->environment('local')) {
    Route::get('test-mail', function() {
        try {
            Mail::raw('Test email', function($message) {
                $message->to('test@example.com')->subject('Test Email');
            });
            return 'Correo enviado correctamente';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    });
}

// ============================================
// PANEL DE ADMINISTRADOR (con middleware admin)
// ============================================
Route::prefix('administrador')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // ========== DASHBOARD ==========
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // ========== API PARA NOTIFICACIONES ==========
    Route::get('/api/notificaciones', [AdminController::class, 'getNotificacionesApi'])->name('api.notificaciones');
    Route::post('/api/notificaciones/marcar/{id}', [AdminController::class, 'marcarNotificacionVista'])->name('api.notificaciones.marcar');
    Route::get('/api/actividad-reciente', [AdminController::class, 'getActividadRecienteApi'])->name('api.actividad');
    Route::get('/api/top-estudiantes', [AdminController::class, 'getTopEstudiantesApi'])->name('api.top.estudiantes');
    
    // ========== GESTIÓN DE ESTUDIANTES ==========
    Route::prefix('estudiantes')->name('estudiantes.')->group(function () {
        // PRIMERO: Todas las rutas estáticas (sin parámetros variables)
        Route::get('/crear', [EstudianteController::class, 'create'])->name('create');
        Route::post('/', [EstudianteController::class, 'store'])->name('store');
        Route::get('/buscar', [EstudianteController::class, 'buscar'])->name('buscar');
        Route::get('/buscar-preparatorias', [EstudianteController::class, 'buscarPreparatorias'])->name('buscar.preparatorias');
        Route::get('/buscar-universidades', [EstudianteController::class, 'buscarUniversidades'])->name('buscar.universidades');
        Route::get('/get-municipios-prepa', [EstudianteController::class, 'getMunicipiosPrepa'])->name('get.municipios.prepa');
        Route::get('/get-localidades-prepa', [EstudianteController::class, 'getLocalidadesPrepa'])->name('get.localidades.prepa');
        Route::get('/get-preparatorias', [EstudianteController::class, 'getPreparatorias'])->name('get.preparatorias');
        Route::get('/get-municipios-universidad', [EstudianteController::class, 'getMunicipiosUniversidad'])->name('get.municipios.universidad');
        Route::get('/get-localidades-universidad', [EstudianteController::class, 'getLocalidadesUniversidad'])->name('get.localidades.universidad');
        Route::get('/get-universidades', [EstudianteController::class, 'getUniversidades'])->name('get.universidades');
        Route::get('/exportar/pdf', [EstudianteController::class, 'exportarPDF'])->name('exportar.pdf');
        Route::get('/exportar/excel', [EstudianteController::class, 'exportarExcel'])->name('exportar.excel');
        Route::get('/estadisticas', [EstudianteController::class, 'estadisticas'])->name('estadisticas');
        Route::get('/filtro/estado', [EstudianteController::class, 'filtrarPorEstado'])->name('filtro.estado');
        Route::get('/{id}/reporte-pdf', [EstudianteController::class, 'generarReportePDF'])->name('reporte-pdf');
        Route::post('/validar-cupon', [EstudianteController::class, 'validarCupon'])->name('validar.cupon');
        Route::get('/cupones-disponibles', [EstudianteController::class, 'getCuponesDisponibles'])->name('cupones.disponibles');
        
        // ÚLTIMO: Rutas con parámetros (después de todas las estáticas)
        Route::get('/', [EstudianteController::class, 'index'])->name('index');
        Route::get('/{id}', [EstudianteController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [EstudianteController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EstudianteController::class, 'update'])->name('update');
        Route::delete('/{id}', [EstudianteController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/reset-password', [EstudianteController::class, 'resetPassword'])->name('reset-password');
    });
    
    // ========== GESTIÓN DE PREPARATORIAS ==========
    Route::prefix('preparatorias')->name('preparatorias.')->group(function () {
        Route::get('/', [PreparatoriaController::class, 'index'])->name('index');
        Route::get('/crear', [PreparatoriaController::class, 'create'])->name('create');
        Route::post('/', [PreparatoriaController::class, 'store'])->name('store');
        Route::get('/{id}', [PreparatoriaController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [PreparatoriaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PreparatoriaController::class, 'update'])->name('update');
        Route::delete('/{id}', [PreparatoriaController::class, 'destroy'])->name('destroy');
    });
    
    // ========== GESTIÓN DE UNIVERSIDADES ==========
    Route::prefix('universidades')->name('universidades.')->group(function () {
        Route::get('/', [UniversidadController::class, 'index'])->name('index');
        Route::get('/crear', [UniversidadController::class, 'create'])->name('create');
        Route::post('/', [UniversidadController::class, 'store'])->name('store');
        Route::get('/get-municipios', [UniversidadController::class, 'getMunicipios'])->name('get.municipios');
        Route::get('/get-localidades', [UniversidadController::class, 'getLocalidades'])->name('get.localidades');
        Route::get('/{id}', [UniversidadController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [UniversidadController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UniversidadController::class, 'update'])->name('update');
        Route::delete('/{id}', [UniversidadController::class, 'destroy'])->name('destroy');
    });
    
    // ========== GESTIÓN DE ADMINISTRADORES ==========
    Route::prefix('administradores')->name('administradores.')->group(function () {
        Route::get('/', [AdminController::class, 'administradores'])->name('index');
        Route::get('/crear', [AdminController::class, 'createAdmin'])->name('create');
        Route::post('/', [AdminController::class, 'storeAdmin'])->name('store');
        Route::get('/{id}/editar', [AdminController::class, 'editAdmin'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateAdmin'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'eliminarAdmin'])->name('destroy');
    });
    
    // ========== GESTIÓN DE PREGUNTAS ==========
    Route::prefix('preguntas')->name('preguntas.')->group(function () {
        Route::get('/', [PreguntaController::class, 'indexPreguntas'])->name('index');
        Route::get('/crear', [PreguntaController::class, 'createPregunta'])->name('create');
        Route::post('/', [PreguntaController::class, 'storePregunta'])->name('store');
        Route::get('/{id}', [PreguntaController::class, 'showPregunta'])->name('show');
        Route::get('/{id}/editar', [PreguntaController::class, 'editPregunta'])->name('edit');
        Route::put('/{id}', [PreguntaController::class, 'updatePregunta'])->name('update');
        Route::delete('/{id}', [PreguntaController::class, 'destroyPregunta'])->name('destroy');
    });
    
    // ========== GESTIÓN DE CUPONES ==========
    Route::prefix('cupones')->name('cupones.')->group(function () {
        Route::get('/', [CuponController::class, 'index'])->name('index');
        Route::get('/crear', [CuponController::class, 'create'])->name('create');
        Route::post('/', [CuponController::class, 'store'])->name('store');
        Route::post('/regenerar', [CuponController::class, 'regenerarCodigo'])->name('regenerar');
        Route::get('/{id}', [CuponController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [CuponController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CuponController::class, 'update'])->name('update');
        Route::delete('/{id}', [CuponController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/regenerar', [CuponController::class, 'regenerarCodigo'])->name('regenerar.id');
    });
    
    // ========== GESTIÓN DE PAGOS ==========
    Route::prefix('pagos')->name('pagos.')->group(function () {
        // PRIMERO: Rutas estáticas
        Route::get('/', [PagoController::class, 'index'])->name('index');
        Route::get('/crear', [PagoController::class, 'create'])->name('create');
        Route::post('/', [PagoController::class, 'store'])->name('store');
        Route::get('/dashboard', [PagoController::class, 'dashboard'])->name('dashboard');
        Route::get('/exportar/csv', [PagoController::class, 'exportar'])->name('exportar');
        Route::get('/alumno/{alumnoId}', [PagoController::class, 'getPagosByAlumno'])->name('alumno.pagos');
        
        // ÚLTIMO: Rutas con parámetros
        Route::get('/{id}', [PagoController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [PagoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PagoController::class, 'update'])->name('update');
        Route::delete('/{id}', [PagoController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/aprobar', [PagoController::class, 'aprobar'])->name('aprobar');
        Route::post('/{id}/rechazar', [PagoController::class, 'rechazar'])->name('rechazar');
        Route::put('/{id}/cambiar-estado', [PagoController::class, 'cambiarEstado'])->name('cambiar.estado');
    });
    
    // ========== GESTIÓN DE EXÁMENES ==========
    Route::prefix('examenes')->name('examenes.')->group(function () {
        // PRIMERO: Todas las rutas ESTÁTICAS
        Route::get('/dashboard', [ExamenGeneradoController::class, 'dashboard'])->name('dashboard');
        Route::get('/crear', [ExamenGeneradoController::class, 'create'])->name('create');
        Route::post('/generar-automatico', [ExamenGeneradoController::class, 'generarAutomatico'])->name('generar.automatico');
        Route::get('/', [ExamenGeneradoController::class, 'index'])->name('index');
        Route::post('/', [ExamenGeneradoController::class, 'store'])->name('store');
        
        // ÚLTIMO: Todas las rutas con parámetros
        Route::post('/{id}/duplicar', [ExamenGeneradoController::class, 'duplicar'])->name('duplicar');
        Route::get('/{id}/editar', [ExamenGeneradoController::class, 'edit'])->name('edit');
        Route::get('/{id}', [ExamenGeneradoController::class, 'show'])->name('show');
        Route::put('/{id}', [ExamenGeneradoController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExamenGeneradoController::class, 'destroy'])->name('destroy');
    });
    
    // ========== CALL CENTER ==========
    Route::prefix('callcenter')->name('callcenter.')->group(function () {
        // PRIMERO: Rutas estáticas
        Route::get('/', [InteraccionCallCenterController::class, 'index'])->name('index');
        Route::get('/interacciones', [InteraccionCallCenterController::class, 'listarInteracciones'])->name('interacciones');
        Route::get('/crear', [InteraccionCallCenterController::class, 'create'])->name('create');
        Route::post('/', [InteraccionCallCenterController::class, 'store'])->name('store');
        Route::get('/api/estudiantes-sin-plan', [InteraccionCallCenterController::class, 'getEstudiantesSinPlan'])->name('estudiantes.sin.plan');
        Route::get('/api/estadisticas', [InteraccionCallCenterController::class, 'getEstadisticasJson'])->name('estadisticas');
        
        // ÚLTIMO: Rutas con parámetros
        Route::get('/{id}', [InteraccionCallCenterController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [InteraccionCallCenterController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InteraccionCallCenterController::class, 'update'])->name('update');
        Route::delete('/{id}', [InteraccionCallCenterController::class, 'destroy'])->name('destroy');
    });

    // ========== GESTIÓN DE ASIGNATURAS (MATERIAS) ==========
    Route::prefix('asignaturas')->name('asignaturas.')->group(function () {
        Route::get('/', [AsignaturaController::class, 'index'])->name('index');
        Route::get('/create', [AsignaturaController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [AsignaturaController::class, 'edit'])->name('edit');
        Route::post('/', [AsignaturaController::class, 'store'])->name('store');
        Route::put('/{id}', [AsignaturaController::class, 'update'])->name('update');
        Route::delete('/{id}', [AsignaturaController::class, 'destroy'])->name('destroy');
        Route::get('/api/all', [AsignaturaController::class, 'getAsignaturasApi'])->name('api.all');
        Route::post('/api/verificar', [AsignaturaController::class, 'verificarAsignatura'])->name('api.verificar');
    });
    
    // ========== GESTIÓN DE CARRERAS ==========
    Route::prefix('carreras')->name('carreras.')->group(function () {
        Route::get('/', [CarreraController::class, 'index'])->name('index');
        Route::get('/create', [CarreraController::class, 'create'])->name('create');
        Route::post('/', [CarreraController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CarreraController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CarreraController::class, 'update'])->name('update');
        Route::delete('/{id}', [CarreraController::class, 'destroy'])->name('destroy');
        Route::get('/api/all', [CarreraController::class, 'getCarrerasApi'])->name('api.all');
    });
    
    // ========== GESTIÓN DE VIDEOS (SOLO UNA VEZ) ==========
    Route::prefix('videos')->name('videos.')->group(function () {
        Route::get('/', [VideoController::class, 'index'])->name('index');
        Route::get('/create', [VideoController::class, 'create'])->name('create');
        Route::post('/', [VideoController::class, 'store'])->name('store');
        Route::get('/{id}', [VideoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [VideoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VideoController::class, 'update'])->name('update');
        Route::delete('/{id}', [VideoController::class, 'destroy'])->name('destroy');
    });
    
    // ========== GESTIÓN DE CLASES ==========
    Route::prefix('clases')->name('clases.')->group(function () {
        Route::get('/', [ClaseController::class, 'index'])->name('index');
        Route::get('/create', [ClaseController::class, 'create'])->name('create');
        Route::post('/', [ClaseController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ClaseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ClaseController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClaseController::class, 'destroy'])->name('destroy');
        Route::get('/api/by-asignatura/{asignaturaId}', [ClaseController::class, 'getClasesByAsignaturaApi'])->name('api.by-asignatura');
    });
    
    // ========== PERFIL ==========
    Route::get('/perfil', [AdminController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [AdminController::class, 'updatePerfil'])->name('perfil.update');
    Route::put('/perfil/password', [AdminController::class, 'updatePassword'])->name('perfil.password');
});

// ============================================
// PANEL DE ESTUDIANTE (con middleware auth)
// ============================================
Route::middleware(['auth'])->prefix('estudiante')->name('estudiante.')->group(function () {
    // Vistas principales
    Route::get('/dashboard', [AlumnoController::class, 'dashboard'])->name('dashboard');
    Route::get('/progreso', [AlumnoController::class, 'progreso'])->name('progreso');
    Route::get('/simulador', [AlumnoController::class, 'simulador'])->name('simulador');
    Route::get('/examenes', function () { return view('estudiante.examenes'); })->name('examenes');
    Route::get('/clases-premium', [AlumnoController::class, 'clasesPremium'])->name('clases-premium');
    
    // Perfil
    Route::get('/perfil', [AlumnoController::class, 'perfil'])->name('perfil');
    Route::put('/perfil/actualizar', [AlumnoController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    Route::post('/perfil/cambiar-password', [AlumnoController::class, 'cambiarPassword'])->name('perfil.cambiar-password');
    Route::post('/subir-foto', [AlumnoController::class, 'subirFoto'])->name('subir.foto');
    Route::get('/get-foto', [AlumnoController::class, 'getFoto'])->name('get.foto');
    Route::delete('/eliminar-foto', [AlumnoController::class, 'eliminarFoto'])->name('eliminar.foto');
    
    // Completar perfil (primera vez)
    Route::get('/completar-perfil', [AlumnoController::class, 'completarPerfilForm'])->name('completar-perfil');
    Route::post('/completar-perfil', [AlumnoController::class, 'completarPerfil'])->name('completar.perfil');
    
    // Pagos y checkout
    Route::get('/checkout', [AlumnoController::class, 'checkout'])->name('checkout');
    Route::post('/aplicar-cupon', [AlumnoController::class, 'aplicarCupon'])->name('aplicar-cupon');
    Route::delete('/eliminar-cupon', [AlumnoController::class, 'eliminarCupon'])->name('eliminar-cupon');
    Route::post('/procesar-solicitud-pago', [AlumnoController::class, 'procesarSolicitudPago'])->name('procesar-solicitud-pago');
    Route::post('/registrar-pago', [AlumnoController::class, 'registrarPago'])->name('registrar.pago');
    Route::get('/ficha-pago/{pago}', [AlumnoController::class, 'mostrarFichaPago'])->name('ficha-pago');
    Route::get('/descargar-ficha/{pago}', [AlumnoController::class, 'descargarFichaPago'])->name('descargar-ficha');
    Route::post('/subir-comprobante', [AlumnoController::class, 'subirComprobantePago'])->name('subir-comprobante');
    Route::get('/pago-exito/{pago}', [AlumnoController::class, 'pagoExito'])->name('pago-exito');
    Route::get('/mis-pagos', [AlumnoController::class, 'misPagos'])->name('mis-pagos');
    
    // Exámenes
    Route::get('/examen-materia/{examenId}', [AlumnoController::class, 'examenMateria'])->name('examen.materia');
    Route::post('/responder-examen-materia', [AlumnoController::class, 'responderExamenMateria'])->name('responder.examen.materia');
    Route::get('/examen-curso/{examenId}', [AlumnoController::class, 'examenCurso'])->name('examen-curso');
    Route::post('/responder-examen-curso', [AlumnoController::class, 'responderExamenCurso'])->name('responder.examen-curso');
    
    // Simulador
    Route::post('/simulador/responder', [AlumnoController::class, 'responderSimulador'])->name('simulador.responder');
    Route::get('/resultados/{id}', [AlumnoController::class, 'resultados'])->name('resultados');
    Route::get('/simulador/{id}', [AlumnoController::class, 'cargarSimulador'])->name('simulador.cargar');
    
    // Progreso y APIs
    Route::post('/registrar-progreso-video', [AlumnoController::class, 'registrarProgresoVideo'])->name('registrar.progreso.video');
    Route::post('/heartbeat', [AlumnoController::class, 'heartbeat'])->name('heartbeat');
    Route::get('/get-estudiante', [AlumnoController::class, 'getEstudiante'])->name('get.estudiante');
    Route::get('/historial-examenes', [AlumnoController::class, 'getHistorialExamenes'])->name('historial.examenes');
    Route::get('/api/progreso', [AlumnoController::class, 'getProgresoApi'])->name('api.progreso');
    Route::get('/api/estadisticas', [AlumnoController::class, 'getEstadisticas'])->name('api.estadisticas');
    Route::get('/api/ultimos-examenes', [AlumnoController::class, 'getUltimosExamenes'])->name('api.ultimos-examenes');
    Route::get('/api/tiempo-estudio', [AlumnoController::class, 'getTiempoEstudio'])->name('api.tiempo-estudio');
});