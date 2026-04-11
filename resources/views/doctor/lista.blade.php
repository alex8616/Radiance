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
                        <div class="col-md-5 bg-light" style="padding: 15px;">
                            <div class="w-20 mx-auto text-center justify-content-center py-8 my-8">
                                <h2 class="page-title mb-0" style="padding: 15px">Buscar Paciente (C.I, Nombre)</h2>  
                                <form class="searchform searchform-lg">
                                    <input id="buscarPaciente" class="form-control form-control-lg bg-white rounded-pill pl-5" type="search" placeholder="Buscar paciente . . ." aria-label="Search">
                                </form>
                            </div>
                                <div id="DivResultadoPaciente" class="mt-4">
                                
                            </div>
                        </div>

                        <div class="col-md-7 bg-light" style="padding: 15px;">
                            <div class="card shadow" id="ContenidoDivPaciente" style="padding: 15px;">
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> 
                    <div class="row">
                        <div class="col-md-8 bg-light" style="padding: 15px;">
                            <div class="card shadow">
                                <div class="card-body">
                                   <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Usuarios</h5>
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
                                        
                                    </tbody>
                                    </table>
                                </div>
                            </div>  
                        </div>

                        <div class="col-md-4 bg-light" style="padding: 15px;">
                            <div class="card shadow" id="ContenidoDiv" style="padding: 15px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    $(document).ready(function () {
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

            // Evitar consultas muy cortas
            if (query.length < 2) {
                $("#DivResultadoPaciente").html("");
                return;
            }

            // 🔥 MOSTRAR LOADING INMEDIATO
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
                                                    data-nombre="${p.nombre}"
                                                    data-apellido-paterno="${p.apellido_paterno}"
                                                    data-apellido-materno="${p.apellido_materno}"
                                                    data-ci="${p.ci}">
                                                    Ver
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

        // 🔥 EVENTO CLICK EN "VER"
        $(document).on("click", ".btnVerPaciente", function() {

            const nombre = $(this).data("nombre");
            const apPaterno = $(this).data("apellido-paterno");
            const apMaterno = $(this).data("apellido-materno");
            const ci = $(this).data("ci");

            const nombreCompleto = `${nombre} ${apPaterno} ${apMaterno}`;

            $("#ContenidoDivPaciente").html(`
                <div class="card shadow-sm border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Detalle del Paciente</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4 font-weight-bold text-muted">Nombre:</div>
                            <div class="col-md-8">${nombreCompleto}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 font-weight-bold text-muted">C.I:</div>
                            <div class="col-md-8">${ci}</div>
                        </div>
                    </div>
                </div>
            `);

        });

    }
</script>
<script>
    $(document).on("click", ".btnRegistrarPaciente", function() {

        // 🔥 Obtener valor del input de búsqueda
        const valorBusqueda = $("#buscarPaciente").val();

        // 🔥 Cambiar al tab
        $('#profile-tab').tab('show');

        // 🔥 Insertar formulario con valor ya cargado
        $("#ContenidoDiv").html(`
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Registrar Paciente</h5>
                </div>
                <div class="card-body">
                    <form id="formPaciente">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>

                        <div class="form-group">
                            <label>Apellido Paterno</label>
                            <input type="text" class="form-control" name="apellido_paterno" required>
                        </div>

                        <div class="form-group">
                            <label>Apellido Materno</label>
                            <input type="text" class="form-control" name="apellido_materno">
                        </div>

                        <div class="form-group">
                            <label>C.I</label>
                            <input type="text" class="form-control" name="ci" value="${valorBusqueda}" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
        `);

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
@endsection

