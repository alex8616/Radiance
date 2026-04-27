<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Historia Clínica</title>

<style>
body{
    font-family: DejaVu Sans, Arial;
    font-size: 11px;
    color:#222;
}

.page{
    padding:10px;
}

/* HEADER */
.header{
    width:100%;
    border-bottom:2px solid #000;
    margin-bottom:10px;
    padding-bottom:6px;
}

.header-table{width:100%;}
.logo{width:90px;}

.clinic{
    text-align:right;
    font-size:11px;
}

/* TITULO */
.title{
    text-align:center;
    font-size:15px;
    font-weight:bold;
    margin:10px 0;
}

/* BLOQUES */
.block{
    margin-bottom:10px;
    border:1px solid #ddd;
    padding:6px;
}

.block-title{
    font-weight:bold;
    font-size:11px;
    border-bottom:1px solid #ddd;
    margin-bottom:6px;
    padding-bottom:3px;
}

/* TABLAS */
.table{
    width:100%;
    border-collapse:collapse;
    font-size:10.5px;
}

.table td,.table th{
    border:1px solid #eee;
    padding:5px;
}

/* PAGE BREAK */
.page-break{
    page-break-after:always;
}

/* STATUS */
.ok{color:green;font-weight:bold;}
.no{color:red;font-weight:bold;}

.badge{
    padding:2px 6px;
    border-radius:3px;
    background:#eee;
    font-size:10px;
}
</style>
</head>

<body>

@php
    $pac = $paciente;
    $ant = optional($paciente->antecedenteMedico);
@endphp

<!-- ===================== HOJA 1 ===================== -->
<div class="page">

<!-- HEADER -->
<div class="header">
<table class="header-table">
<tr>
<td><img src="{{ public_path('imagen/logo_sin_fondo.png') }}" class="logo"></td>
<td class="clinic">
<strong>Radiance Clínica</strong><br>
{{ now()->format('d/m/Y') }}
</td>
</tr>
</table>
</div>

<div class="title">HISTORIA CLÍNICA DEL PACIENTE</div>

<!-- PACIENTE -->
<div class="block">
<div class="block-title">DATOS DEL PACIENTE</div>

<table class="table">
<tr>
<td><b>Nombre</b><br>{{ $pac->nombre }} {{ $pac->apellido_paterno }} {{ $pac->apellido_materno }}</td>
<td><b>CI</b><br>{{ $pac->ci }}</td>
<td><b>Sexo</b><br>{{ $pac->sexo }}</td>
</tr>

<tr>
<td><b>Fecha Nac.</b><br>{{ $pac->fecha_nacimiento }}</td>
<td><b>Teléfono</b><br>{{ $pac->telefono }}</td>
<td><b>Estado Civil</b><br>{{ $pac->estado_civil }}</td>
</tr>
</table>
</div>

<!-- ANTECEDENTES GENERALES -->
<div class="block">
<div class="block-title">ANTECEDENTES GENERALES</div>

<table class="table">
<tr>
<td><b>Alergias</b><br>{{ $ant->alergias ?? '-' }}</td>
<td><b>Familiares</b><br>{{ $ant->antecedentes_familiares ?? '-' }}</td>
<td><b>Otros</b><br>{{ $ant->otros ?? '-' }}</td>
</tr>
</table>
</div>

<!-- PATOLOGICOS -->
<div class="block">
<div class="block-title">ANTECEDENTES PATOLÓGICOS</div>

Anemia: {!! $ant->anemia ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!} |
Asma: {!! $ant->asma ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!} |
Cardiopatías: {!! $ant->cardiopatias ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!} |
Diabetes: {!! $ant->diabetes ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!} |
VIH: {!! $ant->vih ? '<span class="ok">Sí</span>' : '<span class="no">No</span>' !!}
</div>

<!-- BUCODENTALES -->
<div class="block">
<div class="block-title">ANTECEDENTES BUCODENTALES</div>

En tratamiento: {{ $ant->en_tratamiento ? 'Sí' : 'No' }} |
Recibe tratamiento: {{ $ant->recibe_tratamiento ? 'Sí' : 'No' }} |
Hemorragia: {{ $ant->hemorragia_extraccion ? 'Sí' : 'No' }} |
Última visita: {{ $ant->ultima_visita ?? '-' }}
</div>

<!-- HIGIENE ORAL -->
<div class="block">
<div class="block-title">HIGIENE ORAL</div>

Cepillo: {{ $ant->cepillo ? 'Sí' : 'No' }} |
Hilo: {{ $ant->hilo ? 'Sí' : 'No' }} |
Enjuague: {{ $ant->enjuague ? 'Sí' : 'No' }} |
Frecuencia: {{ $ant->frecuencia ?? '-' }} |
Sangrado: {{ $ant->sangrado ? 'Sí' : 'No' }}
</div>

</div>

<div class="page-break"></div>

<!-- ===================== HOJAS TRATAMIENTOS ===================== -->

@foreach($tratamientos as $tratamiento)

@php
    $estado = $tratamiento->estado;
@endphp

<div class="page">

<div class="block">
<div class="block-title">TRATAMIENTO</div>

<b>Estado:</b>
<span class="badge">
    {{ strtoupper($estado) }}
</span><br><br>

<b>Categoría:</b> {{ $tratamiento->categoria->nombre ?? '-' }}<br>
<b>Descripción:</b> {{ $tratamiento->descripcion }}<br>
<b>Sucursal:</b> {{ $tratamiento->sucursal->nombre ?? '-' }}<br>
<b>Dirección:</b> {{ $tratamiento->sucursal->direccion ?? '-' }}<br>

<br>

<b>Fechas:</b> {{ $tratamiento->fecha_inicio }} → {{ $tratamiento->fecha_fin_estimada }}
</div>

<!-- SESIONES -->
<div class="block">
<div class="block-title">SESIONES</div>

<table class="table">
<tr>
<th>Fecha</th>
<th>Análisis</th>
<th>Plan</th>
</tr>

@foreach($tratamiento->sesiones as $s)
<tr>
<td>{{ $s->fecha_atencion }}</td>
<td>{{ $s->analisis ?? '-' }}</td>
<td>{{ $s->plan_accion ?? '-' }}</td>
</tr>
@endforeach

</table>

</div>

</div>

<div class="page-break"></div>

@endforeach

</body>
</html>