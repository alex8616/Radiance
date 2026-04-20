@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="efectivo-tab" data-toggle="tab" href="#efectivo" role="tab" aria-controls="efectivo" aria-selected="true">EFECTIVO</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="deposito-qr-tab" data-toggle="tab" href="#deposito-qr" role="tab" aria-controls="deposito-qr" aria-selected="false">DEPOSITO/QR</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tarjeta-tab" data-toggle="tab" href="#tarjeta" role="tab" aria-controls="tarjeta" aria-selected="false">TARJETA</a>
            </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="efectivo" role="tabpanel" aria-labelledby="efectivo-tab">
                    <div class="row">
                        <div class="col-12 col-sm-8">
                            <div class="card">
                                <div class="card-header" style="width: 100%; background-color: #1d2736">
                                    <div class="row" style="width: 100%;">
                                        <div class="col-12 col-sm-8">
                                            <h3 class="card-title" style="color: white; font-weight: bold;">Efectivo</h3>
                                        </div>
                                        <div class="col-12 col-sm-4" style="text-align: right;">
                                            <button id="addcajaEfectivos" class="btn btn-primary">
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="row">
                                            <div class="col-12 col-sm-12" style="width: 100%; background: #F5F7F8; padding-top: 10px;">
                                                <div class="row" style="background: #F5F7F8;">
                                                    <div class="col-md-11">
                                                        <div class="row" style="padding-bottom: 10px">
                                                            <div class="col-md-3">
                                                                <select name="DateCajaEfectivo" id="DateCajaEfectivo" class="form-control">
                                                                    <option value="DiarioCajaEfectivo">Diario</option>
                                                                    <option value="MensualidadCajaEfectivo">Todo El Mes</option>
                                                                    <option value="RangoCajaEfectivo">Rango</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2" id="DiaCajaEfectivoContainer">
                                                                <select name="DiaCajaEfectivo" id="DiaCajaEfectivo" class="form-control"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="MesCajaEfectivoContainer">
                                                                <select name="MesCajaEfectivo" id="MesCajaEfectivo" class="form-control"><option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option><option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option><option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option><option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="AnioCajaEfectivoContainer">
                                                                <select name="AnioCajaEfectivo" id="AnioCajaEfectivo" class="form-control"><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="FechaInicioContainerCajaEfectivo" style="display: none;">
                                                                <input type="date" name="fechaInicioCajaEfectivo" id="fechaInicioCajaEfectivo" class="form-control" min="2026-04-01" max="2026-04-30">
                                                            </div>
                                                            <div class="col-md-3" id="FechaFinContainerCajaEfectivo" style="display: none;">
                                                                <input type="date" name="fechaFinCajaEfectivo" id="fechaFinCajaEfectivo" class="form-control" min="2026-04-01" max="2026-04-30">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12" style="width: 100%; margin:0; border:1px solid #E6E6E6; border-top:none;">
                                                <div class="row">
                                                    <div class="col-md-12" style="border-top:2px solid #E6E6E6;">
                                                        <div class="row pt-2">
                                                            <!-- Ingreso -->
                                                            <div class="col-md-4" style="border-right:2px solid #E6E6E6; border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">INGRESO <br>
                                                                    <span id="IngresoEfectivo" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                            <!-- Egreso -->
                                                            <div class="col-md-4" style="border-right:2px solid #E6E6E6; border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">EGRESO <br>
                                                                    <span id="EgresoEfectivo" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                            <!-- Total -->
                                                            <div class="col-md-4" style="border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">TOTAL <br>
                                                                    <span id="TotalEfectivo" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12" style="width: 100%; margin:0; border:1px solid #E6E6E6; border-top:none;">
                                                <div class="table-responsive" style="overflow-y: scroll; max-height: 500px">
                                                    <table class="table table-vcenter card-table" id="tabla-caja-Efectivo">
                                                        <thead style="position: sticky; top: 0; z-index: 1;">
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>Sucursal</th>
                                                                <th>Categoria</th>
                                                                <th>Descripcion</th>
                                                                <th>Fecha</th>
                                                                <th>Ingreso</th>
                                                                <th>Egreso</th>
                                                                <th>Saldo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Se llena dinámicamente -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="card" id="detalleMovimientoCajaEfectivo">
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="deposito-qr" role="tabpanel" aria-labelledby="deposito-qr-tab"> 
                    deposito/qr
                </div>
                <div class="tab-pane fade" id="tarjeta" role="tabpanel" aria-labelledby="tarjeta-tab"> 
                    tarjeta
                </div>
            </div>
        </div>
    </div>    
</div>
<style>
    #tabla-caja-Efectivo thead {
        position: sticky;
        top: 0;
        z-index: 2;
        background-color: #f8f9fa; /* gris claro Bootstrap */
    }

    .selected-row {
        background-color: #F9E7B2 !important; /* azul claro */
    }

</style>
<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    $(document).ready(function() { 
        FechaSelectCajaEfectivo()
        InputRangoFechasControl()
        
        document.getElementById('addcajaEfectivos').addEventListener('click', function() {
            var detalleDiv = document.getElementById('detalleMovimientoCajaEfectivo');

            let formHtml = `
                <form id="form-register-egreso">
                    <div class="card-header" style="background: rgb(24, 36, 51); color: white">
                        <div class="row" style="width: 100%;">
                            <div class="col-12 col-sm-8">
                                <h4 class="card-title" style="color: white">REGISTRAR</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Categoría</label>
                            <select id="categoriaEgreso" class="form-control" style="width: 100%;">
                                <option value="Gasto">Gasto</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Descripción</label>
                            <textarea id="descripcionEgreso" rows="3" class="form-control convertmayusculas"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Monto</label>
                            <input type="number" id="montoEgreso" class="form-control" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex" style="text-align: right">
                            <button type="button" class="btn me-auto" id="btn-cancelar-egreso">CANCELAR</button>
                            <button type="button" class="btn btn-primary" id="btn-registrar-egreso">GUARDAR</button>
                        </div>
                    </div>
                </form>
            `;

            detalleDiv.innerHTML = formHtml;

            // Acción de cancelar
            document.getElementById('btn-cancelar-egreso').addEventListener('click', function() {
                detalleDiv.innerHTML = '';
            });

            // Acción de guardar
            $('#btn-registrar-egreso').on('click', function() {
                let egreso = {
                    categoria: $('#categoriaEgreso').val(),
                    descripcion: $('#descripcionEgreso').val(),
                    monto: $('#montoEgreso').val(),
                    metodo_pago: $('#metodoPagoEgreso').val(),
                    factura: $('#facturaEgreso').length ? $('#facturaEgreso').val() : null,
                    tipo: 'Egreso',
                    fecha: new Date().toISOString().slice(0,10) // YYYY-MM-DD
                };

                $.ajax({
                    url: '/movimientos-caja/egreso',
                    type: 'POST',
                    data: egreso,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        console.log("Guardado correctamente:", data);
                        $('#detalleMovimientoCajaEfectivo').html('');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al guardar egreso:", xhr.responseText);
                        alert('Error al registrar el egreso');
                    }
                });
            });
        });

        
        function MostrarCajaEfectivo(response) {
            var tbody = $('#tabla-caja-Efectivo tbody');
            tbody.empty();

            // Actualizar totales generales
            $('#IngresoEfectivo').text(response.IngresoEfectivo);
            $('#EgresoEfectivo').text(response.EgresoEfectivo);
            $('#TotalEfectivo').text(
                (parseFloat(response.IngresoEfectivo) - parseFloat(response.EgresoEfectivo)).toFixed(2)
            );

            let saldoAcumulado = 0; // inicializamos el saldo acumulado

            // Recorrer movimientos de caja
            response.CajaEfectivo.forEach(function(item, index) {
                let colorBorde = item.tipo === "Ingreso" ? "green" : "red";
                let ingresoColor = item.tipo === "Ingreso" ? "green" : "#2C3333";
                let egresoColor = item.tipo === "Egreso" ? "red" : "#2C3333";

                // actualizar saldo acumulado
                saldoAcumulado += (item.tipo === "Ingreso" ? parseFloat(item.monto) : 0) 
                                - (item.tipo === "Egreso" ? parseFloat(item.monto) : 0);

                let row = `
                    <tr class="clickable-row" 
                        data-id="${item.id}" 
                        data-categoria="${item.categoria}" 
                        data-descripcion="${item.descripcion}" 
                        data-fecha="${item.fecha}" 
                        data-monto="${item.monto}" 
                        data-metodo="${item.metodo_pago}" 
                        data-sucursal="${item.caja.sucursal.nombre}"
                        style="border-left: 3px solid ${colorBorde}">
                        <td>${index + 1}</td>
                        <td>${item.caja.sucursal.nombre}</td>
                        <td>${item.categoria}</td>
                        <td>${item.descripcion ?? ''}</td>
                        <td>${item.fecha}</td>
                        <td style="font-weight:${item.tipo === "Ingreso" ? 'bold' : 'normal'}; color:${ingresoColor}">
                            ${item.tipo === "Ingreso" ? item.monto : ''}
                        </td>
                        <td style="font-weight:${item.tipo === "Egreso" ? 'bold' : 'normal'}; color:${egresoColor}">
                            ${item.tipo === "Egreso" ? item.monto : ''}
                        </td>
                        <td style="background:#40A2E3; color:white; font-weight:bold; text-align:center">
                            ${saldoAcumulado.toFixed(2)}
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });

            // Evento click en filas
            $('#tabla-caja-Efectivo tbody').on('click', '.clickable-row', function() {
                $('#tabla-caja-Efectivo tbody tr').removeClass('selected-row');
                $(this).addClass('selected-row');

                // Recuperar el objeto original desde response usando el id
                let movimiento = response.CajaEfectivo.find(m => m.id == $(this).data('id'));

                // Validar que exista
                if (!movimiento) {
                    console.warn("Movimiento no encontrado para la fila seleccionada");
                    return;
                }

                let tipoProceso = movimiento.tipo || 'No definido';
                let colorProceso = (tipoProceso === 'Ingreso') ? 'green' : (tipoProceso === 'Egreso' ? 'red' : '#686D76');

                let detalle = `
                    <div class="card-header" style="background: rgb(24, 36, 51); color: white">
                        <div class="row" style="width: 100%;">
                            <div class="col-12 col-sm-8">
                                <h4 class="card-title" style="color:white">INFORMACION DETALLE</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">SUCURSAL:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data('sucursal')}</label></div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">FECHA REGISTRO:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data('fecha')}</label></div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">CATEGORIA:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data('categoria')}</label></div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">DESCRIPCION:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data('descripcion')}</label></div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">TIPO DE PROCESO:</label>
                            <div class="col">
                                <label class="col-8 col-form-label" 
                                    style="font-weight:bold; font-size:16px; color:${colorProceso}">
                                    ${tipoProceso}
                                </label>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">MONTO:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data('monto')}</label></div>
                        </div>
                    </div>
                `;

                // Si tiene tratamiento, mostrar datos adicionales
                if (movimiento && movimiento.tratamiento) {
                    detalle += `
                        <div class="card shadow mt-3">
                            <div class="card-header" id="headingTratamiento">
                                <a role="button" href="#collapseTratamiento${movimiento.id}" 
                                data-toggle="collapse" data-target="#collapseTratamiento${movimiento.id}" 
                                aria-expanded="false" aria-controls="collapseTratamiento${movimiento.id}">
                                <strong>Datos del Tratamiento</strong>
                                </a>
                            </div>
                            <div id="collapseTratamiento${movimiento.id}" class="collapse" aria-labelledby="headingTratamiento" data-parent="#detalleMovimientoCajaEfectivo">
                                <div class="card-body">

                                    <!-- Información general -->
                                    <table class="table table-sm table-striped">
                                        <tbody>
                                            <tr><th>Descripción</th><td>${movimiento.tratamiento.descripcion}</td></tr>
                                            <tr><th>Estado</th><td>${movimiento.tratamiento.estado}</td></tr>
                                            <tr><th>Fechas</th><td>Inicio: ${movimiento.tratamiento.fecha_inicio}<br>Fin estimada: ${movimiento.tratamiento.fecha_fin_estimada}</td></tr>
                                            <tr><th>Costos</th><td>Total: ${movimiento.tratamiento.costo_total}<br>Diferencia: ${movimiento.tratamiento.diferencia_costo}<br>Saldo: ${movimiento.tratamiento.saldo_total}</td></tr>
                                        </tbody>
                                    </table>

                                    <!-- Paciente -->
                                    <h6 class="bg-dark text-white p-2 mt-3">Paciente</h6>
                                    <p>${movimiento.tratamiento.paciente.nombre} ${movimiento.tratamiento.paciente.apellido_paterno} ${movimiento.tratamiento.paciente.apellido_materno} - CI: ${movimiento.tratamiento.paciente.ci} - Tel: ${movimiento.tratamiento.paciente.telefono}</p>

                                    <!-- Doctor -->
                                    <h6 class="bg-dark text-white p-2 mt-3">Doctor</h6>
                                    <p>${movimiento.tratamiento.doctor.nombre} (${movimiento.tratamiento.doctor.especialidad}) - Tel: ${movimiento.tratamiento.doctor.telefono}</p>

                                    <!-- Sesiones -->
                                    <h6 class="bg-dark text-white p-2 mt-3">Sesiones</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead><tr><th>#</th><th>Fecha Atención</th><th>Análisis</th><th>Plan Acción</th></tr></thead>
                                        <tbody>
                                            ${movimiento.tratamiento.sesiones.map((s,i)=>`
                                                <tr><td>${i+1}</td><td>${s.fecha_atencion}</td><td>${s.analisis}</td><td>${s.plan_accion}</td></tr>
                                            `).join('')}
                                        </tbody>
                                    </table>

                                    <!-- Pagos -->
                                    <h6 class="bg-dark text-white p-2 mt-3">Pagos</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead><tr><th>#</th><th>Fecha</th><th>Monto</th><th>Método</th></tr></thead>
                                        <tbody>
                                            ${movimiento.tratamiento.pagos.map((p,i)=>`
                                                <tr><td>${i+1}</td><td>${p.fecha}</td><td>${p.monto}</td><td>${p.metodo_pago}</td></tr>
                                            `).join('')}
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    `;
                }

                detalle += `</div>`; // cerrar card-body

                $('#detalleMovimientoCajaEfectivo').html(detalle);
            });


        }


        function FechaSelectCajaEfectivo() {
            var today = new Date();
            var currentDay = today.getDate();
            var currentMonth = today.getMonth();
            var currentYear = today.getFullYear();
            var monthsOfYear = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

            $('#MesCajaEfectivo').empty();
            $('#AnioCajaEfectivo').empty();

            for (var month = 0; month < 12; month++) {
                $('#MesCajaEfectivo').append('<option value="' + (month + 1) + '">' + monthsOfYear[month] + '</option>');
            }
            $('#MesCajaEfectivo').val(currentMonth + 1);

            var startYear = currentYear - 6;
            var endYear = currentYear;
            for (var year = startYear; year <= endYear; year++) {
                $('#AnioCajaEfectivo').append('<option value="' + year + '">' + year + '</option>');
            }
            $('#AnioCajaEfectivo').val(currentYear);
            
            function updateDaySelectorNovedad() {
                var selectedMonth = parseInt($('#MesCajaEfectivo').val());
                var selectedYear = parseInt($('#AnioVenta').val());

                var selectedYear = today.getFullYear();
                var daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
                $('#DiaCajaEfectivo').empty();
                for (var day = 1; day <= daysInMonth; day++) {
                    $('#DiaCajaEfectivo').append('<option value="' + day + '">' + day + '</option>');
                }
                if (currentDay > daysInMonth) {
                    $('#DiaCajaEfectivo').val(daysInMonth);
                } else {
                    $('#DiaCajaEfectivo').val(currentDay);
                }
            }

            updateDaySelectorNovedad();

            $('#DateCajaEfectivo').on('change', function() {
                var selectedValue = $(this).val();
                switch (selectedValue) {
                    case 'DiarioCajaEfectivo':
                        $('#DiaCajaEfectivoContainer').show();
                        $('#AnioCajaEfectivoContainer').show();
                        $('#FechaInicioContainerCajaEfectivo').hide();
                        $('#FechaFinContainerCajaEfectivo').hide();
                        break;
                    case 'MensualidadCajaEfectivo':
                        $('#MesCajaEfectivoContainer').show();
                        $('#AnioCajaEfectivoContainer').show();
                        $('#DiaCajaEfectivoContainer').hide();
                        $('#FechaInicioContainerCajaEfectivo').hide();
                        $('#FechaFinContainerCajaEfectivo').hide();
                    break;
                    case 'RangoCajaEfectivo':
                        $('#DiaCajaEfectivoContainer').hide();
                        $('#MesCajaEfectivoContainer').hide();
                        $('#AnioCajaEfectivoContainer').hide();
                        $('#FechaInicioContainerCajaEfectivo').show();
                        $('#FechaFinContainerCajaEfectivo').show();
                    break;
                }
                FiltrarDatosCajaEfectivo();
            });


            $('#MesCajaEfectivo').on('change', function() {
                updateDaySelectorNovedad();
                FiltrarDatosCajaEfectivo();
            });

            $('#AnioCajaEfectivo').on('change', function() {
                FiltrarDatosCajaEfectivo();
            });

            $('#DiaCajaEfectivo').on('change', function() {
                FiltrarDatosCajaEfectivo();
            });

            $('#FechaInicioContainerCajaEfectivo').on('change', function() {
                FiltrarDatosCajaEfectivo();
            });

            $('#FechaFinContainerCajaEfectivo').on('change', function() {
                FiltrarDatosCajaEfectivo();
            });

            $('#DateCajaEfectivo').trigger('change');

        }

        function FiltrarDatosCajaEfectivo(){
            var today = new Date();
            var selectedYear = $('#AnioCajaEfectivo').val();
            var tipoFiltro = $('#DateCajaEfectivo').val();
            var dia = $('#DiaCajaEfectivo').val();
            var mes = $('#MesCajaEfectivo').val();
            var anio = selectedYear;
            var fechaInicio = $('#fechaInicioCajaEfectivo').val();
            var fechaFin = $('#fechaFinCajaEfectivo').val();

            $.ajax({
                url: '/filtrar-datos-caja-Efectivo',
                method: 'GET',
                data: {
                    tipoFiltro: tipoFiltro,
                    dia: dia,
                    mes: mes,
                    anio: anio,
                    fechaInicio: fechaInicio,
                    fechaFin: fechaFin,
                },
                success: function(response) {
                    MostrarCajaEfectivo(response)
                },
                error: function(error) {
                    console.error('Error al filtrar datos:', error);
                }
            });
        }

        function InputRangoFechasControl(){
            var today = new Date();
            var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            var formatDate = function(date) {
                var day = ('0' + date.getDate()).slice(-2);
                var month = ('0' + (date.getMonth() + 1)).slice(-2);
                return date.getFullYear() + '-' + month + '-' + day;
            }
            var fechaInicioCajaEfectivo = document.getElementById('fechaInicioCajaEfectivo');
            var fechaFinCajaEfectivo = document.getElementById('fechaFinCajaEfectivo');
            fechaInicioCajaEfectivo.min = formatDate(firstDay);
            fechaInicioCajaEfectivo.max = formatDate(lastDay);
            fechaFinCajaEfectivo.min = formatDate(firstDay);
            fechaFinCajaEfectivo.max = formatDate(lastDay);
            fechaInicioCajaEfectivo.value = formatDate(today);
            fechaFinCajaEfectivo.value = formatDate(today);
        }
        
    });
</script>
@endsection

