<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF Paciente</title>
    <style>
        .tabla {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .tabla th {
            border-bottom: 2px solid #000;
            padding: 6px;
            text-align: left;
            font-weight: bold;
        }

        .tabla td {
            border-bottom: 1px solid #ccc;
            padding: 6px;
            vertical-align: top;
        }

        .fila-par {
            background: #EEEEEE;
        }

        .analisis-box, .materiales-box {
            font-size: 11.5px;
            line-height: 1.4;
        }

        .firma-img {
            height: 35px;
        }

        .sin-firma {
            color: #999;
            font-style: italic;
            text-align: center;
        }

        .pago-item {
            display: block;
            margin-bottom: 2px;
        }

        .titulo-seccion {
            margin-top: 15px;
            margin-bottom: 5px;
            font-size: 14px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
    </style>
</head>

<body>

@php
    $totalPagado = $tratamiento->saldo_total;
    $saldo = $tratamiento->diferencia_costo;
    $costo = $tratamiento->costo_total;

    // 🔥 Adelantos (pagos sin sesión)
    $adelantos = $tratamiento->pagos->whereNull('sesion_id');
    $totalAdelantos = $adelantos->sum('monto');
@endphp

<h3 class="titulo-seccion">
    Historial de Sesiones - {{ $tratamiento->categoria->nombre ?? 'Tratamiento' }} 
    ({{ $tratamiento->descripcion }})
</h3>

<table class="tabla">
    <thead>
        <tr>
            <th style="width: 20%;">Fecha</th>
            <th style="width: 40%;">Pagos</th>
            <th style="width: 20%;">Firma</th>
        </tr>
    </thead>
    <tbody>

    @foreach($tratamiento->sesiones->sortBy('fecha_atencion')->values() as $index => $sesion)

        @php
            $pagos = $tratamiento->pagos->where('sesion_id', $sesion->id);
        @endphp

        <!-- FILA PRINCIPAL -->
        <tr class="{{ $index % 2 == 0 ? 'fila-par' : '' }}">
            <td>{{ $sesion->fecha_atencion }}</td>

            <td>
                @if($pagos->count())
                    @foreach($pagos as $p)
                        <span class="pago-item">
                            Bs {{ number_format($p->monto, 2) }} ({{ $p->metodo_pago }})
                        </span>
                    @endforeach
                @else
                    <span style="color:#999;">Sin pagos</span>
                @endif
            </td>

            <td style="text-align:center;">
                @if($sesion->firma)
                    <img src="{{ public_path('storage/' . $sesion->firma) }}" class="firma-img">
                @else
                    <div class="sin-firma">Sin firma</div>
                @endif
            </td>
        </tr>

        <!-- DETALLE -->
        <tr class="{{ $index % 2 == 0 ? 'fila-par' : '' }}">
            
            <!-- ANALISIS -->
            <td colspan="2" class="analisis-box">
                <strong>Análisis:</strong><br>
                {{ $sesion->analisis ?? 'No registrado' }} <br><br>

                <strong>Plan:</strong><br>
                {{ $sesion->plan_accion ?? 'No registrado' }}
            </td>

            <!-- MATERIALES -->
            <td class="materiales-box">
                <strong>Materiales:</strong><br>

                @if($sesion->productos->count())
                    @foreach($sesion->productos as $prod)
                        <div>
                            - {{ $prod->producto->nombre }}  
                            ({{ $prod->pivot->detalle ?? '-' }})
                        </div>
                    @endforeach
                @else
                    <span style="color:#999;">Sin materiales</span>
                @endif
            </td>

        </tr>

    @endforeach

    </tbody>
</table>

<!-- ADELANTOS -->
@if($adelantos->count())

<div style="margin-top: 10px;">

    <h4 style="font-size: 12px; margin-bottom: 5px;">
        Adelantos del Tratamiento
    </h4>

    <table style="width: 60%; font-size: 12px; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border-bottom:1px solid #000; text-align:left;">Fecha</th>
                <th style="border-bottom:1px solid #000; text-align:left;">Método</th>
                <th style="border-bottom:1px solid #000; text-align:right;">Monto</th>
            </tr>
        </thead>
        <tbody>

            @foreach($adelantos as $a)
                <tr>
                    <td style="padding:4px;">{{ $a->fecha }}</td>
                    <td style="padding:4px;">{{ $a->metodo_pago }}</td>
                    <td style="padding:4px; text-align:right;">
                        Bs {{ number_format($a->monto, 2) }}
                    </td>
                </tr>
            @endforeach

            <!-- TOTAL ADELANTOS -->
            <tr>
                <td colspan="2" style="padding:4px; text-align:right;">
                    <strong>Total Adelantos:</strong>
                </td>
                <td style="padding:4px; text-align:right;">
                    <strong>Bs {{ number_format($totalAdelantos, 2) }}</strong>
                </td>
            </tr>

        </tbody>
    </table>

</div>

@endif

<!-- RESUMEN FINAL -->
<div style="margin-top: 15px; width: 100%;">

    <table style="width: 40%; margin-left: auto; font-size: 12px; border-collapse: collapse;">
        <tr>
            <td style="padding: 5px;"><strong>Costo Total:</strong></td>
            <td style="padding: 5px; text-align: right;">
                Bs {{ number_format($costo, 2) }}
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;"><strong>Total Pagado:</strong></td>
            <td style="padding: 5px; text-align: right;">
                Bs {{ number_format($totalPagado, 2) }}
            </td>
        </tr>

        <tr>
            <td style="padding: 5px; border-top: 1px solid #000;">
                <strong>Saldo Pendiente:</strong>
            </td>
            <td style="padding: 5px; text-align: right; border-top: 1px solid #000;">
                Bs {{ number_format($saldo, 2) }}
            </td>
        </tr>
    </table>

</div>

</body>
</html>