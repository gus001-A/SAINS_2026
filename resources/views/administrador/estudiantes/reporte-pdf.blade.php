<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estudiante - {{ $estudiante->nombre }} {{ $estudiante->paterno }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            background: #f5f5f5;
            color: #333333;
            font-size: 11px;
            line-height: 1.4;
            padding: 20px;
        }
        
        /* Contenedor principal */
        .report {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            padding: 25px;
        }
        
        /* ========== HEADER MEJORADO ========== */
        .header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 25px;
            border-bottom: 2px solid #2c3e50;
        }
        
        .logo-img {
            width: 70px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            letter-spacing: 1px;
        }
        
        .header h2 {
            font-size: 14px;
            font-weight: 500;
            color: #7f8c8d;
            margin: 5px 0 0;
        }
        
        .header-subtitle {
            font-size: 10px;
            color: #95a5a6;
            margin-top: 8px;
        }
        
        .report-meta {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 9px;
            color: #95a5a6;
        }
        
        /* ========== PERFIL ========== */
        .profile-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
            page-break-inside: avoid;
        }
        
        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .profile-email {
            font-size: 10px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .profile-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .badge-active {
            background: #d4edda;
            color: #155724;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 500;
        }
        
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 500;
        }
        
        .badge-cupon {
            background: #fff3cd;
            color: #856404;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-family: monospace;
        }
        
        /* ========== TABLA DE ESTADÍSTICAS ========== */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .stats-table td {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 12px 10px;
            text-align: center;
            width: 25%;
        }
        
        .stats-number {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            display: block;
        }
        
        .stats-label {
            font-size: 9px;
            color: #7f8c8d;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }
        
        /* ========== TABLAS DE INFORMACIÓN ========== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .data-table th {
            background: #e9ecef;
            padding: 8px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            color: #2c3e50;
            border: 1px solid #dee2e6;
            width: 30%;
        }
        
        .data-table td {
            background: white;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            font-size: 10px;
            color: #555;
        }
        
        /* ========== SECCIONES ========== */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        /* ========== TABLA DE ACTIVIDAD (SIN BARRAS) ========== */
        .actividad-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .actividad-table th {
            background: #e9ecef;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            color: #2c3e50;
            border: 1px solid #dee2e6;
        }
        
        .actividad-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            font-size: 9px;
            color: #555;
        }
        
        /* ========== TABLA DE MÉTRICAS ========== */
        .metrics-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .metrics-table td {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 10px;
            text-align: center;
            width: 33.33%;
        }
        
        .metrics-number {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            display: block;
        }
        
        .metrics-label {
            font-size: 9px;
            color: #7f8c8d;
            margin: 4px 0;
            display: block;
        }
        
        .progress-bar {
            background: #e9ecef;
            height: 4px;
            border-radius: 2px;
            margin: 6px 0 3px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: #3498db;
            border-radius: 2px;
        }
        
        /* ========== TABLA DE EXÁMENES ========== */
        .examenes-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .examenes-table th {
            background: #e9ecef;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            color: #2c3e50;
            border: 1px solid #dee2e6;
        }
        
        .examenes-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            font-size: 9px;
            color: #555;
        }
        
        .score-high {
            color: #27ae60;
            font-weight: 600;
        }
        
        .score-low {
            color: #e74c3c;
            font-weight: 600;
        }
        
        /* ========== FOOTER ========== */
        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 8px;
            color: #95a5a6;
            page-break-inside: avoid;
        }
        
        /* Utilidades */
        .text-center { text-align: center; }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="report">
        
        <!-- ==================== PAGINA 1 ==================== -->
        
        <!-- HEADER MEJORADO -->
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" class="logo-img" alt="SAINS">
            <h1>SAINS</h1>
            <h2>Plataforma educativa para ingreso a la universidad</h2>
            <div class="header-subtitle">Sistema de Administracion y Gestion Academica</div>
            <div class="report-meta">
                <span>Reporte de Estudiante</span>
                <span>|</span>
                <span>Generado: {{ date('d/m/Y H:i:s') }}</span>
            </div>
        </div>
        
        <!-- PERFIL -->
        <div class="profile-card">
            <div class="profile-name">{{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}</div>
            <div class="profile-email">{{ $usuario->correo }}</div>
            <div class="profile-badges">
                @if($estudiante->plan_activo)
                    <span class="badge-active">Plan Activo</span>
                @else
                    <span class="badge-inactive">Plan Inactivo</span>
                @endif
                @if($estudiante->cupon)
                    <span class="badge-cupon">Cupon: {{ $estudiante->cupon }}</span>
                @endif
            </div>
        </div>
        
        <!-- TABLA DE ESTADISTICAS PRINCIPALES -->
        <table class="stats-table">
            <tr>
                @php
                    $horas = floor($tiempoTotalHoras ?? 0);
                    $minutos = round(($tiempoTotalHoras - $horas) * 60);
                    if ($horas >= 24) {
                        $dias = floor($horas / 24);
                        $horasResto = $horas % 24;
                        $tiempoTexto = $dias . 'd ' . $horasResto . 'h';
                    } else {
                        $tiempoTexto = $horas . 'h ' . $minutos . 'm';
                    }
                @endphp
                <td>
                    <span class="stats-number">{{ $tiempoTexto }}</span>
                    <span class="stats-label">Tiempo de estudio</span>
                </td>
                <td>
                    <span class="stats-number">{{ $vistosCompletos ?? 0 }}/{{ $totalVideos ?? 0 }}</span>
                    <span class="stats-label">Videos vistos</span>
                </td>
                <td>
                    <span class="stats-number">{{ number_format($promedioCalificaciones ?? 0) }}%</span>
                    <span class="stats-label">Promedio general</span>
                </td>
                <td>
                    <span class="stats-number">{{ number_format($diasActivos ?? 0) }}</span>
                    <span class="stats-label">Dias activos</span>
                </td>
            </tr>
        </table>
        
        <!-- TABLA INFORMACION PERSONAL -->
        <div class="section">
            <div class="section-title">Informacion Personal</div>
            <table class="data-table">
                <tr>
                    <th>Nombre completo</th>
                    <td colspan="3">{{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}</td>
                </tr>
                <tr>
                    <th>Fecha de nacimiento</th>
                    <td>{{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años)</td>
                    <th>Sexo</th>
                    <td>{{ $estudiante->sexo == 'M' ? 'Masculino' : 'Femenino' }}</td>
                </tr>
                <tr>
                    <th>Telefono celular</th>
                    <td>{{ $estudiante->telefono ?? '—' }}</td>
                    <th>Telefono casa</th>
                    <td>{{ $estudiante->telefono_casa ?? '—' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- TABLA INFORMACION ACADEMICA -->
        <div class="section">
            <div class="section-title">Informacion Academica</div>
            <table class="data-table">
                <tr>
                    <th>Escuela de procedencia</th>
                    <td colspan="3">{{ $estudiante->escuelaProcedencia->centro_educativo ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Ubicacion (Prepa)</th>
                    <td colspan="3">{{ $estudiante->escuelaProcedencia->estado ?? '' }} / {{ $estudiante->escuelaProcedencia->municipio ?? '' }} / {{ $estudiante->escuelaProcedencia->localidad ?? '' }}</td>
                </tr>
                <tr>
                    <th>Turno</th>
                    <td>{{ $estudiante->escuelaProcedencia->turno ?? '—' }}</td>
                    <th>Clave</th>
                    <td>{{ $estudiante->escuelaProcedencia->clave ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Universidad de interes</th>
                    <td colspan="3">{{ $estudiante->universidadInteres->clave ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Ubicacion (Universidad)</th>
                    <td colspan="3">{{ $estudiante->universidadInteres->estado ?? '' }} / {{ $estudiante->universidadInteres->municipio ?? '' }} / {{ $estudiante->universidadInteres->localidad ?? '' }}</td>
                </tr>
                <tr>
                    <th>Tipo / Duracion</th>
                    <td colspan="3">{{ $estudiante->universidadInteres->tipo ?? '—' }} / {{ $estudiante->universidadInteres->duracion ?? '—' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- ==================== PAGINA 2 ==================== -->
        <div class="page-break"></div>
        
        <!-- TABLA DE ACTIVIDAD (SIN BARRAS, SOLO DATOS) -->
        <div class="section">
            <div class="section-title">Actividad - Ultimos 7 dias</div>
            <table class="actividad-table">
                <thead>
                    <tr>
                        <th>Dia</th>
                        <th>Fecha</th>
                        <th>Horas estudiadas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudioDiario ?? [] as $dia)
                        @php
                            $horas = floatval($dia->horas_estudiadas ?? 0);
                            $diaSemana = [
                                'Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miercoles', 'Thu' => 'Jueves',
                                'Fri' => 'Viernes', 'Sat' => 'Sabado', 'Sun' => 'Domingo'
                            ][$dia->dia] ?? $dia->dia;
                        @endphp
                        <tr>
                            <td><strong>{{ $diaSemana }}</strong></td>
                            <td>{{ $dia->fecha }}</td>
                            <td>{{ number_format($horas, 1) }} horas</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- TABLA EXTRAS DE ESTUDIO -->
        <table class="data-table" style="margin-bottom: 20px;">
            <tr>
                <th>Sesiones totales</th>
                <td>{{ number_format($totalSesiones ?? 0) }}</td>
                <th>Ultima actividad</th>
                <td>{{ $ultimaActividad ?? '—' }}</td>
            </tr>
        </table>
        
        <!-- TABLA RENDIMIENTO POR TIPO -->
        <div class="section">
            <div class="section-title">Rendimiento por tipo de examen</div>
            <table class="metrics-table">
                <tr>
                    <td>
                        <span class="metrics-number">{{ $examenesPorTipo['simulacion'] ?? 0 }}</span>
                        <span class="metrics-label">Simulaciones</span>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ ($promedioPorTipo['simulacion'] ?? 0) }}%;"></div>
                        </div>
                        <span class="metrics-label">Promedio: {{ number_format($promedioPorTipo['simulacion'] ?? 0, 0) }}%</span>
                    </td>
                    <td>
                        <span class="metrics-number">{{ $examenesPorTipo['materia'] ?? 0 }}</span>
                        <span class="metrics-label">Por materia</span>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ ($promedioPorTipo['materia'] ?? 0) }}%;"></div>
                        </div>
                        <span class="metrics-label">Promedio: {{ number_format($promedioPorTipo['materia'] ?? 0, 0) }}%</span>
                    </td>
                    <td>
                        <span class="metrics-number">{{ $examenesPorTipo['curso'] ?? 0 }}</span>
                        <span class="metrics-label">Por curso</span>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ ($promedioPorTipo['curso'] ?? 0) }}%;"></div>
                        </div>
                        <span class="metrics-label">Promedio: {{ number_format($promedioPorTipo['curso'] ?? 0, 0) }}%</span>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- ==================== PAGINA 3 ==================== -->
        <div class="page-break"></div>
        
        <!-- TABLA DE EXAMENES -->
        @if($examenes->isNotEmpty())
        <div class="section">
            <div class="section-title">Historial de examenes</div>
            <table class="examenes-table">
                <thead>
                    <tr>
                        <th>Examen</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Calificacion</th>
                        <th>Intento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examenes->take(15) as $examen)
                        @php
                            $score = floatval($examen->calificacion ?? 0);
                        @endphp
                        <tr>
                            <td>{{ Str::limit($examen->examen_nombre ?? 'Examen', 40) }}</td>
                            <td>{{ $examen->tipo_texto ?? 'General' }}</td>
                            <td>{{ $examen->fecha_completa ?? '—' }}</td>
                            <td class="{{ $score >= 70 ? 'score-high' : 'score-low' }}">
                                {{ number_format($score, 0) }}%
                            </td>
                            <td>{{ $examen->intento ?? 1 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($examenes->count() > 15)
                <div class="text-center mt-2" style="font-size: 8px; color: #95a5a6;">* Mostrando ultimos 15 de {{ $examenes->count() }} examenes</div>
            @endif
        </div>
        @endif
        
        <!-- ==================== PAGINA 4 ==================== -->
        @if($ultimosVideos->isNotEmpty())
        <div class="page-break"></div>
        
        <!-- TABLA PROGRESO VIDEOS -->
        <div class="section">
            <div class="section-title">Progreso en videos</div>
            
            <table class="stats-table" style="margin-bottom: 15px;">
                <tr>
                    <td>
                        <span class="stats-number">{{ $vistosCompletos ?? 0 }}</span>
                        <span class="stats-label">Completados</span>
                    </td>
                    <td>
                        <span class="stats-number">{{ $videosEnProgreso ?? 0 }}</span>
                        <span class="stats-label">En progreso</span>
                    </td>
                    <td>
                        <span class="stats-number">{{ number_format($porcentajeProgreso ?? 0) }}%</span>
                        <span class="stats-label">Progreso</span>
                    </td>
                </tr>
            </table>
            
            <!-- Barra de progreso (sutil) -->
            <div class="progress-bar" style="margin-bottom: 15px; height: 6px;">
                <div class="progress-fill" style="width: {{ $porcentajeProgreso ?? 0 }}%; background: #27ae60;"></div>
            </div>
            
            <!-- TABLA ULTIMOS VIDEOS VISTOS -->
            <div class="section-title" style="margin-bottom: 10px;">Ultimos videos vistos</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Video</th>
                        <th width="25%">Fecha</th>
                        <th width="20%">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimosVideos as $progreso)
                    <tr>
                        <td>{{ Str::limit($progreso->video->titulo ?? 'Video', 50) }}</td>
                        <td>{{ $progreso->fecha_visto ? \Carbon\Carbon::parse($progreso->fecha_visto)->format('d/m/Y') : '—' }}</td>
                        <td>{{ $progreso->completado ? 'Completado' : 'En progreso' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- FOOTER -->
        <div class="footer">
            <p>SAINS - Plataforma educativa para ingreso a la universidad</p>
            <p>Reporte generado automaticamente - Documento confidencial</p>
        </div>
        
    </div>
</body>
</html>