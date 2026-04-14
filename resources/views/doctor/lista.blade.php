@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">ATENDER</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">PACIENTES</a>
            </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> 
                    <div class="row">
                        <div class="col-md-12 bg-light" style="padding: 15px;">
                            <div class="w-20 mx-auto text-center justify-content-center py-8 my-8">
                                <h2 class="page-title mb-0" style="padding: 15px">Buscar Paciente (C.I, Nombre)</h2>  
                                <form class="searchform searchform-lg">
                                    <input id="buscarPaciente" class="form-control form-control-lg bg-white rounded-pill pl-5" type="search" placeholder="Buscar paciente . . ." aria-label="Search">
                                </form>
                            </div>
                                <div id="DivResultadoPaciente" class="mt-4">
                                
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-4 bg-light" style="padding: 15px;">
                            <div class="card shadow" id="ContenidoDivPaciente" style="padding: 15px;">
                            </div>
                        </div>
                        <div class="col-md-8 bg-light">
                            <div class="card shadow" id="DivSesionTratamiento">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> 
                    <div class="row">
                        <div class="col-md-7 bg-light" style="padding: 15px;">
                            <div class="card shadow">
                                <div class="card-body">
                                    <!-- Encabezado con botones -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Paciente</h5>
                                        <div class="d-flex align-items-center">
                                            <a href="#" class="btn btn-outline-primary mr-2" id="bntListPaciente">
                                                <span class="fe-list"></span>
                                            </a>
                                            <a href="#" class="btn btn-outline-primary mr-2" id="btnGridPaciente">
                                                <span class="fe-grid"></span>
                                            </a>
                                            <a href="#" class="btn btn-primary" id="btnAgregarUsuario">
                                                Agregar
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Vista LISTA (tabla) -->
                                    <div id="ListUsuarios">
                                        <table class="table table-striped table-hover" id="TableUsuarios">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre Completo</th>
                                                    <th>Documento</th>
                                                    <th>Celular</th>
                                                    <th>Direccion</th>
                                                    <th>Edad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Aquí se llenan los usuarios en lista -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Vista GRID (tarjetas) -->
                                    <div id="GridUsuarios" class="row d-none">
                                        
                                    </div>
                                </div>
                            </div>  
                        </div>


                        <div class="col-md-5 bg-light" style="padding: 15px;">
                            <div class="card shadow" id="ContenidoDiv" style="padding: 15px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<div class="modal fade" id="ModalAdelantoTratamiento" tabindex="-1" role="dialog" aria-labelledby="ModalAdelantoTratamientoTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAdelantoTratamientoTitle">Registrar Adelanto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card border-secondary">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <span class="font-weight-bold">PAGO</span>
                        <button type="button" class="btn btn-sm btn-info" id="btnAgregarFilaPago">
                            <i class="fas fa-plus"></i> +
                        </button>
                    </div>
                    <div class="card-body p-2" style="background-color: #f8f9fa;">
                        <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                            <div style="flex: 2;">
                                <select class="form-control form-control-sm" name="metodo_pago[]">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="qr">QR</option>
                                </select>
                            </div>
                            
                            <div class="font-weight-bold">Bs.</div>
                            
                            <div style="flex: 1;">
                                <input type="number" class="form-control form-control-sm text-right" 
                                       name="monto_pago[]" value="0.00" step="0.01">
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times"></i> x
                            </button>
                        </div>
                    </div>
                    <div class="card-footer py-2 bg-light">
                        <div class="d-flex justify-content-between">
                            <span class="font-weight-bold">Cambio:</span>
                            <span id="txtCambio">0.00 Bs.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn mb-2 btn-primary" id="btnConfirmarAdelanto">Confirmar</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    $(document).ready(function () {
        $(document).on("submit", ".searchform", function(e) {
            e.preventDefault();
        });

        $(document).on("keydown", "#buscarPaciente", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                $(this).trigger("keyup"); // dispara búsqueda
            }
        });

        function ejecutarAccionTab(tabId) {
            if (tabId === "home-tab") {
                SearchPaciente();
                mostrarEstadoEsperaPaciente();
            }
            if (tabId === "profile-tab") {
                mostrarEstadoEspera();
            }
        }

        // 🔥 Ejecutar según tab activo al cargar
        const tabInicial = $('#myTab .nav-link.active').attr("id");
        ejecutarAccionTab(tabInicial);

        // 🔥 Cambio de tabs
        $('#myTab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            const tabActivo = $(e.target).attr("id");
            ejecutarAccionTab(tabActivo);
        });

    });
</script>
<script>
    function SearchPaciente(){
        let timeout = null;
        $("#buscarPaciente").on("keyup", function() {
            clearTimeout(timeout);
            let query = $(this).val();
            if (query.length < 2) {
                $("#DivResultadoPaciente").html("");
                return;
            }
            $("#DivResultadoPaciente").html(`
                <div class="d-flex flex-column justify-content-center align-items-center text-muted" style="padding:30px;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <span class="mt-2">Buscando...</span>
                </div>
            `);
            timeout = setTimeout(function() {
                $.ajax({
                    url: "/buscar-pacientes",
                    type: "GET",
                    data: { search: query },
                    success: function(res) {
                        console.log("entro al succes");
                        let html = "";
                        if (res.data.length === 0) {
                            let query = $("#buscarPaciente").val();
                            html = `
                                <div class="text-center text-muted">No se encontraron resultados</div>
                                <div class="text-center mt-2">
                                    <button class="btn btn-primary btnRegistrarPaciente" data-ci="${query}">
                                        Registrar Paciente
                                    </button>
                                </div>
                            `;
                        } else {

                            res.data.forEach(function(p) {
                                html += `
                                    <div class="card mb-2 shadow-sm">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>${p.nombre} ${p.apellido_paterno} ${p.apellido_materno}</strong><br>
                                                <small>C.I: ${p.ci}</small>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-primary btnVerPaciente"
                                                    data-id="${p.id}"
                                                    data-nombre="${p.nombre}"
                                                    data-apellido-paterno="${p.apellido_paterno}"
                                                    data-apellido-materno="${p.apellido_materno}"
                                                    data-ci="${p.ci}">
                                                    Atender
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });

                        }

                        $("#DivResultadoPaciente").html(html);
                    },
                    error: function() {
                        $("#DivResultadoPaciente").html(`
                            <div class="text-danger text-center">Error al buscar pacientes</div>
                        `);
                    }
                });

            }, 300);

        });

        $(document).on("click", ".btnVerPaciente", function() {
            // 1. Obtener los datos del botón seleccionado
            const pacienteId = $(this).data("id");
            const nombreCompleto = $(this).data("nombre") + " " + $(this).data("apellido-paterno") + " " + $(this).data("apellido-materno");
            const ci = $(this).data("ci");
            
            // 2. Limpiar la lista de resultados de búsqueda
            $("#DivResultadoPaciente").html("");

            // 4. Renderizar la vista del paciente seleccionado
            $("#DivResultadoPaciente").html(`
                <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm">
                    <div>
                        <i class="fas fa-user-circle fa-lg mr-2"></i>
                        <strong style="font-size: 14px; color: black">${nombreCompleto}</strong> - <strong style="font-size: 14px; color: black">${ci}</strong> 
                    </div>
                </div>
            `);
            
            // 3. (Opcional) Limpiar o esconder el input de búsqueda
            $("#buscarPaciente").val("");
            
            $("#ContenidoDivPaciente").html(`
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Atender Paciente</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary" id="btnAgregarTratamiento" data-paciente-id="${pacienteId}">
                            Agregar tratamiento
                        </a>
                    </div>

                    <div class="card-body" id="DivTratamiento">
                        <p>Cargando tratamientos...</p>
                    </div>
                </div>
            `);

            // 🔥 Consultar tratamientos del paciente
            fetch(`/pacientes/${pacienteId}/tratamientos`)
                .then(res => res.json())
                .then(data => {
                    const div = document.getElementById("DivTratamiento");

                    if (data.length > 0) {
                        let html = ``;
                        data.forEach((t, index) => {
                            let estadoHTML = '';
                            let duracion = calcularDuracion(t.fecha_inicio, t.fecha_fin_estimada);

                            // 🔥 estado
                            if (t.estado === 'activo') {
                                estadoHTML = `<span class="text-success">
                                    <i class="fas fa-circle mr-1" style="font-size:10px;"></i> En proceso
                                </span>`;
                            } else if (t.estado === 'finalizado') {
                                estadoHTML = `<span class="text-primary">
                                    <i class="fas fa-circle mr-1" style="font-size:10px;"></i> Finalizado
                                </span>`;
                            } else if (t.estado === 'cancelado') {
                                estadoHTML = `<span class="text-danger">
                                    <i class="fas fa-circle mr-1" style="font-size:10px;"></i> Cancelado
                                </span>`;
                            }

                            // 🔥 render final
                            html += `
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="accordion w-100" id="accordion${index}">
                                        <div class="card shadow">
                                            
                                            <!-- HEADER -->
                                            <div class="card-header" id="heading${index}">
                                                <a role="button" data-toggle="collapse" href="#collapse${index}">
                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                        <strong>${t.categoria?.nombre ?? 'Sin categoría'}</strong>
                                                        ${estadoHTML}
                                                    </div>
                                                </a>
                                            </div>

                                            <!-- BODY -->
                                            <div id="collapse${index}" class="collapse show" data-parent="#accordion${index}">
                                                <div class="card-body"> 
                                                    
                                                    ${t.descripcion ?? ''} <br><br>

                                                    Inicio <strong>${t.fecha_inicio ?? '-'}</strong> 
                                                    Hasta <strong>${t.fecha_fin_estimada ?? '-'}</strong> 
                                                    (${duracion}) <br><br>

                                                    COSTO DEL TRATAMIENTO: 
                                                    <strong>${t.costo_total ?? 0} Bs.</strong> <br><br>

                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                        <a href="#" class="btn btn-sm btn-outline-primary" id="btnVerTratamiento" data-paciente-id="${t.id}">
                                                            Ver Tratamiento
                                                        </a>

                                                        <a href="#" class="btn btn-sm btn-link" id="btnAdelanto" data-paciente-id="${t.id}" data-toggle="modal" data-target="#ModalAdelantoTratamiento">
                                                            Adelanto
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                        div.innerHTML = html;
                    } else {
                        div.innerHTML = `<div class="alert alert-warning">Este paciente no tiene tratamientos en proceso.</div>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById("DivTratamiento").innerHTML = `<div class="alert alert-danger">Error al cargar tratamientos.</div>`;
                });
            });

            // Escuchar el click en el botón
            $(document).on("click", "#btnVerTratamiento", function(e) {
                e.preventDefault();

                const tratamientoId = $(this).data("paciente-id");

                // Petición AJAX a tu ruta Laravel
                $.ajax({
                    url: `/tratamiento/${tratamientoId}/sesiones`,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        let sesiones = data.sesiones;
                        let html = `
                            <div class="card shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Sesiones del tratamiento</h5>
                                    <a href="#" class="btn btn-sm btn-outline-primary" id="btnAgregarSesionTratamiento" data-tratamiento-id="${data.id}">
                                        Agregar Sesión
                                    </a>
                                </div>
                                <div class="card-body" id="CrearDivSesion">

                                <div class="card-body" id="DivSesion">
                        `;

                        if (sesiones && sesiones.length > 0) {                         

                            html += `<ul class="list-group">`;
                            sesiones.forEach(function(s) {
                                let botonFirma = s.firma 
                                ? `<img src="/storage/${s.firma}" width="120"/>`
                                : `<a href="#" class="btn mb-2 btn-link btnFirmar" data-id="${s.id}">¿Quieres Firmar?</a>`

                                html += `
                                    <table class="table table-bordered" style="width:100%;">
                                        <tr>
                                            <th style="width:60%;">
                                                <strong>FECHA DE ATENCION:</strong> ${s.fecha_atencion} <br><br>
                                                <strong>ANALISIS:</strong> ${s.analisis} <br><br>
                                                <strong>PLAN DE ACCION:</strong> ${s.plan_accion} <br><br>
                                            </th>

                                            <th style="width:10%;">${s.saldo} Bs.</th>

                                            <th style="width:10%;">${s.saldo} Bs.</th>

                                            <td style="width:20%;">${botonFirma}</td>
                                        </tr>
                                    </table>
                                `;
                            });
                            html += `</ul>`;
                        } else {
                            html += `<p>No hay sesiones registradas.</p>`;
                        }

                        html += `
                                </div>
                            </div>
                        `;

                        // Insertar en el div
                        $("#DivSesionTratamiento").html(html);

                        $(document).on("click", ".btnFirmar", function(e){
                            e.preventDefault();

                            let sesionId = $(this).data("id");
                            let $td = $(this).closest("td"); // 👈 agarramos la celda

                            $.post("/generar-token-firma", { sesion_id: sesionId }, function(resp){

                                let urlFirma = resp.url;

                                let qrHtml = `
                                    <div class="text-center">
                                        <div class="qrcode"></div>
                                    </div>
                                `;

                                // 👇 SOLO reemplaza ese td
                                $td.html(qrHtml);

                                // 👇 generar QR dentro de ese td
                                new QRCode($td.find(".qrcode")[0], {
                                    text: urlFirma,
                                    width: 120,
                                    height: 120
                                });

                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        alert("Error al obtener las sesiones: " + error);
                    }
                });

            });

            // Delegar el evento porque el botón se genera dinámicamente
            $(document).on("click", "#btnAgregarSesionTratamiento", function(e) {
                e.preventDefault();

                let tratamientoId = $(this).data("tratamiento-id");

                // Construir el formulario
                let formHtml = `
                    <form id="formNuevaSesion" data-tratamiento-id="${tratamientoId}">
                        <div class="mb-3">
                            <label for="fechaAtencion" class="form-label">Fecha de atención</label>
                            <input type="date" class="form-control" id="fechaAtencion" name="fechaAtencion" required>
                        </div>
                        <div class="mb-3">
                            <label for="analisis" class="form-label">Análisis</label>
                            <input type="text" class="form-control" id="analisis" name="analisis" required>
                        </div>
                        <div class="mb-3">
                            <label for="planAccion" class="form-label">Plan de acción</label>
                            <textarea class="form-control" id="planAccion" name="planAccion" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="costo" class="form-label">Costo</label>
                                <input type="number" step="0.01" class="form-control" id="costo" name="costo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="saldo" class="form-label">Saldo</label>
                                <input type="number" step="0.01" class="form-control" id="saldo" name="saldo" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar Sesión</button>
                    </form>
                `;

                // Insertar en el div
                $("#CrearDivSesion").html(formHtml);

                $(document).on("submit", "#formNuevaSesion", function(e) {
                    e.preventDefault();

                    let tratamientoId = $(this).data("tratamiento-id");
                    let formData = $(this).serialize();

                    $.ajax({
                        url: `/tratamiento/${tratamientoId}/sesiones`,
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            alert(response.message);
                        },
                        error: function(xhr) {
                            alert("Error al guardar la sesión: " + xhr.responseText);
                        }
                    });
                });

            });

            // Función para volver al buscador
            function volverBuscador() {
                $("#DivBuscador").removeClass("d-none");
                $("#DivPaciente").addClass("d-none");
                $("#ContenidoDivPaciente").html("");
            }


            // 🔹 CLICK: Renderiza el formulario
            $(document).on("click", "#btnAgregarTratamiento", function() {
                const pacienteId = $(this).data("paciente-id");

                $("#DivTratamiento").html(`
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            Registrar Nuevo Tratamiento
                        </div>
                        <div class="card-body">
                            <form id="formTratamiento" data-paciente-id="${pacienteId}">
                                <div class="form-group">
                                    <label>Categoría del tratamiento</label>
                                    <select class="form-control" name="categoria_id" id="selectCategoria" required>
                                        <option value="">Cargando categorías...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea class="form-control" name="descripcion" rows="3"></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Fecha inicio</label>
                                        <input type="date" class="form-control" name="fecha_inicio" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Fecha fin estimada</label>
                                        <input type="date" class="form-control" name="fecha_fin_estimada">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Costo total</label>
                                    <input type="number" step="0.01" class="form-control" name="costo_total" required>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success" id="btnGuardarTratamiento">
                                        Guardar Tratamiento
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                `);

                // 🔥 Cargar categorías
                $.ajax({
                    url: '/categoria-get',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let options = '<option value="">Seleccione una categoría</option>';
                        data.forEach(cat => {
                            options += `<option value="${cat.id}">${cat.nombre}</option>`;
                        });
                        $("#selectCategoria").html(options);
                    },
                    error: function() {
                        $("#selectCategoria").html('<option value="">Error al cargar categorías</option>');
                    }
                });
            });


            // 🔹 SUBMIT: SOLO UNA VEZ (FUERA DEL CLICK)
            let enviando = false;

            $(document).on("submit", "#formTratamiento", function(e) {
                e.preventDefault();

                if (enviando) return; // 🔥 evita doble envío
                enviando = true;

                const form = $(this);
                const pacienteId = form.data("paciente-id");
                const formData = form.serialize();

                // 🔥 Desactivar botón mientras envía
                $("#btnGuardarTratamiento").prop("disabled", true).text("Guardando...");

                $.ajax({
                    url: `/pacientes/${pacienteId}/crear-tratamientos`,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert("✅ Tratamiento registrado correctamente");
                        $("#DivTratamiento").html(`
                            <div class="alert alert-success">
                                Tratamiento guardado con éxito.
                            </div>
                        `);
                    },
                    error: function(xhr) {
                        alert("❌ Error al registrar tratamiento");
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        enviando = false;
                        $("#btnGuardarTratamiento").prop("disabled", false).text("Guardar Tratamiento");
                    }
                });
            });
        }
</script>
<script>
    function renderFormularioPaciente(valorBusqueda = '') {
        $("#ContenidoDiv").html(`
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Registrar Paciente</h5>
                </div>
                <div class="card-body">
                    <form id="formPaciente" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Cedula De Identidad</label>
                            <input type="text" class="form-control" name="ci" value="${valorBusqueda}" required>
                        </div>

                        <div class="form-row row">
                            <div class="form-group col-md-4">
                                <label>Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Apellido Paterno</label>
                                <input type="text" class="form-control" name="apellido_paterno" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Apellido Materno</label>
                                <input type="text" class="form-control" name="apellido_materno">
                            </div>
                        </div>

                        <div class="form-row row">
                            <div class="form-group col-md-6">
                                <label>Fecha Nacimiento</label>
                                <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Edad</label>
                                <input type="text" class="form-control" name="edad" id="edad" readonly>
                            </div>
                        </div>

                        <div class="form-row row">
                            <div class="form-group col-md-6">
                                <label>Lugar Nacimiento</label>
                                <input type="text" class="form-control" name="lugar_nacimiento" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Direccion</label>
                                <input type="text" class="form-control" name="direccion" required>
                            </div>
                        </div>

                        <div class="form-row row">
                            <div class="form-group col-md-6">
                                <label>Ocupacion</label>
                                <input type="text" class="form-control" name="ocupacion" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Telefono</label>
                                <input type="text" class="form-control" name="telefono" required>
                            </div>
                        </div>

                        <div class="form-row row">
                            <div class="form-group col-md-6">
                                <label>Estado</label>
                                <select class="form-control" name="estado" required>
                                    <option value="Soltero">Soltero</option>
                                    <option value="Casado">Casado</option>
                                    <option value="Divorciado">Divorciado</option>
                                    <option value="Viudo">Viudo</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Genero</label>
                                <select class="form-control" name="genero" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <!-- 🔥 Imagen en su propia fila -->
                        <div class="form-group">
                            <label>Imagen</label>
                            <input type="file" class="form-control" name="imagen" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
        `);

        // calcular edad
        $(document).off('change', '#fecha_nacimiento').on('change', '#fecha_nacimiento', function () {
            const fechaNacimiento = new Date(this.value);
            const hoy = new Date();

            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            const mes = hoy.getMonth() - fechaNacimiento.getMonth();

            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                edad--;
            }

            $('#edad').val(edad);
        });
    }

    // botón con búsqueda
    $(document).on("click", ".btnRegistrarPaciente", function() {
        const valorBusqueda = $("#buscarPaciente").val();

        $('#profile-tab').tab('show');

        $('#profile-tab')
            .off('shown.bs.tab')
            .on('shown.bs.tab', function () {
                renderFormularioPaciente(valorBusqueda);
            });
    });

    // botón agregar
    $(document).on("click", "#btnAgregarUsuario", function(e) {
        e.preventDefault();

        $('#profile-tab').tab('show');

        setTimeout(function() {
            renderFormularioPaciente();
        }, 100);
    });

    // 🔥 SUBMIT CORREGIDO (CON IMAGEN)
    $(document).off("submit", "#formPaciente").on("submit", "#formPaciente", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "/pacientes",
            method: "POST",
            data: formData,
            processData: false, // 🔥 importante
            contentType: false, // 🔥 importante
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastSuccess("Paciente Registrado Correctamente");
                mostrarEstadoEspera();
            },
            error: function(xhr) {
                toastError("Hubo un problema al registrar el paciente");
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const btnList = document.getElementById("bntListPaciente");
        const btnGrid = document.getElementById("btnGridPaciente");
        const listUsuarios = document.getElementById("ListUsuarios");
        const gridUsuarios = document.getElementById("GridUsuarios");

        // 🔥 TRAER PACIENTES
        function cargarPacientes() {
            return fetch('/get-paciente')
                .then(res => res.json());
        }

        // 🔥 CALCULAR EDAD
        function calcularEdad(fechaNacimiento) {
            const hoy = new Date();
            const nacimiento = new Date(fechaNacimiento);

            let edad = hoy.getFullYear() - nacimiento.getFullYear();
            const m = hoy.getMonth() - nacimiento.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
                edad--;
            }

            return edad;
        }

        // 🔥 RENDER LISTA
        function renderLista(data) {
            const tbody = document.querySelector("#TableUsuarios tbody");
            tbody.innerHTML = "";

            data.forEach(p => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>${p.id}</td>
                    <td>${p.nombre} ${p.apellido_paterno} ${p.apellido_materno}</td>
                    <td>${p.ci}</td>
                    <td>${p.telefono}</td>
                    <td>${p.direccion}</td>
                    <td>${p.fecha_nacimiento ? calcularEdad(p.fecha_nacimiento) : ''}</td>
                `;

                // 👉 Evento click en la fila
                tr.addEventListener("click", function() {
                    // Renderizar estructura base con tabs y formulario vacío
                    document.getElementById("ContenidoDiv").innerHTML = `
                        <div class="card shadow">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="paciente-tab" data-toggle="tab" href="#paciente" role="tab">Paciente</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="antecedentes-tab" data-toggle="tab" href="#antecedentes" role="tab">Antecedentes Médico</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="paciente" role="tabpanel">
                                       <div class="card mb-3 shadow-sm">
                                            <div class="card-header bg-primary text-white" style="font-weight:bold;">
                                                Información del Paciente
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-center mb-3">
                                                        <h4 class="mb-3">${p.nombre} ${p.apellido_paterno} ${p.apellido_materno}</h4>
                                                    </div>
                                                    <div class="col-md-12" style="text-align:center; padding-bottom:15px;">
                                                        ${p.imagen 
                                                            ? `<img src="/storage/${p.imagen}" alt="Foto paciente" class="img-thumbnail shadow-sm" style="max-width:60%;">` 
                                                            : `<div class="alert alert-secondary">Sin foto</div>`}
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label class="text-muted">CI</label>
                                                                <div><strong>${p.ci}</strong></div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="text-muted">Teléfono</label>
                                                                <div><strong>${p.telefono}</strong></div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="text-muted">Dirección</label>
                                                                <div><strong>${p.direccion}</strong></div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="text-muted">Fecha de nacimiento</label>
                                                                <div><strong>${p.fecha_nacimiento}</strong></div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="text-muted">Lugar de nacimiento</label>
                                                                <div><strong>${p.lugar_nacimiento}</strong></div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="text-muted">Ocupación</label>
                                                                <div><strong>${p.ocupacion}</strong></div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="text-muted">Estado civil</label>
                                                                <div><strong>${p.estado_civil}</strong></div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="text-muted">Sexo</label>
                                                                <div><strong>${p.sexo}</strong></div>
                                                            </div>
                                                        </div>
                                                    </div>                                            
                                                </div>
                                            </div>
                                        </div>


                                        <!-- 🔹 Mostrar antecedentes médicos como texto -->
                                        ${p.antecedente_medico ? `
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">Antecedentes Médicos</div>
                                            <div class="card-body">
                                                <div class="card mb-3 shadow-sm">
                                                    <div class="card-header" style="background-color:#EEEEEE;">
                                                        Antecedentes Patológicos Familiares <strong>${p.antecedente_medico.antecedentes_familiares || 'No registrado'}</strong>
                                                    </div>
                                                </div>

                                                <div class="card mb-3 shadow-sm">
                                                    <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                        Antecedentes Patológicos Personales
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label>Anemia <strong>(${p.antecedente_medico.anemia ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>Asma <strong>(${p.antecedente_medico.asma ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>Cardiopatías <strong>(${p.antecedente_medico.cardiopatias ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>Diabetes <strong>(${p.antecedente_medico.diabetes ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label>Epilepsia <strong>(${p.antecedente_medico.epilepsia ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>Hipertensión <strong>(${p.antecedente_medico.hipertension ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>Tuberculosis <strong>(${p.antecedente_medico.tuberculosis ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>VIH <strong>(${p.antecedente_medico.vih ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label>Embarazo <strong>(${p.antecedente_medico.embarazo ? 'Sí' : 'No'})</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Alergias <strong>${p.antecedente_medico.alergias || 'No registrado'}</strong></label>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Otros <strong>${p.antecedente_medico.otros || 'No registrado'}</strong></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- 🔹 Tratamientos Médicos -->
                                                <div class="card mb-3 shadow-sm">
                                                    <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                        Tratamientos Médicos
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group">
                                                                <label>¿Está en tratamiento médico?</label>
                                                                <strong>(${p.antecedente_medico.en_tratamiento ? 'Sí' : 'No'})</strong>
                                                            </div>
                                                        </div>
                                                         <div class="form-row">
                                                            <div class="form-group">
                                                                <label>¿Actualmente recibe algún tratamiento?</label>
                                                                <strong>(${p.antecedente_medico.recibe_tratamiento ? 'Sí' : 'No'})</strong>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>¿Tuvo hemorragia después de una extracción dental?</label>
                                                            <strong>(${p.antecedente_medico.hemorragia_extraccion ? 'Sí' : 'No'})</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                

                                                <!-- 🔹 Antecedentes Bucodentales -->
                                                <div class="card mb-3 shadow-sm">
                                                    <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                        Antecedentes Bucodentales
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Fecha de última visita al dentista (<strong>${p.antecedente_medico.ultima_visita || 'No registrado'}</strong>)</label>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>¿Fuma? (<strong>${p.antecedente_medico.fuma ? 'Sí' : 'No'}</strong>)</label>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>¿Bebe? (<strong>${p.antecedente_medico.bebe ? 'Sí' : 'No'}</strong>)</label>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label>¿Utiliza prótesis dental? (<strong>${p.antecedente_medico.protesis ? 'Sí' : 'No'}</strong>)</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- 🔹 Higiene Oral -->
                                                <div class="card mb-3 shadow-sm">
                                                    <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                        Higiene Oral
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>¿Utiliza cepillo dental?(<strong>${p.antecedente_medico.cepillo ? 'Sí' : 'No'}</strong>)</label>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>¿Utiliza hilo dental?(<strong>${p.antecedente_medico.hilo ? 'Sí' : 'No'}</strong>)</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>¿Utiliza enjuague bucal?(<strong>${p.antecedente_medico.enjuague ? 'Sí' : 'No'}</strong>)</label>
                                                            </div>
                                                             <div class="form-group col-md-6">
                                                                <label>Frecuencia de cepillado (<strong>${p.antecedente_medico.frecuencia || 'No registrado'}</strong>)</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label>¿al cepillarse le sangran las encías? (<strong>${p.antecedente_medico.sangrado ? 'Sí' : 'No'}</strong>)</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        ` : `
                                        <div class="alert alert-warning">Este paciente aún no tiene antecedentes médicos registrados.</div>
                                        `}
                                    </div>

                                    <div class="tab-pane fade" id="antecedentes" role="tabpanel">
                                        <form id="formAntecedentes" data-id="${p.id}">
                                            @csrf
                                            <!-- 🔹 Antecedentes Patológicos Familiares -->
                                            <div class="card mb-3 shadow-sm">
                                                <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                    Antecedentes Patológicos Familiares
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>¿Tiene antecedentes familiares de enfermedades?</label>
                                                        <input type="text" class="form-control" name="PatologicosFamiliares" placeholder="Ej: Diabetes, Hipertensión, etc." required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 🔹 Antecedentes Patológicos Personales -->
                                            <div class="card mb-3 shadow-sm">
                                                <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                    Antecedentes Patológicos Personales
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label>Anemia</label>
                                                            <select class="form-control" name="anemia"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Asma</label>
                                                            <select class="form-control" name="asma"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Cardiopatías</label>
                                                            <select class="form-control" name="cardiopatias"><option>Si</option><option>No</option></select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label>Diabetes</label>
                                                            <select class="form-control" name="diabetes"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Epilepsia</label>
                                                            <select class="form-control" name="epilepsia"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Hipertensión</label>
                                                            <select class="form-control" name="hipertension"><option>Si</option><option>No</option></select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label>Tuberculosis</label>
                                                            <select class="form-control" name="tuberculosis"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>VIH</label>
                                                            <select class="form-control" name="vih"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Embarazo</label>
                                                            <select class="form-control" name="embarazo"><option>Si</option><option>No</option></select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label>Alergias</label>
                                                        <input type="text" class="form-control" name="alergias" placeholder="Ej: Penicilina, polen...">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label>Otros</label>
                                                        <input type="text" class="form-control" name="otros" placeholder="Otros antecedentes">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 🔹 Tratamientos Médicos -->
                                            <div class="card mb-3 shadow-sm">
                                                <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                    Tratamientos Médicos
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label>¿Está en tratamiento médico?</label>
                                                            <select class="form-control" name="en_tratamiento"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>¿Actualmente recibe algún tratamiento?</label>
                                                            <select class="form-control" name="recibe_tratamiento"><option>Si</option><option>No</option></select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>¿Tuvo hemorragia después de una extracción dental?</label>
                                                        <select class="form-control" name="hemorragia"><option>Si</option><option>Inmediatamente</option><option>No</option></select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 🔹 Antecedentes Bucodentales -->
                                            <div class="card mb-3 shadow-sm">
                                                <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                    Antecedentes Bucodentales
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Fecha de última visita al dentista</label>
                                                        <input type="date" class="form-control" name="ultima_visita">
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label>¿Fuma?</label>
                                                            <select class="form-control" name="fuma"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>¿Bebe?</label>
                                                            <select class="form-control" name="bebe"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>¿Utiliza prótesis dental?</label>
                                                            <select class="form-control" name="protesis"><option>Si</option><option>No</option></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 🔹 Higiene Oral -->
                                            <div class="card mb-3 shadow-sm">
                                                <div class="card-header" style="background-color:#EEEEEE; font-weight:bold;">
                                                    Higiene Oral
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label>¿Utiliza cepillo dental?</label>
                                                            <select class="form-control" name="cepillo"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>¿Utiliza hilo dental?</label>
                                                            <select class="form-control" name="hilo"><option>Si</option><option>No</option></select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>¿Utiliza enjuague bucal?</label>
                                                            <select class="form-control" name="enjuague"><option>Si</option><option>No</option></select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label>Frecuencia de cepillado</label>
                                                            <select class="form-control" name="frecuencia">
                                                                <option>1 vez al día</option>
                                                                <option>2 veces al día</option>
                                                                <option>3 veces al día</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>¿al cepillarse le sangran las encías?</label>
                                                            <select class="form-control" name="sangrado"><option>Si</option><option>No</option></select>
                                                        </div>
                                            <div class="text-right mt-3">
                                                <button type="submit" class="btn btn-success">Guardar Antecedentes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    const form = document.getElementById("formAntecedentes");
                    const pacienteId = p.id;

                    // 🔥 Consultar antecedentes existentes
                    fetch(`/pacientes/${pacienteId}/antecedentes-show`)
                        .then(res => res.json())
                        .then(data => {
                            if (data) {
                                // Campos de texto
                                const fields = {
                                    PatologicosFamiliares: data.antecedentes_familiares,
                                    otros: data.otros,
                                    alergias: data.alergias,
                                    ultima_visita: data.ultima_visita,
                                    frecuencia: data.frecuencia
                                };

                                Object.keys(fields).forEach(name => {
                                    const input = form.querySelector(`[name="${name}"]`);
                                    if (input) input.value = fields[name] || '';
                                });

                                // Campos booleanos
                                const booleanFields = [
                                    'anemia','asma','cardiopatias','diabetes','epilepsia','hipertension',
                                    'tuberculosis','vih','embarazo','en_tratamiento','recibe_tratamiento',
                                    'hemorragia','fuma','bebe','protesis','cepillo','hilo','enjuague','sangrado'
                                ];

                                booleanFields.forEach(campo => {
                                    const input = form.querySelector(`[name="${campo}"]`);
                                    if (input) input.value = data[campo] ? 'Si' : 'No';
                                });
                            } else {
                                console.log("Paciente sin antecedentes registrados");
                            }
                        });
                });

                tbody.appendChild(tr);
            });

            // 👉 Capturar envío del formulario
            document.addEventListener("submit", function (e) {
                if (e.target && e.target.id === "formAntecedentes") {
                    e.preventDefault();

                    const form = e.target;
                    const formData = new FormData(form);
                    const pacienteId = form.getAttribute("data-id");

                    fetch(`/pacientes/${pacienteId}/antecedentes`, { 
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            "Accept": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("✅ Guardado correctamente");
                        } else {
                            alert("❌ Error al guardar");
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
                }
            });
        }


        // 🔥 RENDER GRID
        function renderGrid(data) {
            gridUsuarios.innerHTML = "";

            data.forEach(p => {

                let imagen = p.imagen
                    ? `/storage/${p.imagen}`
                    : `/assets/avatars/default.jpg`;

                gridUsuarios.innerHTML += `
                    <div class="col-md-4 mb-3">
                        <div class="card shadow mb-4">

                            <div class="card-body text-center">

                                <img src="${imagen}"
                                    class="rounded-circle mt-3"
                                    style="width:90px;height:90px;object-fit:cover;">

                                <div class="mt-2">
                                    <strong>${p.nombre} ${p.apellido_paterno}</strong>
                                    <p class="small text-muted mb-0">CI: ${p.ci}</p>
                                    <p class="small">
                                        <span class="badge badge-light">${p.direccion}</span>
                                    </p>
                                    <p class="small text-muted">
                                        Edad: ${p.fecha_nacimiento ? calcularEdad(p.fecha_nacimiento) : ''}
                                    </p>
                                </div>

                            </div>

                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">

                                    <small>
                                        <span class="dot dot-lg bg-success mr-1"></span> Activo
                                    </small>

                                    <div class="dropdown">
                                        <button class="btn btn-link p-0 text-muted" data-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Ver</a>
                                            <a class="dropdown-item" href="#">Editar</a>
                                            <a class="dropdown-item text-danger" href="#">Eliminar</a>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                `;
            });
        }

        // 🔥 LIST BUTTON
        btnList.addEventListener("click", function(e) {
            e.preventDefault();

            listUsuarios.classList.remove("d-none");
            gridUsuarios.classList.add("d-none");

            cargarPacientes().then(renderLista);
        });

        // 🔥 GRID BUTTON
        btnGrid.addEventListener("click", function(e) {
            e.preventDefault();

            listUsuarios.classList.add("d-none");
            gridUsuarios.classList.remove("d-none");

            cargarPacientes().then(renderGrid);
        });

        // 🔥 🔥 FIX REAL BOOTSTRAP TAB (ESTO ES LO QUE TE FALTABA)
        $(document).on('shown.bs.tab', function (e) {

            const target = $(e.target).attr("href");

            if (target === "#profile") {

                listUsuarios.classList.remove("d-none");
                gridUsuarios.classList.add("d-none");

                cargarPacientes().then(renderLista);
            }
        });

    });
</script>
<script>
    function mostrarEstadoEsperaPaciente() {
        const contenedor = document.getElementById("ContenidoDivPaciente");
        if (!contenedor) return;

        contenedor.innerHTML = `
                <div class="d-flex flex-column justify-content-center align-items-center" style="height:auto;">
                    <img src="/svg/grid.svg" alt="En espera" style="width:60%; height:auto;">
                    <span class="mt-3 text-muted h5">En espera</span>
                </div>
            `;
    }
</script>
<script>
    function calcularDuracion(inicio, fin) {
        if (!inicio || !fin) return '-';
        const fechaInicio = new Date(inicio);
        const fechaFin = new Date(fin);
        if (isNaN(fechaInicio) || isNaN(fechaFin)) return '-';
        let años = fechaFin.getFullYear() - fechaInicio.getFullYear();
        let meses = fechaFin.getMonth() - fechaInicio.getMonth();
        let dias = fechaFin.getDate() - fechaInicio.getDate();
        if (dias < 0) {
            meses--;
            dias += 30; 
        }

        if (meses < 0) {
            años--;
            meses += 12;
        }

        let resultado = '';

        if (años > 0) resultado += `${años} año(s) `;
        if (meses > 0) resultado += `${meses} mes(es) `;
        if (dias > 0) resultado += `${dias} día(s)`;

        return resultado || '0 días';
    }
</script>
@endsection

