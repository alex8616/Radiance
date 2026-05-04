@extends('layouts.my-dashboard-layout')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            
            <!-- Cabecera -->
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">Panel de Control - Clínica Dental</h2>
                    <p class="text-muted">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                </div>
                <div class="col-auto">
                    <form class="form-inline">
                        <div class="form-group d-none d-lg-inline">
                            <div id="reportrange" class="px-2 py-2 text-muted">
                                <span class="small">{{ now()->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tarjetas de métricas principales -->
            <div class="row my-4">
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Pacientes Activos</small>
                                    <h3 class="card-title mb-0">{{ number_format($pacientesActivos, 0, ',', '.') }}</h3>
                                    <p class="small text-muted mb-0">
                                        <span class="fe fe-user-plus fe-12 text-success"></span>
                                        <span>+{{ $nuevosPacientesMes }} este mes</span>
                                    </p>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="fe fe-users fe-24 text-primary"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Tratamientos Pendientes</small>
                                    <h3 class="card-title mb-0">{{ number_format($tratamientosPendientes, 0, ',', '.') }}</h3>
                                    <p class="small text-muted mb-0">
                                        <span class="fe fe-alert-circle fe-12 text-warning"></span>
                                        <span>{{ $tratamientosConDeuda }} con deuda</span>
                                    </p>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="fe fe-heart fe-24 text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Doctores</small>
                                    <h3 class="card-title mb-0">{{ number_format($totalDoctores, 0, ',', '.') }}</h3>
                                    <p class="small text-muted mb-0">
                                        <span class="fe fe-user-check fe-12 text-info"></span>
                                        <span>En actividad</span>
                                    </p>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="fe fe-user-md fe-24 text-info"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Saldo en Cajas</small>
                                    <h3 class="card-title mb-0">${{ number_format(floatval($saldoTotalCajas), 0, ',', '.') }}</h3>
                                    <p class="small text-muted mb-0">
                                        <span class="fe fe-box fe-12 text-success"></span>
                                        <span>{{ $totalCajas }} cajas activas</span>
                                    </p>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="fe fe-dollar-sign fe-24 text-success"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de métricas financieras -->
            <div class="row my-4">
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Ingresos del Mes</small>
                                    <h3 class="card-title mb-0 text-success">${{ number_format(floatval($ingresosMovimientos), 0, ',', '.') }}</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="fe fe-trending-up fe-24 text-success"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Egresos del Mes</small>
                                    <h3 class="card-title mb-0 text-danger">${{ number_format(floatval($egresosMovimientos), 0, ',', '.') }}</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="fe fe-trending-down fe-24 text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Balance del Mes</small>
                                    <h3 class="card-title mb-0 text-info">${{ number_format($balanceMes, 0, ',', '.') }}</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="fe fe-pie-chart fe-24 text-info"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila con dos columnas: Tratamientos y Últimos Pacientes -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">🦷 Tratamientos más Realizados (Este Mes)</strong>
                        </div>
                        <div class="card-body">
                            @if(count($tratamientosRealizados) > 0)
                                <div class="list-group list-group-flush my-n3">
                                    @foreach($tratamientosRealizados as $tratamiento)
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <strong>{{ $tratamiento->nombre }}</strong>
                                                    <small class="text-muted"> ({{ $tratamiento->total }} tratamientos)</small>
                                                </div>
                                                <div class="col-auto">
                                                    <strong>{{ $tratamiento->porcentaje }}%</strong>
                                                    <div class="progress mt-2" style="height: 4px; width: 120px;">
                                                        <div class="progress-bar {{ $tratamiento->clase }}" role="progressbar" style="width: {{ $tratamiento->porcentaje }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted text-center py-4">No hay tratamientos registrados este mes</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">👥 Últimos Pacientes Registrados</strong>
                            <a class="float-right small text-muted" href="#">Ver todos →</a>
                        </div>
                        <div class="card-body">
                            @if(count($ultimosPacientes) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-borderless">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Paciente</th>
                                                <th>Teléfono</th>
                                                <th>Registro</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ultimosPacientes as $paciente)
                                                <tr>
                                                    <td><strong>{{ $paciente->nombre }}</strong><br>
                                                        <small class="text-muted">CI: {{ $paciente->ci }}</small>
                                                    </td>
                                                    <td>{{ $paciente->telefono }}</td>
                                                    <td>{{ $paciente->fecha_registro }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4">No hay pacientes registrados</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila de movimientos recientes de caja -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">📋 Movimientos Recientes de Caja</strong>
                            <a class="float-right small text-muted" href="#">Ver todos →</a>
                        </div>
                        <div class="card-body">
                            @if(count($movimientosRecientes) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-borderless">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th>Concepto</th>
                                                <th>Categoría</th>
                                                <th>Monto</th>
                                                <th>Método Pago</th>
                                                <th>Paciente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($movimientosRecientes as $movimiento)
                                                <tr>
                                                    <td>{{ $movimiento->fecha }}</td>
                                                    <td>
                                                        @if($movimiento->tipo == 'Ingreso')
                                                            <span class="badge badge-success">Ingreso</span>
                                                        @else
                                                            <span class="badge badge-danger">Egreso</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ Str::limit($movimiento->concepto, 50) }}</td>
                                                    <td>{{ $movimiento->categoria ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($movimiento->tipo == 'Ingreso')
                                                            <span class="text-success">+${{ number_format(floatval($movimiento->monto), 0, ',', '.') }}</span>
                                                        @else
                                                            <span class="text-danger">-${{ number_format(floatval($movimiento->monto), 0, ',', '.') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($movimiento->metodo_pago == 'efectivo')
                                                            <span class="badge badge-secondary">Efectivo</span>
                                                        @elseif($movimiento->metodo_pago == 'qr')
                                                            <span class="badge badge-info">QR</span>
                                                        @elseif($movimiento->metodo_pago == 'tarjeta')
                                                            <span class="badge badge-warning">Tarjeta</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ $movimiento->metodo_pago ?? 'N/A' }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $movimiento->paciente }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4">No hay movimientos registrados</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila de alertas -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow mb-4 border-left-warning">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Cuentas por Cobrar</small>
                                    <h4 class="mb-0 text-warning">${{ number_format(floatval($cuentasPorCobrar), 0, ',', '.') }}</h4>
                                    <p class="small text-muted mb-0 mt-1">{{ $pacientesConDeuda }} pacientes con deuda activa</p>
                                    @if($tratamientosVencidos > 0)
                                        <p class="small text-danger mb-0 mt-1">{{ $tratamientosVencidos }} tratamientos vencidos +30 días</p>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <span class="fe fe-alert-triangle fe-24 text-warning"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow mb-4 border-left-info">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Próximos Cumpleaños</small>
                                    <h4 class="mb-0 text-info">{{ count($proximosCumpleanos) }}</h4>
                                    <p class="small text-muted mb-0 mt-1">Esta semana</p>
                                    @foreach($proximosCumpleanos as $cumple)
                                        <p class="small mb-0 mt-1">🎂 {{ $cumple->nombre }} - {{ $cumple->fecha }}</p>
                                    @endforeach
                                    @if(count($proximosCumpleanos) == 0)
                                        <p class="small text-muted mt-1">No hay cumpleaños esta semana</p>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <span class="fe fe-gift fe-24 text-info"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow mb-4 border-left-success">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted mb-1">Métodos de Pago más Usados</small>
                                    @if(count($metodosPago) > 0)
                                        @foreach($metodosPago as $metodo)
                                            <p class="mb-1">
                                                <strong>{{ ucfirst($metodo->metodo_pago) }}</strong> 
                                                <span class="float-right">{{ $metodo->total }} veces</span>
                                            </p>
                                            <div class="progress mb-2" style="height: 3px;">
                                                @php
                                                    $maxTotal = $metodosPago->max('total');
                                                    $porcentaje = ($metodo->total / $maxTotal) * 100;
                                                @endphp
                                                <div class="progress-bar bg-success" style="width: {{ $porcentaje }}%"></div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Sin registros</p>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <span class="fe fe-credit-card fe-24 text-success"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas adicionales -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h3>{{ $ticketPromedio }}</h3>
                            <p class="mb-0 text-muted">Ticket Promedio</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h3>{{ $retencion }}%</h3>
                            <p class="mb-0 text-muted">Tasa de Retención</p>
                            <small class="text-muted">Pacientes que vuelven</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h3>${{ number_format($ingresoPromedioTratamiento, 0, ',', '.') }}</h3>
                            <p class="mb-0 text-muted">Ingreso Promedio por Tratamiento</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h3>{{ number_format($tratamientosPorEstado['completado'], 0, ',', '.') }}</h3>
                            <p class="mb-0 text-muted">Tratamientos Completados</p>
                            <small class="text-muted">Total histórico</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de ingresos por mes - VERSIÓN CORREGIDA -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">📊 Ingresos por Mes (Últimos 6 Meses)</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="ingresosChart" height="300" style="width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sucursal con mayor saldo -->
            @if($sucursalMayorSaldo)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow bg-success text-white">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small>🏆 Sucursal con Mayor Saldo</small>
                                    <h4 class="mb-0">{{ $sucursalMayorSaldo->nombre }}</h4>
                                    <p class="mb-0 small">
                                        Saldo total: ${{ number_format(floatval($sucursalMayorSaldo->saldo_total), 0, ',', '.') }}
                                        | Ingresos: ${{ number_format(floatval($sucursalMayorSaldo->saldo_ingreso), 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <span class="fe fe-award fe-32"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<!-- Cargar Chart.js desde CDN confiable -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM cargado, inicializando gráfico...');
        
        // Datos desde Laravel
        const ingresosData = @json($ingresosPorMes);
        console.log('Datos recibidos:', ingresosData);
        
        // Verificar si hay datos
        if (!ingresosData || ingresosData.length === 0) {
            console.log('No hay datos para el gráfico');
            return;
        }
        
        // Extraer meses y montos
        const meses = ingresosData.map(item => item.mes);
        const montos = ingresosData.map(item => parseFloat(item.monto) || 0);
        
        console.log('Meses:', meses);
        console.log('Montos:', montos);
        
        // Obtener el canvas
        const canvas = document.getElementById('ingresosChart');
        if (!canvas) {
            console.error('No se encontró el canvas');
            return;
        }
        
        // Crear nuevo gráfico (sin usar Chart.getChart)
        const ctx = canvas.getContext('2d');
        
        // Limpiar el canvas si ya tiene un gráfico
        if (canvas.chart) {
            canvas.chart.destroy();
        }
        
        // Crear nuevo gráfico
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Ingresos ($)',
                    data: montos,
                    backgroundColor: 'rgba(78, 115, 223, 0.7)',
                    borderColor: '#4e73df',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw;
                                return label + ': $' + value.toLocaleString('es-BO');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-BO');
                            }
                        },
                        title: {
                            display: true,
                            text: 'Ingresos (Bs)',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Meses',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
        
        // Guardar referencia al gráfico en el canvas
        canvas.chart = chart;
        
        console.log('Gráfico inicializado correctamente');
    });
</script>
@endsection