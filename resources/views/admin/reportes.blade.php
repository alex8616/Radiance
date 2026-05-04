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
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="card shadow p-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <!-- FILTROS -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label>Fecha Inicio</label>
                                        <input type="date" class="form-control" id="fechaInicioSucursales"
                                            value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Fecha Fin</label>
                                        <input type="date" class="form-control" id="fechaFinoSucursales"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Sucursal</label>
                                        <select class="form-control" id="sucursalSucursales">
                                            <option value="">Todas</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- BOTÓN PDF -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <button onclick="generarPDF()" id="btnGenerarPDF" class="btn btn-danger btn-block">
                                            <i class="fas fa-file-pdf"></i> Generar PDF
                                        </button>
                                    </div>
                                </div>
                                <div class="card shadow-sm p-3" id="ViewPDFsucursales" style="height: 400px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <div class="card shadow p-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <!-- FILTROS -->
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label>Fecha Inicio</label>
                                        <input type="date" class="form-control" id="fechaInicioTratamientos"
                                            value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Fecha Fin</label>
                                        <input type="date" class="form-control" id="fechaFinTratamientos"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Accion</label>
                                        <select class="form-control" id="TratamientosSesionesFiltro">
                                            <option value="Ambos">Ambos</option>
                                            <option value="Tratamiento">Tratamiento</option>
                                            <option value="Sesion">Sesion</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Sucursal</label>
                                        <select class="form-control" id="sucursalTratamientos">
                                            <option value="">Todas</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- BOTÓN PDF -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <button onclick="generarPDFTratamientos()" id="btnGenerarPDFTratamientos" class="btn btn-danger btn-block">
                                            <i class="fas fa-file-pdf"></i> Generar PDF Tratamientos
                                        </button>
                                    </div>
                                </div>
                                <div class="card shadow-sm p-3" id="ViewPDFTratamientos" style="height: 400px;">
                                </div>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function cargarSucursales() {
        $.get('/sucursales-get', function (data) {
            let select = $('#sucursalSucursales');
            select.empty();
            select.append(`<option value="">Todas</option>`);

            data.forEach(s => {
                select.append(`<option value="${s.id}">${s.nombre}</option>`);
            });
        });
    }

    // Función para generar y mostrar PDF
    function generarPDF() {
        // Obtener valores de los filtros
        let fechaInicio = $('#fechaInicioSucursales').val();
        let fechaFin = $('#fechaFinoSucursales').val();
        let sucursalId = $('#sucursalSucursales').val();
        
        // Validar fechas
        if (!fechaInicio || !fechaFin) {
            Swal.fire('Error', 'Por favor seleccione las fechas', 'error');
            return;
        }
        
        // Mostrar loading
        Swal.fire({
            title: 'Generando PDF...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Limpiar el div antes de mostrar el nuevo PDF
        $('#ViewPDFsucursales').html('<div style="text-align: center; padding: 50px;">Cargando PDF...</div>');
        
        // Enviar petición AJAX
        $.ajax({
            url: '/reporte-pdf-sucursales-ingresos',
            method: 'POST',
            data: {
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin,
                sucursal_id: sucursalId,
                _token: '{{ csrf_token() }}'
            },
            xhrFields: {
                responseType: 'blob' // Para manejar el PDF como blob
            },
            success: function(response) {
                Swal.close();
                
                // Crear URL del blob
                let blob = new Blob([response], { type: 'application/pdf' });
                let url = URL.createObjectURL(blob);
                
                // Mostrar PDF en el div usando iframe
                $('#ViewPDFsucursales').html(`
                    <iframe 
                        src="${url}" 
                        style="width: 100%; height: 100%; border: none;"
                        frameborder="0">
                    </iframe>
                `);
                
                // Limpiar la URL del blob después de un tiempo (opcional)
                setTimeout(() => {
                    URL.revokeObjectURL(url);
                }, 10000);
                
                Swal.fire('Éxito', 'PDF generado correctamente', 'success');
            },
            error: function(xhr) {
                Swal.close();
                let errorMsg = 'Error al generar el PDF';
                try {
                    let response = JSON.parse(xhr.responseText);
                    errorMsg = response.error || errorMsg;
                } catch(e) {}
                
                $('#ViewPDFsucursales').html(`
                    <div style="text-align: center; padding: 50px; color: red;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                        <p>${errorMsg}</p>
                    </div>
                `);
                
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    }

    // Eventos de cambio en filtros
    $('#fechaInicioSucursales, #fechaFinoSucursales, #sucursalSucursales').on('change', function () {
        console.log('Filtro cambiado, recargando datos...');
    });

    $(document).ready(function () {
        cargarSucursales();
    });
</script>


<script>
    function cargarSucursalesTratamientos() {
        $.get('/sucursales-get', function (data) {
            let select = $('#sucursalTratamientos');
            select.empty();
            select.append(`<option value="">Todas</option>`);

            data.forEach(s => {
                select.append(`<option value="${s.id}">${s.nombre}</option>`);
            });
        });
    }

    // Función para generar y mostrar PDF de Tratamientos
    function generarPDFTratamientos() {
        // Obtener valores de los filtros
        let fechaInicio = $('#fechaInicioTratamientos').val();
        let fechaFin = $('#fechaFinTratamientos').val();
        let sucursalId = $('#sucursalTratamientos').val();
        let tratamientoId = $('#TratamientosSesionesFiltro').val();
        
        // Validar fechas
        if (!fechaInicio || !fechaFin) {
            Swal.fire('Error', 'Por favor seleccione las fechas', 'error');
            return;
        }
        
        // Mostrar loading
        Swal.fire({
            title: 'Generando PDF de Tratamientos...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Limpiar el div antes de mostrar el nuevo PDF
        $('#ViewPDFTratamientos').html('<div style="text-align: center; padding: 50px;">Cargando PDF...</div>');
        
        // Enviar petición AJAX
        $.ajax({
            url: '/reporte-pdf-tratamientos-sesiones',
            method: 'POST',
            data: {
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin,
                sucursal_id: sucursalId,
                accion: tratamientoId,
                _token: '{{ csrf_token() }}'
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                Swal.close();
                
                // Crear URL del blob
                let blob = new Blob([response], { type: 'application/pdf' });
                let url = URL.createObjectURL(blob);
                
                // Mostrar PDF en el div usando iframe
                $('#ViewPDFTratamientos').html(`
                    <iframe 
                        src="${url}" 
                        style="width: 100%; height: 100%; border: none;"
                        frameborder="0">
                    </iframe>
                `);
                
                // Limpiar la URL del blob después de un tiempo
                setTimeout(() => {
                    URL.revokeObjectURL(url);
                }, 10000);
                
                Swal.fire('Éxito', 'PDF de tratamientos generado correctamente', 'success');
            },
            error: function(xhr) {
                Swal.close();
                let errorMsg = 'Error al generar el PDF';
                try {
                    let response = JSON.parse(xhr.responseText);
                    errorMsg = response.error || errorMsg;
                } catch(e) {}
                
                $('#ViewPDFTratamientos').html(`
                    <div style="text-align: center; padding: 50px; color: red;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                        <p>${errorMsg}</p>
                    </div>
                `);
                
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    }

    // Eventos de cambio en filtros de tratamientos
    $('#fechaInicioTratamientos, #fechaFinTratamientos, #sucursalTratamientos').on('change', function () {
        console.log('Filtro de tratamientos cambiado');
    });

    $(document).ready(function () {
        cargarSucursalesTratamientos();
    });
</script>
@endsection