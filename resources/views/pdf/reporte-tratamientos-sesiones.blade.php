<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reporte</title>

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
        margin: 20px;
        line-height: 1.5;
    }
    
    /* Header */
    .header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #cbd5e0;
    }
    
    .header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 5px;
    }
    
    .header p {
        font-size: 10px;
        color: #718096;
        margin-top: 5px;
    }
    
    /* Secciones */
    .section {
        margin-bottom: 25px;
        page-break-inside: avoid;
    }
    
    .section-title {
        background: #2d3748;
        color: #fff;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
        border-radius: 4px;
        letter-spacing: 0.3px;
    }
    
    /* Tarjetas financieras */
    .card-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    
    .card-table td {
        border: 1px solid #e2e8f0;
        padding: 12px 10px;
        text-align: center;
        background: #fff;
        border-radius: 8px;
    }
    
    .card-table .label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #718096;
        margin-bottom: 6px;
    }
    
    .card-table .value {
        font-size: 16px;
        font-weight: bold;
    }
    
    /* Colores */
    .text-success { color: #38a169; }
    .text-danger { color: #e53e3e; }
    .text-primary { color: #3182ce; }
    .text-warning { color: #d69e2e; }
    
    /* Tablas de datos */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
    }
    
    th {
        background: #edf2f7;
        color: #2d3748;
        font-weight: 600;
        padding: 8px 6px;
        font-size: 9px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    td {
        padding: 8px 6px;
        border-bottom: 1px solid #f0f0f0;
        color: #4a5568;
    }
    
    tr:hover {
        background: #f7fafc;
    }
    
    /* Utilidades */
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    
    /* Badges */
    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 8px;
        font-weight: 600;
        text-align: center;
        min-width: 65px;
    }
    
    .badge-success {
        background: #c6f6d5;
        color: #22543d;
    }
    
    .badge-info {
        background: #bee3f8;
        color: #2c5282;
    }
    
    .badge-warning {
        background: #fefcbf;
        color: #744210;
    }
    
    /* Caja de detalle tratamiento */
    div[style*="border:1px solid #ccc"] {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 15px !important;
        margin-bottom: 20px !important;
        background: #fff !important;
        transition: box-shadow 0.2s;
    }
    
    div[style*="border:1px solid #ccc"]:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    /* Firma */
    .signature-img {
        max-width: 60px;
        max-height: 35px;
    }
    
    /* Footer */
    .footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
        font-size: 8px;
        color: #a0aec0;
    }
    
    /* Para impresión */
    @media print {
        body {
            margin: 15px;
            padding: 0;
        }
        
        .card-table td {
            print-color-adjust: exact;
        }
        
        .badge {
            print-color-adjust: exact;
        }
        
        .section {
            page-break-inside: avoid;
        }
    }
</style>

</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>Reporte de Tratamientos y Sesiones</h2>
    <p>{{ $fechaInicio }} - {{ $fechaFin }}</p>
</div>

@php
$totalIngresos = 0;
$totalPagado = 0;
$totalDeuda = 0;
@endphp

@if($accion === 'Sesion')
    @foreach($datos as $s)
        @php
            $monto = $s->pagos->sum('monto');
            $totalIngresos += $monto;
        @endphp
    @endforeach
@endif

@if($accion === 'Tratamiento' || $accion === 'Ambos')
    @foreach($datos as $t)
        @php
            $pagado = collect($t->pagos)->sum('monto');
            $totalPagado += $pagado;
            $totalDeuda += $t->saldo_total;
            $totalIngresos += $pagado;
        @endphp
    @endforeach
@endif

<!-- ================= FINANCIERO ================= -->
<table class="card-table">
    <tr>
        <td>
            <div class="label">Ingresos</div>
            <div class="value text-success">Bs {{ number_format($totalIngresos,2) }}</div>
        </td>
        <td>
            <div class="label">Pagado</div>
            <div class="value text-primary">Bs {{ number_format($totalPagado,2) }}</div>
        </td>
        <td>
            <div class="label">Deuda</div>
            <div class="value text-danger">Bs {{ number_format($totalDeuda,2) }}</div>
        </td>
    </tr>
</table>

<!-- ================= SESION ================= -->
@if($accion === 'Sesion')
<div class="section">
    <div class="section-title">Sesiones</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Tratamiento</th>
                <th>Material</th>
                <th class="text-right">Costo</th>
                <th class="text-right">Pagado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $s)
            @php
                $material = $s->productos->map(fn($p)=>$p->producto->nombre ?? '')->implode(', ');
                $monto = $s->pagos->sum('monto');
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($s->fecha_atencion)->format('d/m/Y') }}</td>
                <td>{{ $s->tratamiento->paciente->nombre ?? '' }}</td>
                <td>{{ $s->tratamiento->descripcion ?? '' }}</td>
                <td>{{ $material ?: '-' }}</td>
                <td class="text-right">Bs {{ number_format($s->costo,2) }}</td>
                <td class="text-right text-success">Bs {{ number_format($monto,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- ================= TRATAMIENTO ================= -->
@if($accion === 'Tratamiento')

@php
$count = count($datos);
$activos=0; $finalizados=0; $pendientes=0;
$totalCosto=0; $totalPagado=0; $totalSaldo=0;
@endphp

@foreach($datos as $t)
@php
$pagado = collect($t->pagos)->sum('monto');
$totalCosto += $t->costo_total;
$totalPagado += $pagado;
$totalSaldo += $t->saldo_total;

if($t->estado=='activo') $activos++;
elseif($t->estado=='finalizado') $finalizados++;
else $pendientes++;
@endphp
@endforeach

<div class="section">
    <div class="section-title">Resumen Tratamientos</div>
    <table class="card-table">
        <tr>
            <td>Total<br><b>{{ $count }}</b></td>
            <td>Finalizados<br><b>{{ $finalizados }}</b></td>
            <td>Activos<br><b>{{ $activos }}</b></td>
            <td>Pendientes<br><b>{{ $pendientes }}</b></td>
            <td>Costo<br><b>Bs {{ number_format($totalCosto,2) }}</b></td>
            <td>Pagado<br><b>Bs {{ number_format($totalPagado,2) }}</b></td>
            <td>Saldo<br><b>Bs {{ number_format($totalSaldo,2) }}</b></td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Listado</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Doctor</th>
                <th class="text-right">Costo</th>
                <th class="text-right">Pagado</th>
                <th class="text-right">Saldo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $t)
            @php $pagado = collect($t->pagos)->sum('monto'); @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->fecha_inicio)->format('d/m/Y') }}</td>
                <td>{{ $t->paciente->nombre }}</td>
                <td>{{ $t->doctor->nombre }}</td>
                <td class="text-right">Bs {{ number_format($t->costo_total,2) }}</td>
                <td class="text-right text-success">Bs {{ number_format($pagado,2) }}</td>
                <td class="text-right text-danger">Bs {{ number_format($t->saldo_total,2) }}</td>
                <td>
                    <span class="badge {{ $t->estado=='finalizado'?'badge-success':($t->estado=='activo'?'badge-info':'badge-warning') }}">
                        {{ $t->estado }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- ================= AMBOS ================= -->
@if($accion === 'Ambos')

<div class="section">
    <div class="section-title">Detalle Completo</div>

    @foreach($datos as $t)

    @php
    $pagadoT = collect($t->pagos)->sum('monto');
    $deuda = $t->costo_total - $pagadoT;
    @endphp

    <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">

        <b>Paciente:</b> {{ $t->paciente->nombre }}<br>
        <b>Doctor:</b> {{ $t->doctor->nombre }}<br>
        <b>Tratamiento:</b> {{ $t->descripcion }}<br>
        <b>Costo:</b> Bs {{ number_format($t->costo_total,2) }} |
        <b>Pagado:</b> Bs {{ number_format($pagadoT,2) }} |
        <b>Deuda:</b> Bs {{ number_format($deuda,2) }}

        <br><br>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Material</th>
                    <th class="text-right">Costo</th>
                    <th class="text-right">Pagado</th>
                    <th class="text-right">Saldo</th>
                    <th>Firma</th>
                </tr>
            </thead>
            <tbody>
                @foreach($t->sesiones as $s)
                @php
                $pagoS = collect($s->pagos ?? [])->sum('monto');
                $saldoS = ($s->costo ?? 0) - $pagoS;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($s->fecha_atencion)->format('d/m/Y') }}</td>
                    <td>
                        @foreach($s->productos as $p)
                            • {{ $p->producto->nombre ?? '' }}<br>
                        @endforeach
                    </td>
                    <td class="text-right">Bs {{ number_format($s->costo,2) }}</td>
                    <td class="text-right text-success">Bs {{ number_format($pagoS,2) }}</td>
                    <td class="text-right text-danger">Bs {{ number_format($saldoS,2) }}</td>
                    <td>
                        @if($s->firma)
                        <img src="{{ public_path('storage/'.$s->firma) }}" class="signature-img">
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>
@endif

</body>
</html>