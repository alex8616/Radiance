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
            <h5 class="modal-title">Registrar Adelanto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="card shadow">

                <div class="card-header d-flex justify-content-between align-items-center" style="background: #2E383F">
                    <h5 class="mb-0" style="color: white">Pagos</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary" id="btnAgregarPagos">+</a>
                </div>

                <div id="contenedorPagos">
                    <div class="row pago-item" style="padding: 15px">
                        
                        <div class="col-md-7">
                            <select class="form-control metodo">
                                <option value="efectivo">Efectivo</option>
                                <option value="qr">QR/Deposito</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input class="form-control form-control-sm monto" type="number" value="0">
                        </div>

                        <div class="col-md-2">
                            <a href="#" class="btn btn-sm btn-outline-danger btnEliminarPago">X</a>
                        </div>

                    </div>
                </div>

                <div class="card-header d-flex justify-content-between align-items-center" style="background: #2E383F">
                    <h5 class="mb-0" style="color: white">
                        Total: <span id="totalPagos">0</span>
                    </h5>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="btnGuardarPagos" class="btn btn-primary">
                Registrar Adelanto
            </button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="ModalProductos" tabindex="-1" role="dialog" aria-labelledby="ModalProductosTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Agregar productos usados en la sesion.</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="mb-3">
                        <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto...">
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div id="gridProductos" class="row" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-sm" id="tablaSeleccionados">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Detalle</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="btnGuardarProductos" class="btn btn-primary">
                Guardar Productos
            </button>
        </div>
        </div>
    </div>
</div>

<style>
    .producto-card {
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        background: #ffffff;
    }

    .producto-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        background: #f8f9fa;
    }

    .producto-card:active {
        transform: scale(0.98);
    }
</style>
<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const contenedor = document.getElementById("contenedorPagos");
        const btnAgregar = document.getElementById("btnAgregarPagos");
        const totalSpan = document.getElementById("totalPagos");

        // 👉 Agregar nueva fila
        btnAgregar.addEventListener("click", function (e) {
            e.preventDefault();

            const nuevaFila = document.createElement("div");
            nuevaFila.classList.add("row", "pago-item");
            nuevaFila.style.padding = "15px";

            nuevaFila.innerHTML = `
                <div class="col-md-7">
                    <select class="form-control">
                        <option value="efectivo">Efectivo</option>
                        <option value="qr">QR/Deposito</option>
                        <option value="tarjeta">Tarjeta</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input class="form-control form-control-sm monto" type="number" value="0">
                </div>
                <div class="col-md-2">
                    <a href="#" class="btn btn-sm btn-outline-danger btnEliminarPago">X</a>
                </div>
            `;

            contenedor.appendChild(nuevaFila);
        });

        // 👉 Eliminar fila (pero dejar al menos una)
        contenedor.addEventListener("click", function (e) {
            if (e.target.classList.contains("btnEliminarPago")) {
                e.preventDefault();

                const filas = document.querySelectorAll(".pago-item");

                if (filas.length > 1) {
                    e.target.closest(".pago-item").remove();
                    calcularTotal();
                } else {
                    alert("Debe existir al menos un pago");
                }
            }
        });

        // 👉 Escuchar cambios en inputs
        contenedor.addEventListener("input", function (e) {
            if (e.target.classList.contains("monto")) {
                calcularTotal();
            }
        });

        // 👉 Función para calcular total
        function calcularTotal() {
            let total = 0;

            document.querySelectorAll(".monto").forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            totalSpan.textContent = total.toFixed(2);
        }

    });

    $(document).on("click", ".btnAdelanto", function () {
        let tratamientoId = $(this).data("tratamiento-id");
        $("#btnGuardarPagos").data("id", tratamientoId);
    });

    $("#btnGuardarPagos").on("click", function (e) {
        e.preventDefault();

        let btn = $(this);
        btn.prop("disabled", true);

        let pagos = [];

        $(".pago-item").each(function () {

            let metodo = $(this).find(".metodo").val();
            let monto = parseFloat($(this).find(".monto").val()) || 0;

            if (monto > 0) {
                pagos.push({
                    metodo: metodo,
                    monto: monto
                });
            }
        });

        if (pagos.length === 0) {
            alert("Debe ingresar al menos un pago válido");
            btn.prop("disabled", false);
            return;
        }

        $.ajax({
            url: "/pagos/adelanto",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                tratamiento_id: $("#btnGuardarPagos").data("id"), // 🔥 CORRECTO
                pagos: pagos
            },
            success: function (response) {

                alert("✅ Pagos registrados correctamente");

                $("#ModalAdelantoTratamiento").modal("hide");

                // 🔥 limpiar formulario
                $(".monto").val(0);
                $("#totalPagos").text("0");

            },
            error: function (xhr) {
                console.error(xhr);
                alert("❌ Error al guardar");
            },
            complete: function () {
                btn.prop("disabled", false);
            }
        });

    });
</script>

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

                            // 🔥 PAGOS
                            let pagosHTML = '';
                            let totalPagos = 0;

                            if (t.pagos && t.pagos.length > 0) {

                                t.pagos.forEach(p => {

                                    let nombre = '';

                                    if (p.sesion_id === null) {
                                        nombre = 'Adelanto de tratamiento';
                                    } else {
                                        let sesion = t.sesiones.find(s => s.id === p.sesion_id);
                                        nombre = sesion 
                                            ? `Sesión (${sesion.fecha_atencion})`
                                            : `Sesión #${p.sesion_id}`;
                                    }

                                    let monto = parseFloat(p.monto);
                                    totalPagos += monto;

                                    pagosHTML += `
                                        <div class="row pago-item" style="padding: 10px">
                                            <div class="col-md-7">
                                                ${nombre} <br>
                                                <small>${p.metodo_pago}</small>
                                            </div>

                                            <div class="col-md-5 text-right">
                                                ${monto.toFixed(2)} Bs.
                                            </div>
                                        </div>
                                    `;
                                });

                            } else {
                                pagosHTML = `<p class="text-muted p-2">No hay pagos registrados</p>`;
                            }

                            // 🔥 HTML FINAL
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

                                                        <a href="#" class="btn btn-sm btn-link btnAdelanto" data-tratamiento-id="${t.id}" data-toggle="modal" data-target="#ModalAdelantoTratamiento">
                                                            Adelanto
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 🔥 PAGOS -->
                                            <div class="card-body">
                                                <div class="card shadow">

                                                    <div class="card-header d-flex justify-content-between align-items-center" style="background: #2E383F">
                                                        <h5 class="mb-0" style="color: white">Pagos</h5>
                                                    </div>

                                                    <div id="contenedorPagos">
                                                        ${pagosHTML}
                                                    </div>

                                                    <div class="card-header d-flex justify-content-between align-items-center" style="background: #2E383F">
                                                        <h5 class="mb-0" style="color: white">
                                                            Total
                                                        </h5>
                                                        <h5 class="mb-0" style="color: white">
                                                            ${totalPagos.toFixed(2)} Bs.
                                                        </h5>
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
                            sesiones.forEach(function (s) {
                                let pagosSesion = data.pagos.filter(p => p.sesion_id === s.id);

                                let pagadoSesion = pagosSesion.reduce(
                                    (sum, p) => sum + parseFloat(p.monto),
                                    0
                                );

                                let desglose = {};

                                pagosSesion.forEach(p => {
                                    let metodo = p.metodo_pago || 'sin metodo';
                                    desglose[metodo] = (desglose[metodo] || 0) + parseFloat(p.monto);
                                });

                                let detallePagoHtml = Object.keys(desglose)
                                    .map(m => `- ${m.toUpperCase()}: ${desglose[m].toFixed(2)} Bs`)
                                    .join("<br>");

                                let botonFirma = s.firma
                                    ? `<img src="/storage/${s.firma}" width="120"/>`
                                    : `<a href="#" class="btn mb-2 btn-link btnFirmar" data-id="${s.id}">¿Quieres Firmar?</a>`;

                                // 🔥 PRODUCTOS HTML
                                let productosHtml = '';

                                if (s.productos && s.productos.length > 0) {
                                    productosHtml = `
                                        <div style="margin-top:10px;">
                                            <table class="table table-sm table-hover table-borderless">
                                                <thead style="background: #f1f1f1;">
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Detalle</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    `;

                                    s.productos.forEach(p => {
                                        productosHtml += `
                                            <tr>
                                                <td>${p.nombre}</td>
                                                <td>${p.pivot?.detalle || '-'}</td>
                                            </tr>
                                        `;
                                    });

                                    productosHtml += `
                                                </tbody>
                                            </table>
                                        </div>
                                    `;
                                }

                                html += `
                                    <table class="table table-bordered" style="width:100%;">
                                        <tr>
                                            <th style="width:55%;">
                                                <strong>FECHA DE ATENCION:</strong> ${s.fecha_atencion} <br><br>
                                                <strong>ANALISIS:</strong> ${s.analisis} <br><br>
                                                <strong>PLAN DE ACCION:</strong> ${s.plan_accion} <br><br>

                                                <a href="#" class="btn btn-link btnProductos" data-id="${s.id}">
                                                    Agregar productos usados
                                                </a>

                                                ${productosHtml}
                                            </th>

                                            <th style="width:15%;">
                                                <strong>${pagadoSesion.toFixed(2)} Bs</strong><br>
                                                <small>${detallePagoHtml}</small>
                                            </th>

                                            <td style="width:20%;">
                                                ${botonFirma}
                                            </td>
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

            /**INICIO PRODUCTOS */
            let productosGlobal = [];
            let seleccionados = [];

            // 🔹 ABRIR MODAL Y CARGAR PRODUCTOS
            $(document).on('click', '.btnProductos', function (e) {
                e.preventDefault();

                let sesionId = $(this).data('id');

                $('#ModalProductos').data('sesion-id', sesionId);
                $('#sesion_id').val(sesionId);

                // limpiar
                seleccionados = [];
                $('#tablaSeleccionados tbody').html('');
                $('#gridProductos').html('Cargando productos...');

                $.ajax({
                    url: '/productos-get',
                    type: 'GET',
                    dataType: 'json',
                    success: function(productos) {
                        productosGlobal = productos;
                        renderProductos(productosGlobal);
                    },
                    error: function() {
                        $('#gridProductos').html('Error al cargar productos');
                    }
                });

                $('#ModalProductos').modal('show');
            });


            // 🔹 RENDER GRID PRODUCTOS
            function renderProductos(lista) {
                let html = '';

                lista.forEach(p => {
                    html += `
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <div class="card producto-card shadow-sm border-0 h-100"
                                data-id="${p.id}" 
                                data-nombre="${p.nombre}">

                                <div class="card-body d-flex align-items-center justify-content-center text-center">
                                    <span class="fw-semibold text-dark">
                                        ${p.nombre}
                                    </span>
                                </div>

                            </div>
                        </div>
                    `;
                });

                $('#gridProductos').html(html);
            }


            // 🔹 CLICK EN PRODUCTO (AGREGAR)
            $(document).on('click', '.producto-card', function () {

                let id = $(this).data('id');
                let nombre = $(this).data('nombre');

                // evitar duplicados
                let existe = seleccionados.find(p => p.id == id);
                if (existe) return;

                seleccionados.push({
                    id: id,
                    nombre: nombre,
                    cantidad: 1
                });

                $(this).addClass('active');

                renderSeleccionados();
            });


            // 🔹 RENDER TABLA DERECHA
            function renderSeleccionados() {

                let html = '';

                seleccionados.forEach((p, index) => {
                    html += `
                        <tr>
                            <td>${p.nombre}</td>
                            <td>
                                <input type="text" 
                                    value="${p.detalle || ''}" 
                                    placeholder="Ej: 1 litro, 2 unidades..."
                                    class="form-control detalleSel" 
                                    data-index="${index}">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger eliminarSel" data-index="${index}">X</button>
                            </td>
                        </tr>
                    `;
                });

                $('#tablaSeleccionados tbody').html(html);
            }

            // 🔹 ELIMINAR PRODUCTO
            $(document).on('click', '.eliminarSel', function () {
                let index = $(this).data('index');
                let producto = seleccionados[index];

                // quitar clase active del grid
                $(`.producto-card[data-id="${producto.id}"]`).removeClass('active');

                seleccionados.splice(index, 1);
                renderSeleccionados();
            });


            // 🔹 BUSCADOR
            $(document).on('keyup', '#buscarProducto', function () {
                let texto = $(this).val().toLowerCase();

                let filtrados = productosGlobal.filter(p =>
                    p.nombre.toLowerCase().includes(texto)
                );

                renderProductos(filtrados);
            });           

            // 🔹 GUARDAR
            $(document).on('input', '.detalleSel', function () {
                let index = $(this).data('index');
                seleccionados[index].detalle = $(this).val();
            });

           $('#btnGuardarProductos').click(function () {
                let sesionId = $('#ModalProductos').data('sesion-id');

                let productos = seleccionados.map(p => ({
                    producto_id: p.id,
                    detalle: p.detalle 
                }));

                $.ajax({
                    url: `/sesiones/${sesionId}/productos`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        productos: productos
                    },
                    success: function(resp) {
                        alert(resp.message);
                        $('#ModalProductos').modal('hide');
                    },
                    error: function(err) {
                        console.error(err);
                        alert('Error al guardar productos');
                    }
                });

            });
            /**FIN PRODUCTOS */
/** inisio de session */
            // Delegar el evento porque el botón se genera dinámicamente
            $(document).on("click", "#btnAgregarSesionTratamiento", function (e) {
                e.preventDefault();

                let tratamientoId = $(this).data("tratamiento-id");

                let formHtml = `
                    <form id="formNuevaSesion" data-tratamiento-id="${tratamientoId}">

                        <div class="mb-3">
                            <label class="form-label">Fecha de atención</label>
                            <input type="date" class="form-control" name="fechaAtencion" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Análisis</label>
                            <input type="text" class="form-control" name="analisis" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Plan de acción</label>
                            <textarea class="form-control" name="planAccion" required></textarea>
                        </div>

                        <div class="card shadow">

                            <div class="card-header d-flex justify-content-between align-items-center" style="background: #2E383F">
                                <h5 class="mb-0 text-white">Pago o Adelanto</h5>
                                <a href="#" class="btn btn-sm btn-outline-primary" id="btnAgregarPagosSesion">+</a>
                            </div>

                            <div id="contenedorPagosSesion">
                                <div class="row pago-item-sesion" style="padding: 15px">

                                    <div class="col-md-7">
                                        <select class="form-control metodo">
                                            <option value="efectivo">Efectivo</option>
                                            <option value="qr">QR/Deposito</option>
                                            <option value="tarjeta">Tarjeta</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <input class="form-control monto" type="number" value="0">
                                    </div>

                                    <div class="col-md-2">
                                        <a href="#" class="btn btn-sm btn-outline-danger btnEliminarPagoSesion">X</a>
                                    </div>

                                </div>
                            </div>

                            <div class="card-header d-flex justify-content-between align-items-center" style="background: #2E383F">
                                <h5 class="mb-0 text-white">
                                    Total: <span id="totalPagosSesion">0</span>
                                </h5>
                            </div>

                        </div>

                        <br>

                        <button type="submit" class="btn btn-success">
                            Guardar Sesión
                        </button>

                    </form>
                `;

                $("#CrearDivSesion").html(formHtml);
            });

            $(document).on("submit", "#formNuevaSesion", function (e) {
                e.preventDefault();

                let form = $(this);
                let tratamientoId = form.data("tratamiento-id");

                let pagos = [];

                $("#contenedorPagosSesion .pago-item-sesion").each(function () {

                    let metodo = $(this).find(".metodo").val();
                    let monto = parseFloat($(this).find(".monto").val()) || 0;

                    if (monto > 0) {
                        pagos.push({
                            metodo: metodo,
                            monto: monto
                        });
                    }
                });

                let data = form.serializeArray();
                data.push({ name: "pagos", value: JSON.stringify(pagos) });

                $.ajax({
                    url: `/tratamiento/${tratamientoId}/sesiones`,
                    type: "POST",
                    data: data,
                    success: function (response) {
                        alert("Sesión guardada correctamente");

                        $("#CrearDivSesion").html("");
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        alert("Error al guardar la sesión");
                    }
                });
            });

            $(document).on("click", "#btnAgregarPagosSesion", function (e) {
                e.preventDefault();

                let fila = `
                    <div class="row pago-item-sesion" style="padding: 15px">

                        <div class="col-md-7">
                            <select class="form-control metodo">
                                <option value="efectivo">Efectivo</option>
                                <option value="qr">QR/Deposito</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input class="form-control monto" type="number" value="0">
                        </div>

                        <div class="col-md-2">
                            <a href="#" class="btn btn-sm btn-outline-danger btnEliminarPagoSesion">X</a>
                        </div>

                    </div>
                `;

                $("#contenedorPagosSesion").append(fila);
            });

            $(document).on("click", ".btnEliminarPagoSesion", function (e) {
                e.preventDefault();

                if ($(".pago-item-sesion").length > 1) {
                    $(this).closest(".pago-item-sesion").remove();
                } else {
                    alert("Debe existir al menos un pago");
                }
            });

            $(document).on("input", "#contenedorPagosSesion .monto", function () {

                let total = 0;

                $("#contenedorPagosSesion .monto").each(function () {
                    total += parseFloat($(this).val()) || 0;
                });

                $("#totalPagosSesion").text(total.toFixed(2));
            });
/** final de session */

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

