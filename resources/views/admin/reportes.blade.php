@extends('layouts.my-dashboard-layout')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="container-fluid mt-3">
        <!-- 🔥 TÍTULO -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 text-primary">
                <i class="fa fa-chart-line"></i> Reportes Generales
            </h4>
        </div>
        <div class="mb-3">
            <div class="card shadow p-3">
                <div class="row g-3">
                    <!-- 🔥 GRÁFICA -->
                    <div class="col-md-12">
                        <!-- FILTROS -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" id="fechaInicioGraf"
                                    value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                            </div>

                            <div class="col-md-4">
                                <label>Fecha Fin</label>
                                <input type="date" class="form-control" id="fechaFinGraf"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>

                            <div class="col-md-4">
                                <label>Sucursal</label>
                                <select class="form-control" id="sucursalGraf">
                                    <option value="">Todas</option>
                                </select>
                            </div>
                        </div>

                        <div class="row text-center" style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 15px 0; margin-bottom: 20px;">
                            <div class="col-4">
                                <small class="text-muted">Ingresos</small>
                                <h4 class="text-success mb-0" id="totalIngresos">Bs 0</h4>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Egresos</small>
                                <h4 class="text-danger mb-0" id="totalEgresos">Bs 0</h4>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Balance</small>
                                <h4 id="balanceTotal" class="mb-0">Bs 0</h4>
                            </div>
                        </div>
                        
                        <!-- GRÁFICA -->
                        <div class="card shadow-sm p-3" style="height: 400px;">
                            <canvas id="graficoIngresosEgresos" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
    let grafico;
    // 🔥 inicializar gráfico
    function initGrafico() {
        const ctx = document.getElementById('graficoIngresosEgresos').getContext('2d');

        grafico = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Ingresos',
                        data: [],
                        borderColor: '#28a745',        // 🟢 verde
                        backgroundColor: 'rgba(40,167,69,0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Egresos',
                        data: [],
                        borderColor: '#dc3545',        // 🔴 rojo
                        backgroundColor: 'rgba(220,53,69,0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#333'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#666'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#666'
                        }
                    }
                }
            }
        });
    }

    // 🔥 sucursales
    function cargarSucursalesGraf() {
        $.get('/sucursales-get', function (data) {
            let select = $('#sucursalGraf');
            select.empty();
            select.append(`<option value="">Todas</option>`);

            data.forEach(s => {
                select.append(`<option value="${s.id}">${s.nombre}</option>`);
            });
        });
    }

    // 🔥 gráfico
    function cargarGrafico() {
        let data = {
            fechaInicio: $('#fechaInicioGraf').val(),
            fechaFin: $('#fechaFinGraf').val(),
            sucursal_id: $('#sucursalGraf').val(),
            _token: '{{ csrf_token() }}'
        };

        $.post('/reporte-grafico-ingreso', data, function (resp) {

            // 🔥 gráfico
            grafico.data.labels = resp.fechas;
            grafico.data.datasets[0].data = resp.ingresos;
            grafico.data.datasets[1].data = resp.egresos;
            grafico.update();

            // 🔥 calcular totales
            let totalIngresos = resp.ingresos.reduce((a, b) => a + Number(b), 0);
            let totalEgresos = resp.egresos.reduce((a, b) => a + Number(b), 0);
            let balance = totalIngresos - totalEgresos;

            // 🔥 pintar en UI
            $('#totalIngresos').text(`Bs ${totalIngresos.toFixed(2)}`);
            $('#totalEgresos').text(`Bs ${totalEgresos.toFixed(2)}`);
            $('#balanceTotal').text(`Bs ${balance.toFixed(2)}`);

            // 🔥 color dinámico balance
            if (balance >= 0) {
                $('#balanceTotal').removeClass('text-danger').addClass('text-success');
            } else {
                $('#balanceTotal').removeClass('text-success').addClass('text-danger');
            }

        });
    }

    // 🔥 auto filtro
    $('#fechaInicioGraf, #fechaFinGraf, #sucursalGraf').on('change', function () {
        cargarGrafico();
    });

    // 🔥 init
    $(document).ready(function () {
        initGrafico();
        cargarSucursalesGraf();
        setTimeout(() => {
            cargarGrafico();
        }, 300);
    });
</script>
@endsection