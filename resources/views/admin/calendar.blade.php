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
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Nuevo evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="eventDate">

                <div class="mb-3">
                    <label>Título</label>
                    <input type="text" id="eventTitle" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Descripción</label>
                    <textarea id="eventDesc" class="form-control"></textarea>
                </div>

            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button class="btn btn-danger" id="btnDeleteEvent">Eliminar</button>
                <button class="btn btn-primary" id="btnSaveEvent">Guardar</button>
            </div>

        </div>
    </div>
</div>

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

            $.ajax({
                url: '/tratamientos-get',
                method: 'GET',
                success: function(resp) {

                    let eventos = [];

                    resp.tratamientos.forEach(t => {

                        eventos.push({
                            id: t.id,
                            title: t.descripcion || 'Tratamiento #' + t.id,
                            start: t.fecha_inicio, // 🔥 SOLO ESTA FECHA
                            allDay: true, // 🔥 opcional pero recomendado
                            color: t.estado === 'activo' ? '#28a745' : '#6c757d',

                            extendedProps: {
                                descripcion: t.descripcion,
                                paciente_id: t.paciente_id
                            }
                        });

                    });

                    successCallback(eventos);
                },
                error: function(err) {
                    console.error(err);
                    failureCallback(err);
                }
            });

        }
    });

    calendar.render();

});
</script>

@endsection