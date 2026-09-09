<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estudiante - {{ $estudiante->nombre }} {{ $estudiante->paterno }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page { margin: 0; }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 10.5px;
            line-height: 1.45;
        }

        .wrap { padding: 32px 34px 40px; }

        /* ===== Encabezado ===== */
        .brandbar {
            background: #4f46e5;
            color: #ffffff;
            padding: 22px 34px;
        }
        .brandbar table { width: 100%; }
        .brandbar .logo { width: 44px; height: auto; vertical-align: middle; }
        .brandbar h1 {
            font-size: 20px; font-weight: 700; letter-spacing: 1px;
            display: inline-block; vertical-align: middle; margin-left: 12px;
        }
        .brandbar .sub { font-size: 10px; color: #c7d2fe; margin-top: 3px; }
        .brandbar .meta { text-align: right; font-size: 9px; color: #c7d2fe; line-height: 1.7; }
        .brandbar .meta strong { color: #ffffff; display: block; font-size: 11px; }

        /* ===== Ficha del estudiante ===== */
        .profile {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 20px;
        }
        .profile .name { font-size: 16px; font-weight: 700; color: #312e81; }
        .profile .email { font-size: 10px; color: #6366f1; margin: 3px 0 9px; }
        .chip {
            display: inline-block;
            padding: 3px 11px;
            border-radius: 999px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-right: 5px;
        }
        .chip-on { background: #dcfce7; color: #166534; }
        .chip-off { background: #fee2e2; color: #991b1b; }
        .chip-cupon { background: #fef3c7; color: #92400e; font-family: 'DejaVu Sans Mono', monospace; }

        /* ===== Tarjetas de estadísticas ===== */
        .stats { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin: 0 -8px 22px; }
        .stats td {
            width: 25%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: 3px solid #4f46e5;
            border-radius: 8px;
            padding: 12px 8px;
            text-align: center;
        }
        .stats .num { font-size: 17px; font-weight: 800; color: #0f172a; display: block; }
        .stats .lbl {
            font-size: 8px; color: #64748b; margin-top: 4px;
            text-transform: uppercase; letter-spacing: 0.5px; display: block;
        }
        .stats td.c-green { border-top-color: #16a34a; }
        .stats td.c-amber { border-top-color: #d97706; }
        .stats td.c-blue { border-top-color: #0ea5e9; }

        /* ===== Secciones ===== */
        .section { margin-bottom: 22px; page-break-inside: avoid; }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #312e81;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 0 6px 10px;
            border-left: 3px solid #4f46e5;
            margin-bottom: 10px;
        }

        /* ===== Tablas de datos ===== */
        table.data { width: 100%; border-collapse: collapse; }
        table.data th {
            background: #f1f5f9;
            padding: 7px 11px;
            text-align: left;
            font-size: 9.5px;
            font-weight: 700;
            color: #334155;
            border: 1px solid #e2e8f0;
            width: 26%;
        }
        table.data td {
            padding: 7px 11px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            color: #475569;
        }

        table.grid { width: 100%; border-collapse: collapse; }
        table.grid th {
            background: #4f46e5;
            color: #ffffff;
            padding: 7px 10px;
            text-align: left;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        table.grid td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5px;
            color: #475569;
        }
        table.grid tr:nth-child(even) td { background: #f8fafc; }

        .metrics { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
        .metrics td {
            width: 33.33%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 10px;
            text-align: center;
        }
        .metrics .num { font-size: 16px; font-weight: 800; color: #0f172a; display: block; }
        .metrics .lbl { font-size: 8.5px; color: #64748b; margin: 3px 0; display: block; }
        .bar { background: #e2e8f0; height: 5px; border-radius: 3px; margin: 6px 0 3px; }
        .bar > span { display: block; height: 5px; border-radius: 3px; background: #4f46e5; }

        .score-high { color: #16a34a; font-weight: 700; }
        .score-low { color: #dc2626; font-weight: 700; }

        .footer {
            margin-top: 26px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            page-break-inside: avoid;
        }
        .page-break { page-break-before: always; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="brandbar">
        <table>
            <tr>
                <td>
                    <img src="{{ public_path('images/logo-sm.png') }}" class="logo" alt="SAINS">
                    <h1>SAINS</h1>
                    <div class="sub">Preparación para el ingreso a la universidad</div>
                </td>
                <td class="meta">
                    <strong>Reporte de estudiante</strong>
                    Generado: {{ date('d/m/Y H:i') }}<br>
                    Documento confidencial
                </td>
            </tr>
        </table>
    </div>

    <div class="wrap">

        <!-- Ficha -->
        <div class="profile">
            <div class="name">{{ $estudiante->nombre }} {{ $estudiante->paterno }} {{ $estudiante->materno }}</div>
            <div class="email">{{ $usuario->correo }}</div>
            @if($estudiante->plan_activo)
                <span class="chip chip-on">Plan activo</span>
            @else
                <span class="chip chip-off">Plan inactivo</span>
            @endif
            @if($estudiante->cupon)
                <span class="chip chip-cupon">Cupón: {{ $estudiante->cupon }}</span>
            @endif
        </div>

        <!-- Estadísticas principales -->
        @php
            $horas = floor($tiempoTotalHoras ?? 0);
            $minutos = round((($tiempoTotalHoras ?? 0) - $horas) * 60);
            if ($horas >= 24) {
                $tiempoTexto = floor($horas / 24) . 'd ' . ($horas % 24) . 'h';
            } else {
                $tiempoTexto = $horas . 'h ' . $minutos . 'm';
            }
        @endphp
        <table class="stats">
            <tr>
                <td><span class="num">{{ $tiempoTexto }}</span><span class="lbl">Tiempo de estudio</span></td>
                <td class="c-blue"><span class="num">{{ $vistosCompletos ?? 0 }}/{{ $totalVideos ?? 0 }}</span><span class="lbl">Videos vistos</span></td>
                <td class="c-green"><span class="num">{{ number_format($promedioCalificaciones ?? 0) }}%</span><span class="lbl">Promedio general</span></td>
                <td class="c-amber"><span class="num">{{ number_format($diasActivos ?? 0) }}</span><span class="lbl">Días activos</span></td>
            </tr>
        </table>

        <!-- Información personal -->
        <div class="section">
            <div class="section-title">Información personal</div>
            <table class="data">
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
                    <th>Teléfono celular</th>
                    <td>{{ $estudiante->telefono ?? '—' }}</td>
                    <th>Teléfono casa</th>
                    <td>{{ $estudiante->telefono_casa ?? '—' }}</td>
                </tr>
            </table>
        </div>

        <!-- Información académica -->
        <div class="section">
            <div class="section-title">Información académica</div>
            <table class="data">
                <tr>
                    <th>Escuela de procedencia</th>
                    <td colspan="3">{{ $estudiante->escuelaProcedencia->centro_educativo ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Ubicación (prepa)</th>
                    <td colspan="3">{{ $estudiante->escuelaProcedencia->estado ?? '' }} / {{ $estudiante->escuelaProcedencia->municipio ?? '' }} / {{ $estudiante->escuelaProcedencia->localidad ?? '' }}</td>
                </tr>
                <tr>
                    <th>Turno</th>
                    <td>{{ $estudiante->escuelaProcedencia->turno ?? '—' }}</td>
                    <th>Clave</th>
                    <td>{{ $estudiante->escuelaProcedencia->clave ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Universidad de interés</th>
                    <td colspan="3">{{ $estudiante->universidadInteres->clave ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Ubicación (universidad)</th>
                    <td colspan="3">{{ $estudiante->universidadInteres->estado ?? '' }} / {{ $estudiante->universidadInteres->municipio ?? '' }} / {{ $estudiante->universidadInteres->localidad ?? '' }}</td>
                </tr>
                <tr>
                    <th>Tipo / Duración</th>
                    <td colspan="3">{{ $estudiante->universidadInteres->tipo ?? '—' }} / {{ $estudiante->universidadInteres->duracion ?? '—' }}</td>
                </tr>
            </table>
        </div>

        <div class="page-break"></div>

        <!-- Actividad -->
        <div class="section">
            <div class="section-title">Actividad · últimos 7 días</div>
            <table class="grid">
                <thead>
                    <tr><th>Día</th><th>Fecha</th><th>Horas estudiadas</th></tr>
                </thead>
                <tbody>
                    @forelse($estudioDiario ?? [] as $dia)
                        @php
                            $h = floatval($dia->horas_estudiadas ?? 0);
                            $diaSemana = [
                                'Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles', 'Thu' => 'Jueves',
                                'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo',
                            ][$dia->dia] ?? $dia->dia;
                        @endphp
                        <tr>
                            <td><strong>{{ $diaSemana }}</strong></td>
                            <td>{{ $dia->fecha }}</td>
                            <td>{{ number_format($h, 1) }} h</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">Sin actividad registrada</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <table class="data" style="margin-bottom: 20px;">
            <tr>
                <th>Sesiones totales</th>
                <td>{{ number_format($totalSesiones ?? 0) }}</td>
                <th>Última actividad</th>
                <td>{{ $ultimaActividad ?? '—' }}</td>
            </tr>
        </table>

        <!-- Rendimiento por tipo -->
        <div class="section">
            <div class="section-title">Rendimiento por tipo de examen</div>
            <table class="metrics">
                <tr>
                    <td>
                        <span class="num">{{ $examenesPorTipo['simulacion'] ?? 0 }}</span>
                        <span class="lbl">Simulaciones</span>
                        <div class="bar"><span style="width: {{ min(100, $promedioPorTipo['simulacion'] ?? 0) }}%;"></span></div>
                        <span class="lbl">Prom. {{ number_format($promedioPorTipo['simulacion'] ?? 0, 0) }}%</span>
                    </td>
                    <td>
                        <span class="num">{{ $examenesPorTipo['materia'] ?? 0 }}</span>
                        <span class="lbl">Por materia</span>
                        <div class="bar"><span style="width: {{ min(100, $promedioPorTipo['materia'] ?? 0) }}%;"></span></div>
                        <span class="lbl">Prom. {{ number_format($promedioPorTipo['materia'] ?? 0, 0) }}%</span>
                    </td>
                    <td>
                        <span class="num">{{ $examenesPorTipo['curso'] ?? 0 }}</span>
                        <span class="lbl">Por curso</span>
                        <div class="bar"><span style="width: {{ min(100, $promedioPorTipo['curso'] ?? 0) }}%;"></span></div>
                        <span class="lbl">Prom. {{ number_format($promedioPorTipo['curso'] ?? 0, 0) }}%</span>
                    </td>
                </tr>
            </table>
        </div>

        @if($examenes->isNotEmpty())
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">Historial de exámenes</div>
            <table class="grid">
                <thead>
                    <tr><th>Examen</th><th>Tipo</th><th>Fecha</th><th>Calificación</th><th>Intento</th></tr>
                </thead>
                <tbody>
                    @foreach($examenes->take(15) as $examen)
                        @php $score = floatval($examen->calificacion ?? 0); @endphp
                        <tr>
                            <td>{{ Str::limit($examen->examen_nombre ?? 'Examen', 40) }}</td>
                            <td>{{ $examen->tipo_texto ?? 'General' }}</td>
                            <td>{{ $examen->fecha_completa ?? '—' }}</td>
                            <td class="{{ $score >= 70 ? 'score-high' : 'score-low' }}">{{ number_format($score, 0) }}%</td>
                            <td>{{ $examen->intento ?? 1 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($examenes->count() > 15)
                <div class="text-center" style="font-size: 8px; color: #94a3b8; margin-top: 6px;">Mostrando 15 de {{ $examenes->count() }} exámenes</div>
            @endif
        </div>
        @endif

        @if($ultimosVideos->isNotEmpty())
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">Progreso en videos</div>
            <table class="metrics" style="margin-bottom: 14px;">
                <tr>
                    <td><span class="num">{{ $vistosCompletos ?? 0 }}</span><span class="lbl">Completados</span></td>
                    <td><span class="num">{{ $videosEnProgreso ?? 0 }}</span><span class="lbl">En progreso</span></td>
                    <td><span class="num">{{ number_format($porcentajeProgreso ?? 0) }}%</span><span class="lbl">Progreso total</span></td>
                </tr>
            </table>
            <div class="bar" style="height: 7px; margin-bottom: 16px;">
                <span style="width: {{ min(100, $porcentajeProgreso ?? 0) }}%; height: 7px; background: #16a34a;"></span>
            </div>

            <div class="section-title" style="margin-bottom: 8px;">Últimos videos vistos</div>
            <table class="grid">
                <thead>
                    <tr><th>Video</th><th>Fecha</th><th>Estado</th></tr>
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

        <div class="footer">
            SAINS · Preparación para el ingreso a la universidad<br>
            Reporte generado automáticamente · Documento confidencial
        </div>

    </div>
</body>
</html>
