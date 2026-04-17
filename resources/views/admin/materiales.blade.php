@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 bg-light" style="padding: 15px;">
            <div class="card shadow">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Materials</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Materiales</h5>
                                    <a href="#" class="btn btn-outline-primary" id="btnAgregarMaterial">Agregar</a>
                                </div>

                                <input type="text" id="searchMaterial" class="form-control mb-3" placeholder="Buscar material...">

                                <table class="table table-striped table-hover" id="TableMaterial">
                                <thead>
                                    <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>            
        </div>

        <div class="col-md-4 bg-light" style="padding: 15px;">
            <div class="card shadow" id="ContenidoDiv" style="padding: 15px;">
            </div>
        </div>
    </div>
</div>

<style>
    .fila-material {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fila-material:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }

    .fila-seleccionada {
        background-color: #e7f1ff !important;
        border-left: 4px solid #0d6efd;
    }
</style>
<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    mostrarEstadoEspera()
    cargarMateriales();

    $(document).on("keyup", "#searchMaterial", function () {
        let valor = $(this).val().toLowerCase();
        $("#TableMaterial tbody tr").each(function () {
            let nombre = $(this).find("td:eq(1)").text().toLowerCase();
            $(this).toggle(nombre.includes(valor));
        });
    });

    $(document).on('click', '#btnAgregarMaterial', function(e) {
        e.preventDefault();
        $.get('/sucursales-get', function(sucursales) {
            let options = '';
            sucursales.forEach(s => {
                options += `
                    <option value="${s.id}">
                        ${s.nombre}
                    </option>
                `;
            });

            $('#ContenidoDiv').html(`
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 text-primary">
                            <i class="fa fa-plus me-1"></i> Nuevo material
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" id="nombreMaterial" class="form-control" placeholder="Ej: Guantes">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea id="descripcionMaterial" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sucursales</label>
                            <select id="sucursalesMaterial" class="form-select" multiple>
                                ${options}
                            </select>
                            <small class="text-muted">
                                Selecciona donde estará disponible este material
                            </small>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-success px-4 btnGuardarMaterial">
                                Guardar
                            </button>
                            <button class="btn btn-outline-secondary px-4 btnCancelarMaterial">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            `);

        });

    });

    $(document).on('click', '.btnCancelarMaterial', function() {
        $('#ContenidoDiv').html(`
            <div class="text-center text-muted py-4">
                Selecciona o agrega un material
            </div>
        `);
    });

    $(document).on('click', '.btnGuardarMaterial', function() {
        let nombre = $('#nombreMaterial').val();
        let descripcion = $('#descripcionMaterial').val();
        let sucursales = $('#sucursalesMaterial').val(); 
        console.log(nombre, descripcion, sucursales);
    });

    // ==========================
    // 🔹 CARGAR MATERIALES
    // ==========================
    function cargarMateriales() {
        $.ajax({
            url: '/materiales-get',
            method: 'GET',
            success: function(data) {
                let tbody = $('#TableMaterial tbody');
                tbody.empty();

                data.forEach(m => {
                    let fila = `
                        <tr class="fila-material"
                            data-id="${m.id}"
                            data-nombre="${m.nombre}"
                            data-descripcion="${m.descripcion ?? ''}">
                            
                            <td>${m.id}</td>
                            <td>${m.nombre}</td>
                            <td>${m.descripcion ?? ''}</td>
                        </tr>
                    `;
                    tbody.append(fila);
                });
            },
            error: function(err) {
                console.error('Error cargando materiales:', err);
            }
        });
    }


    // ==========================
    // 🔹 CLICK EN FILA (VER)
    // ==========================
    $(document).on('click', '.fila-material', function () {

        $('.fila-material').removeClass('fila-seleccionada');
        $(this).addClass('fila-seleccionada');

        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let descripcion = $(this).data('descripcion');

        $('#ContenidoDiv').html(`
            <div class="card border-0 shadow-sm rounded-3">
                
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-primary">
                        <i class="fa fa-cube me-1"></i> Material seleccionado
                    </h6>
                    <button 
                        class="btn btn-sm btn-outline-primary btnEditarMaterial"
                        data-id="${id}"
                        data-nombre="${nombre}"
                        data-descripcion="${descripcion}">
                        <i class="fa fa-edit"></i> Editar
                    </button>
                </div>

                <div class="card-body">
                    <h5 class="fw-bold mb-1">${nombre}</h5>
                    <hr>
                    <p class="mb-3 text-secondary">
                        ${descripcion || 'Sin descripción disponible'}
                    </p>
                </div>

            </div>
        `);
    });


    // ==========================
    // 🔹 CLICK EDITAR
    // ==========================
    $(document).on("click", ".btnEditarMaterial", function () {

        const id = $(this).data("id");
        const nombre = $(this).data("nombre");
        const descripcion = $(this).data("descripcion");

        $('#ContenidoDiv').html(`
            <div class="card shadow p-3">

                <h5 class="mb-3 text-primary">
                    <i class="fa fa-edit"></i> Editar Material
                </h5>

                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" class="form-control" id="editNombre" value="${nombre}">
                </div>

                <div class="mb-3">
                    <label>Descripción</label>
                    <textarea class="form-control" id="editDescripcion">${descripcion}</textarea>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-success px-4 btnGuardarMaterial" data-id="${id}">
                        Guardar
                    </button>
                    <button class="btn btn-outline-secondary px-4 btnCancelarMaterial">
                        Cancelar
                    </button>
                </div>

            </div>
        `);

    });


    // ==========================
    // 🔹 GUARDAR
    // ==========================
    $(document).on("click", ".btnGuardarMaterial", function () {

        const id = $(this).data("id");

        const nombre = $('#editNombre').val();
        const descripcion = $('#editDescripcion').val();

        $.ajax({
            url: `/materiales/${id}`,
            method: 'PUT',
            data: {
                nombre: nombre,
                descripcion: descripcion
            },
            success: function () {
                toastSuccess("Actualizado correctamente");
                cargarMateriales();
                mostrarEstadoEspera()
            },
            error: function (err) {
                console.error(err);
            }
        });

    });


    // ==========================
    // 🔹 CANCELAR
    // ==========================
    $(document).on("click", ".btnCancelarMaterial", function () {
                        mostrarEstadoEspera()
    });
</script>
<script>
    $(document).on("click", "#home-tab", function () {
        mostrarEstadoEspera();
    });

    $(document).on("click", "#profile-tab", function () {
        mostrarEstadoEspera();
        cargarMapaSucursales(); 
    });
</script>
@endsection

