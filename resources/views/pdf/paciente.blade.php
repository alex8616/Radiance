<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Historia Clínica</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        font-size: 10px;
        color: #2c3e50;
        background: #fff;
        line-height: 1.4;
    }
    
    .page {
        padding: 20px;
        background: #fff;
    }
    
    /* HEADER */
    .header {
        width: 100%;
        border-bottom: 2px solid #cbd5e0;
        margin-bottom: 20px;
        padding-bottom: 12px;
    }
    
    .header-table {
        width: 100%;
    }
    
    .logo {
        width: 80px;
    }
    
    .clinic {
        text-align: right;
        font-size: 10px;
        color: #4a5568;
    }
    
    .clinic strong {
        color: #2d3748;
        font-size: 12px;
    }
    
    /* TITULO */
    .title {
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        color: #1a202c;
        margin: 20px 0 25px 0;
        letter-spacing: 0.5px;
    }
    
    /* BLOQUES */
    .block {
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        overflow: hidden;
    }
    
    .block-title {
        font-weight: 600;
        font-size: 11px;
        color: #fff;
        background: #2d3748;
        padding: 8px 12px;
        letter-spacing: 0.3px;
    }
    
    /* TABLAS DE DATOS */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }
    
    .data-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #edf2f7;
    }
    
    .data-table tr:last-child td {
        border-bottom: none;
    }
    
    .data-table .label-cell {
        width: 180px;
        font-weight: 600;
        color: #4a5568;
        background: #f7fafc;
    }
    
    /* TABLA DE PATOLOGICOS */
    .patho-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .patho-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #edf2f7;
    }
    
    .patho-table tr:last-child td {
        border-bottom: none;
    }
    
    .patho-table .patho-label {
        font-weight: 600;
        color: #4a5568;
        width: 140px;
        background: #f7fafc;
    }
    
    /* TABLA DE HIGIENE */
    .hygiene-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .hygiene-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #edf2f7;
        text-align: center;
    }
    
    .hygiene-table th {
        padding: 8px 12px;
        background: #f7fafc;
        font-weight: 600;
        color: #2d3748;
        font-size: 9px;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        text-align: center;
    }
    
    .hygiene-table tr:last-child td {
        border-bottom: none;
    }
    
    /* TABLA DE TRATAMIENTO RESUMIDA */
    .tratamiento-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
    }
    
    .tratamiento-table th {
        background: #f7fafc;
        padding: 10px 8px;
        text-align: left;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 1px solid #e2e8f0;
        font-size: 9px;
        text-transform: uppercase;
    }
    
    .tratamiento-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: top;
    }
    
    .tratamiento-table tr:last-child td {
        border-bottom: none;
    }
    
    /* FOOTER FINANCIERO */
    .financial-footer {
        background: #f7fafc;
        padding: 10px 12px;
        margin-top: 10px;
        border-top: 2px solid #e2e8f0;
    }
    
    .financial-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }
    
    .financial-item {
        flex: 1;
        text-align: center;
        padding: 5px;
    }
    
    .financial-item .label {
        font-size: 8px;
        color: #718096;
        text-transform: uppercase;
        margin-bottom: 3px;
    }
    
    .financial-item .value {
        font-size: 13px;
        font-weight: bold;
    }
    
    /* RESUMEN GENERAL */
    .resumen-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
    }
    
    .resumen-table th {
        background: #f7fafc;
        padding: 8px;
        text-align: left;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .resumen-table td {
        padding: 8px;
        border-bottom: 1px solid #edf2f7;
    }
    
    .resumen-table tfoot td {
        background: #f7fafc;
        font-weight: bold;
        border-top: 1px solid #e2e8f0;
    }
    
    /* STATUS */
    .ok { color: #38a169; font-weight: 600; }
    .no { color: #e53e3e; font-weight: 600; }
    .text-success { color: #38a169; }
    .text-danger { color: #e53e3e; }
    .text-primary { color: #3182ce; }
    .text-muted { color: #718096; }
    
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 8px;
        font-weight: 600;
    }
    
    .badge-success { background: #c6f6d5; color: #22543d; }
    .badge-info { background: #bee3f8; color: #2c5282; }
    .badge-warning { background: #fefcbf; color: #744210; }
    
    /* FIRMA */
    .signature-img {
        max-width: 50px;
        max-height: 25px;
    }
    
    .signature-box {
        margin-top: 20px;
        padding: 15px;
        border-top: 1px solid #e2e8f0;
        text-align: center;
    }
    
    .signature-line {
        margin-top: 25px;
        padding-top: 5px;
        border-top: 1px solid #cbd5e0;
        width: 200px;
        display: inline-block;
        font-size: 8px;
        color: #718096;
    }
    
    /* PAGE BREAK */
    .page-break {
        page-break-before: always;
    }
    
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    
    @media print {
        body { margin: 0; padding: 0; }
        .page { padding: 15px; }
        .block { page-break-inside: avoid; }
    }
</style>
</head>

<body>

@php
    $pac = $paciente;
    $ant = optional($paciente->antecedenteMedico);
    
    // Totales generales
    $totalGeneralCosto = 0;
    $totalGeneralPagado = 0;
    $totalGeneralDeuda = 0;
    
    foreach($tratamientos as $t) {
        $pagado = collect($t->pagos)->sum('monto');
        $totalGeneralCosto += $t->costo_total;
        $totalGeneralPagado += $pagado;
        $totalGeneralDeuda += ($t->costo_total - $pagado);
    }
@endphp

<!-- ===================== HOJA 1 - DATOS DEL PACIENTE ===================== -->
<div class="page">

<!-- HEADER -->
<div class="header">
    <table class="header-table">
        <tr>
            <td><img src="{{ public_path('imagen/LogoFInal.png') }}" class="logo"></tr>
            <td class="clinic">
                <strong>Radiance Clínica</strong><br>
                <span class="text-muted">{{ now()->format('d/m/Y') }}</span>
            </td>
        </tr>
    </table>
</div>

<div class="title">HISTORIA CLÍNICA DEL PACIENTE</div>

<!-- DATOS DEL PACIENTE -->
<div class="block">
    <div class="block-title">DATOS DEL PACIENTE</div>
    <table class="data-table">
        <tr>
            <td class="label-cell">Nombre Completo</td>
            <td>{{ $pac->nombre }} {{ $pac->apellido_paterno }} {{ $pac->apellido_materno }}</td>
            <td class="label-cell">Cédula de Identidad</td>
            <td>{{ $pac->ci }}</td>
        </tr>
        <tr>
            <td class="label-cell">Sexo</td>
            <td>{{ $pac->sexo }}</td>
            <td class="label-cell">Fecha de Nacimiento</td>
            <td>{{ $pac->fecha_nacimiento }}</td>
        </tr>
        <tr>
            <td class="label-cell">Teléfono</td>
            <td>{{ $pac->telefono }}</td>
            <td class="label-cell">Estado Civil</td>
            <td>{{ $pac->estado_civil }}</td>
        </tr>
    </table>
</div>

<!-- ANTECEDENTES GENERALES -->
<div class="block">
    <div class="block-title">ANTECEDENTES GENERALES</div>
    <table class="data-table">
        <tr>
            <td class="label-cell">Alergias</td>
            <td>{{ $ant->alergias ?? 'No registra' }}</td>
            <td class="label-cell">Antecedentes Familiares</td>
            <td>{{ $ant->antecedentes_familiares ?? 'No registra' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Otros Antecedentes</td>
            <td colspan="3">{{ $ant->otros ?? 'No registra' }}</td>
        </tr>
    </table>
</div>

<!-- ANTECEDENTES PATOLÓGICOS -->
<div class="block">
    <div class="block-title">ANTECEDENTES PATOLÓGICOS</div>
    <table class="patho-table">
        <tr>
            <td class="patho-label">Anemia</td>
            <td>{!! $ant->anemia ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!}</td>
            <td class="patho-label">Asma</td>
            <td>{!! $ant->asma ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!}</td>
        </tr>
        <tr>
            <td class="patho-label">Cardiopatías</td>
            <td>{!! $ant->cardiopatias ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!}</td>
            <td class="patho-label">Diabetes</td>
            <td>{!! $ant->diabetes ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!}</td>
        </tr>
        <tr>
            <td class="patho-label">VIH</td>
            <td colspan="3">{!! $ant->vih ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!}</td>
        </tr>
    </table>
</div>

<!-- ANTECEDENTES BUCODENTALES -->
<div class="block">
    <div class="block-title">ANTECEDENTES BUCODENTALES</div>
    <table class="data-table">
        <tr>
            <td class="label-cell">En tratamiento actual</td>
            <td>{{ $ant->en_tratamiento ? 'Sí' : 'No' }}</td>
            <td class="label-cell">Recibe tratamiento</td>
            <td>{{ $ant->recibe_tratamiento ? 'Sí' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Hemorragia en extracción</td>
            <td>{{ $ant->hemorragia_extraccion ? 'Sí' : 'No' }}</td>
            <td class="label-cell">Última visita al odontólogo</td>
            <td>{{ $ant->ultima_visita ?? 'No registra' }}</td>
        </tr>
    </table>
</div>

<!-- HIGIENE ORAL -->
<div class="block">
    <div class="block-title">HIGIENE ORAL</div>
    <table class="hygiene-table">
        <thead>
            <tr>
                <th>Uso de cepillo</th>
                <th>Uso de hilo dental</th>
                <th>Uso de enjuague</th>
                <th>Frecuencia de cepillado</th>
                <th>Sangrado al cepillarse</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $ant->cepillo ? 'Sí' : 'No' }}</td>
                <td>{{ $ant->hilo ? 'Sí' : 'No' }}</td>
                <td>{{ $ant->enjuague ? 'Sí' : 'No' }}</td>
                <td>{{ $ant->frecuencia ?? '-' }}</td>
                <td>{{ $ant->sangrado ? 'Sí' : 'No' }}</td>
            </tr>
        </tbody>
    </table>
</div>

</div>

<div class="page-break"></div>
@foreach($tratamientos as $index => $tratamiento)

@php
    $pagosSesion = $tratamiento->pagos->where('sesion_id', '!=', null);
    $adelantos = $tratamiento->pagos->where('sesion_id', null);

    $totalPagado = $tratamiento->pagos->sum('monto');
    $deuda = $tratamiento->costo_total - $totalPagado;

    $numSesiones = $tratamiento->sesiones->count();
@endphp

<div class="page">

<div class="block">

    <div class="block-title">
        TRATAMIENTO #{{ $index + 1 }} - {{ strtoupper($tratamiento->descripcion) }}
    </div>

    <!-- ================= TABLA 1: SOLO TRATAMIENTO ================= -->
    <table class="tratamiento-table">
        <tbody>

            <tr>
                <th>Descripción</th>
                <td colspan="2">{{ $tratamiento->descripcion }}</td>

                <th>Doctor</th>
                <td colspan="2">{{ $tratamiento->doctor->nombre}}</td>
            </tr>
            <tr>
                <th>Sucursal</th>
                <td colspan="2">{{ $tratamiento->sucursal->nombre}}</td>
                <th>Estado</th>
                <td colspan="2">
                    @if($tratamiento->estado == 'finalizado')
                        <span class="badge badge-success">Finalizado</span>
                    @elseif($tratamiento->estado == 'activo')
                        <span class="badge badge-info">En Proceso</span>
                    @else
                        <span class="badge badge-warning">Pendiente</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Costo Total</th>
                <td>Bs {{ number_format($tratamiento->costo_total, 2) }}</td>                
                <th>Total Pagado</th>
                <td class="text-success">
                    Bs {{ number_format($totalPagado, 2) }}
                </td>
                <th>Deuda</th>
                <td class="text-danger">
                    Bs {{ number_format($deuda, 2) }}
                </td>
            </tr>

        </tbody>
    </table>


    <!-- ================= TABLA 2: SESIONES ================= -->
    <h4 style="margin-top:20px;">Sesiones del Tratamiento (cantidad {{ $numSesiones }})</h4><br>
    <table class="tratamiento-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Pagos</th>
                <th>Firma</th>
            </tr>
        </thead>

        <tbody>

        @forelse($tratamiento->sesiones as $sesion)

        @php
            $pagadoSesion = $pagosSesion
                ->where('sesion_id', $sesion->id)
                ->sum('monto');

            $saldoSesion = ($sesion->costo ?? 0) - $pagadoSesion;
        @endphp

        <tr>
            <td>{{ $sesion->fecha_atencion }}</td>

            <td class="text-right text-success">
                Bs {{ number_format($pagadoSesion, 2) }}
            </td>

            <td class="text-center">
                @if($sesion->firma)
                    <img src="{{ public_path('storage/' . $sesion->firma) }}" class="signature-img">
                @else
                    -
                @endif
            </td>
        </tr>

        <tr>            
            <td>
                <strong>Análisis:</strong><br> {{ $sesion->analisis ?? '-' }}<br>
                <strong>Plan:</strong><br> {{ $sesion->plan_accion ?? '-' }}
            </td>
            <td></td> 
           <td>
                <h4>Materiales Usados</h4>

                <table style="
                    width:100%;
                    border-collapse: collapse;
                    border-spacing: 0;
                    margin:0;
                    padding:0;
                    font-size:10px;
                ">
                    @forelse($sesion->productos as $prod)
                    <tr>
                        <td style="padding:0; margin:0;">
                            {{ $prod->producto->nombre }}
                        </td>

                        <td style="padding:0; margin:0;">
                            {{ $prod->pivot->detalle ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td style="padding:0; margin:0;">
                            Sin materiales
                        </td>
                    </tr>
                    @endforelse
                </table>
            </td>

        </tr>

        @empty
        <tr>
            <td colspan="6" class="text-center">
                No hay sesiones registradas
            </td>
        </tr>
        @endforelse

        </tbody>
    </table>

    <table class="tratamiento-table">
        <tr>
            <td colspan="2" style="padding:2px 4px; text-align:right;">
                SUB TOTAL
            </td>
                <td style="padding:2px 4px; text-align:right; padding-right: 30%">
                Bs {{ number_format($tratamiento->costo_total, 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding:2px 4px; text-align:right;">
                PAGADO
            </td>
            <td style="padding:2px 4px; text-align:right; color:green; padding-right: 30%">
                Bs {{ number_format($tratamiento->saldo_total, 2) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding:2px 4px; text-align:right;">
                POR PAGAR
            </td>
            <td style="padding:2px 4px; text-align:right; color:red; padding-right: 30%">
                Bs {{ number_format($tratamiento->diferencia_costo, 2) }}
            </td>
        </tr>
    </table>
</div>

</div>

@if(!$loop->last)
<div class="page-break"></div>
@endif

@endforeach
<!-- FIRMA FINAL -->

<div style="text-align: center; margin-top: 30px; font-size: 8px; color: #a0aec0;">
    Documento generado por Radiance Clínica - Sistema de Gestión Odontológica
</div>

</div>

</body>
</html>