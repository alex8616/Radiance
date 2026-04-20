@extends('layouts.my-dashboard-layout')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="card shadow p-3">
        <h4 class="mb-3">Calendario</h4>

        <div id="calendar"></div>
    </div>
</div>

<!-- 🔥 MODAL -->
<div class="modal fade bd-example-modal-xl" id="tratamientoModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="varyModalLabel">Informacion Completo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
<style>
    .firma-img {
        max-height: 80px;       /* tamaño normal */
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        cursor: pointer;
    }

    .firma-img:hover {
        transform: scale(4);    /* se agranda al pasar el mouse */
        z-index: 10;            /* se pone encima de otros elementos */
        position: relative;
        background-color: white; /* fondo blanco */
        padding: 5px;            /* espacio alrededor de la imagen */
        box-shadow: 0 4px 12px rgba(0,0,0,0.3); /* sombra elegante */
        border-radius: 4px;      /* esquinas redondeadas */
    }

</style>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            themeSystem: 'bootstrap5',

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                let eventos = [];
                // 🔥 1. TRATAMIENTOS
                $.ajax({
                    url: '/tratamientos-get',
                    method: 'GET',
                    success: function(resp) {

                        resp.tratamientos.forEach(t => {
                            let paciente = t.paciente;
                            let nombreCompleto = paciente
                                ? `${paciente.nombre} ${paciente.apellido_paterno} ${paciente.apellido_materno}`
                                : 'Paciente';

                            eventos.push({
                                id: 't_' + t.id,
                                title: `${t.descripcion} - ${nombreCompleto}`,
                                start: t.fecha_inicio,
                                allDay: true,
                                color: t.estado === 'activo' ? '#28a745' : '#6c757d',

                                extendedProps: {
                                    tipo: 'tratamiento',
                                    tratamiento_id: t.id,   // 👈 guardamos el ID aquí
                                    paciente_id: t.paciente_id,
                                    paciente: paciente,
                                    categoria: t.categoria ? t.categoria.nombre : null
                                }
                            });
                        });

                        // 🔥 2. SESIONES
                        $.ajax({
                            url: '/sesiones-get',
                            method: 'GET',
                            success: function(resp2) {

                                resp2.forEach(s => {
                                    let paciente = s.tratamiento?.paciente;

                                    let nombreCompleto = paciente
                                        ? `${paciente.nombre} ${paciente.apellido_paterno} ${paciente.apellido_materno}`
                                        : 'Paciente';

                                    let analisis = (s.analisis || '').substring(0, 40);

                                    eventos.push({
                                        id: 's_' + s.id,
                                        title: `${nombreCompleto} - ${analisis}`,
                                        start: s.fecha_atencion,
                                        allDay: true,
                                        color: '#007bff',

                                        extendedProps: {
                                            tipo: 'sesion',
                                            tratamiento_id: s.tratamiento_id, // 👈 también aquí
                                            firma: s.firma,
                                            paciente: paciente,
                                            analisis: s.analisis
                                        }
                                    });
                                });

                                // 🔥 devolver todos los eventos
                                successCallback(eventos);
                            },
                            error: function(err) {
                                console.error('Error sesiones:', err);
                                failureCallback(err);
                            }
                        });

                    },
                    error: function(err) {
                        console.error('Error tratamientos:', err);
                        failureCallback(err);
                    }
                });
            },

            // 🔥 CLICK EN EVENTO
            eventClick: function(info) {
                let tratamientoId = info.event.extendedProps.tratamiento_id;

                $.ajax({
                    url: '/tratamientos-select/' + tratamientoId,
                    method: 'GET',
                    success: function(resp) {
                        let paciente = resp.paciente 
                            ? `${resp.paciente.nombre} ${resp.paciente.apellido_paterno} ${resp.paciente.apellido_materno}` 
                            : 'Paciente';
                        let doctor = resp.doctor ? resp.doctor.nombre : 'Doctor';
                        let sucursal = resp.sucursal ? resp.sucursal.nombre : 'Sucursal';
                        let categoria = resp.categoria ? resp.categoria.nombre : 'Sin categoría';

                        // Sesiones
                        let sesionesHtml = '';
                        if (resp.sesiones && resp.sesiones.length > 0) {
                            sesionesHtml = `<div class="list-group">`;
                            resp.sesiones.forEach(s => {
                                // productos usados
                                let productosHtml = '';
                                if (s.productos && s.productos.length > 0) {
                                    productosHtml = '<div class="my-0 text-muted small">';
                                    s.productos.forEach(p => {
                                        productosHtml += `${p.producto.nombre} (${p.pivot.detalle}) `;
                                    });
                                    productosHtml += '</div>';
                                }

                                // pago en sesión (si existe)
                                let pagoSesion = '';
                                if (resp.pagos && resp.pagos.length > 0) {
                                    let pago = resp.pagos.find(pg => pg.sesion_id === s.id);
                                    if (pago) {
                                        pagoSesion = `
                                            <strong>${pago.monto} Bs.</strong>`;
                                    }
                                }

                                sesionesHtml += `
                                    <div class="list-group-item">
                                        <div class="row align-items-center">                                           
                                            <div class="col">
                                                <strong>${s.analisis || 'Sin análisis'}</strong>
                                                <div class="my-0 text-muted small">${s.plan_accion || ''}</div>
                                                ${productosHtml}
                                            </div>
                                            <div>
                                                ${pagoSesion}
                                            </div>
                                            <div class="col-3 col-md-2">
                                                ${s.firma 
                                                    ? `<img src="/storage/firmas/${s.firma.split('/').pop()}" 
                                                            alt="firma" 
                                                            class="img-fluid rounded firma-img">` 
                                                    : '<span class="badge bg-secondary">Sin firma</span>'}
                                            </div>
                                        </div>
                                    </div>`;
                            });
                            sesionesHtml += `</div>`;
                        }


                        // Pagos
                        let pagosHtml = '';
                        if (resp.pagos && resp.pagos.length > 0) {
                            resp.pagos.forEach(p => {
                                pagosHtml += `
                                    <div class="d-flex justify-content-between w-100 mb-1" style="font-size:1.1em;">
                                        <span>${p.metodo_pago} - ${p.fecha}</span>
                                        <span>${p.monto} Bs.</span>
                                    </div>`;
                            });
                        }


                        // 🔥 Insertar en el modal con estilos elegantes
                        let modalBody = `
                            <div class="row text-start">
                                <div class="col-md-6 border-end pe-3">
                                    <div class="card shadow mb-4">
                                        <div class="card-header" Style="background-color: #2C3947; color: white; font-size: 1.2em; font-weight: bold;">
                                            <strong class="card-title" style="color: white">INFORMACION DEL TRATAMIENTO</strong>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row align-items-center mb-0">
                                                <dt class="col-sm-4 mb-3 text-muted">
                                                    Descripción:
                                                </dt>
                                                <dd class="col-sm-8 mb-3">
                                                    <strong>${resp.descripcion}</strong>
                                                </dd>
                                            </dl>
                                            <dl class="row align-items-center mb-0">
                                                <dt class="col-sm-4 mb-3 text-muted">
                                                    Paciente:
                                                </dt>
                                                <dd class="col-sm-8 mb-3">
                                                    <strong>${paciente}</strong>
                                                </dd>
                                            </dl>
                                            <dl class="row align-items-center mb-0">
                                                <dt class="col-sm-4 mb-3 text-muted">
                                                    Doctor:
                                                </dt>
                                                <dd class="col-sm-8 mb-3">
                                                    <strong>${doctor}</strong>
                                                </dd>
                                            </dl>
                                            <dl class="row align-items-center mb-0">
                                                <dt class="col-sm-4 mb-3 text-muted">
                                                    Sucursal:
                                                </dt>
                                                <dd class="col-sm-8 mb-3">
                                                    <strong>${sucursal}</strong>
                                                </dd>
                                            </dl>
                                            <dl class="row align-items-center mb-0">
                                                <dt class="col-sm-4 mb-3 text-muted">
                                                    Categoría:
                                                </dt>
                                                <dd class="col-sm-8 mb-3">
                                                    <strong>${categoria}</strong>
                                                </dd>
                                            </dl>
                                            <dl class="row align-items-center mb-0">
                                                <dt class="col-sm-4 mb-3 text-muted">
                                                    inicio:
                                                </dt>
                                                <dd class="col-sm-8 mb-3">
                                                    <strong>${resp.fecha_inicio}</strong>
                                                </dd>
                                            </dl>
                                            <dl class="row align-items-center mb-0">
                                                <dt class="col-sm-4 mb-3 text-muted">
                                                    Fecha fin estimada:
                                                </dt>
                                                <dd class="col-sm-8 mb-3">
                                                    <strong>${resp.fecha_fin_estimada}</strong>
                                                </dd>
                                            </dl>
                                            <dl class="row align-items-center mb-0">
                                                <dt class="col-sm-4 mb-3 text-muted">
                                                    Estado:
                                                </dt>
                                                <dd class="col-sm-8 mb-3">
                                                    <span class="badge badge-pill badge-success">${resp.estado}</span>
                                                </dd>
                                            </dl>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: #2C3947; color: white; font-size: 1.2em; font-weight: bold;">
                                            <span class="text-start">SUB TOTAL</span>
                                            <span class="text-end">${resp.costo_total} Bs.</span>
                                        </div>
                                        <div class="card-footer">
                                            ${pagosHtml}
                                        </div>
                                        <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: #2C3947; color: white; font-size: 1.2em; font-weight: bold;">
                                            <span class="text-start">DEUDA A PAGAR</span>
                                            <span class="text-end">${resp.diferencia_costo} Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-3">
                                    <div class="card shadow mb-4">
                                        <div class="card-header" Style="background-color: #2C3947; color: white; font-size: 1.2em; font-weight: bold;">
                                            <strong class="card-title" style="color: white">SESIONES DEL TRATAMIENTO</strong>
                                        </div>
                                        <div class="card-body">
                                            ${sesionesHtml || '<div class="text-center text-muted py-3">No hay sesiones registradas</div>'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('.bd-example-modal-xl .modal-body').html(modalBody);
                        $('.bd-example-modal-xl').modal('show');
                    },
                    error: function(err) {
                        console.error('Error cargando tratamiento:', err);
                        alert('No se pudo cargar la información del tratamiento.');
                    }
                });
            },

            // 🔥 TOOLTIP BONITO
            eventDidMount: function(info) {
                let contenido = info.event.title;
                $(info.el).tooltip({
                    title: contenido,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }

        });

        calendar.render();

    });
</script>
@endsection