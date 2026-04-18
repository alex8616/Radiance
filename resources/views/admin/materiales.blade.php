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

    /* Opción 1: Cards */
    .sucursal-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .sucursal-card.selected {
        background-color: #0d6efd;
        color: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    /* Opción 2: Badges */
    .sucursal-badge {
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 20px;
        border: 1px solid #ccc;
    }
    .sucursal-badge.selected {
        background-color: #198754;
        color: white;
        border-color: #198754;
    }

    /* Opción 3: Lista con check */
    .sucursal-list-item {
        cursor: pointer;
    }
    .sucursal-list-item.selected {
        background-color: #e9f7ef;
    }

    .sucursal-item {
        transition: 0.2s;
    }

    .sucursal-item:hover {
        transform: scale(1.03);
    }

    .sucursal-item.selected {
        background-color: #0d6efd;
        color: white;
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
                    <div class="sucursal-list-item d-flex align-items-center p-2 border-bottom" 
                        data-id="${s.id}" style="width: 150px; border-radius: 5px; margin-right: 10px;">
                        <span class="nombre flex-grow-1">${s.nombre}</span>
                        <span class="check-icon d-none text-success fw-bold">✔️</span>
                    </div>
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
                            <div id="sucursalesContainer" class="d-flex flex-wrap">
                                ${options}
                            </div>
                            <small class="text-muted">
                                Haz clic en las sucursales donde estará disponible este material
                            </small>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-success px-4 btnGuardarNuevoMaterial">
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

    // ==========================
    // 🔹 Toggle selección
    // ==========================
    $(document).on('click', '.sucursal-card', function() {
        $(this).toggleClass('selected');
    });
    $(document).on('click', '.sucursal-badge', function() {
        $(this).toggleClass('selected');
    });
    $(document).on('click', '.sucursal-list-item', function() {
        $(this).toggleClass('selected');
        $(this).find('.check-icon').toggleClass('d-none');
    });

    // ==========================
    // 🔹 Guardar material
    // ==========================
    $(document).on('click', '.btnGuardarNuevoMaterial', function() {
        let nombre = $('#nombreMaterial').val();
        let descripcion = $('#descripcionMaterial').val();
        let sucursales = [];

        $('#sucursalesContainer .selected').each(function() {
            sucursales.push($(this).data('id'));
        });

        $.ajax({
            url: '/materiales-store',
            method: 'POST',
            data: {
                nombre: nombre,
                descripcion: descripcion,
                sucursales: sucursales,
                _token: $('meta[name="csrf-token"]').attr('content') // importante para Laravel
            },
            success: function(response) {
                toastSuccess("Material creado correctamente");
                cargarMateriales();
                mostrarEstadoEspera();
            },
            error: function(err) {
                console.error(err);
                toastError("Error al guardar el material");
            }
        });
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

                    // 🔥 texto simple para tabla
                    let sucursalesTexto = '—';

                    if (m.sucursales && m.sucursales.length > 0) {
                        sucursalesTexto = m.sucursales.map(s => s.nombre).join(', ');
                    }

                    let fila = `
                        <tr class="fila-material"
                            data-id="${m.id}"
                            data-nombre="${m.nombre}"
                            data-descripcion="${m.descripcion ?? ''}"
                            data-sucursales='${JSON.stringify(m.sucursales)}'>
                            
                            <td>${m.id}</td>
                            <td>${m.nombre}</td>
                            <td>${m.descripcion ?? ''}</td>
                            <td>${sucursalesTexto}</td>
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

        let sucursalesArr = JSON.parse($(this).attr('data-sucursales'));

        // 🔥 UI sucursales
        let sucursalesHTML = `
            <a href="#" class="btn btn-sm btn-outline-primary mt-2 btnAsignarSucursales" data-id="${id}">
                <i class="fa fa-plus"></i> Asignar sucursales
            </a>
        `;

        if (sucursalesArr.length > 0) {
            sucursalesHTML = `
                <div class="d-flex flex-wrap mb-2">
                    ${sucursalesArr.map(s => `
                        <span class="badge rounded-pill bg-primary text-white px-3 py-2 me-2 mb-2" style="font-size: 0.9rem;">
                            <i class="fa fa-building me-1"></i> ${s.nombre}
                        </span>
                    `).join('')}
                </div>

                <a href="#" class="btn btn-sm btn-outline-secondary btnAsignarSucursales" data-id="${id}">
                    <i class="fa fa-edit"></i> Editar sucursales
                </a>
            `;
        }

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
                    <h4 class="fw-bold mb-2">${nombre}</h4>
                    <hr>

                    <p class="mb-3 text-secondary" style="font-size: 1.05rem;">
                        ${descripcion || 'Sin descripción disponible'}
                    </p>

                    <div class="mt-3">
                        <small class="text-muted d-block mb-2" style="font-size: 0.95rem;">
                            <i class="fa fa-building me-1"></i> Sucursales
                        </small>

                        ${sucursalesHTML}
                    </div>
                </div>

            </div>
        `);
    });

    $(document).on('click', '.btnAsignarSucursales', function(e) {
        e.preventDefault();

        let materialId = $(this).data('id');

        // 🔥 cargar sucursales
        $.get('/sucursales-get', function(data) {

            let htmlSucursales = '';

            data.forEach(s => {
                htmlSucursales += `
                    <div class="col-6 mb-2">
                        <div class="card border p-2 text-center sucursal-item" 
                            data-id="${s.id}" 
                            style="cursor:pointer;">
                            ${s.nombre}
                        </div>
                    </div>
                `;
            });

            $('#ContenidoDiv').html(`
                <div class="card border-0 shadow-sm rounded-3">
                    
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 text-primary">
                            <i class="fa fa-building me-1"></i> Asignar sucursales
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="row" id="sucursalesContainer">
                            ${htmlSucursales}
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-success btnGuardarSucursales" data-id="${materialId}">
                                Guardar
                            </button>
                            <button class="btn btn-outline-secondary btnCancelarMaterial">
                                Cancelar
                            </button>
                        </div>
                    </div>

                </div>
            `);
        });
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

    $(document).on('click', '.sucursal-item', function () {
        $(this).toggleClass('selected bg-primary text-white');
    });

    $(document).on('click', '.btnGuardarSucursales', function () {
        let producto_id = $(this).data('id');
        let sucursales = [];

        $('#sucursalesContainer .selected').each(function () {
            sucursales.push($(this).data('id'));
        });

        $.ajax({
            url: '/materiales/asignar-sucursales',
            method: 'POST',
            data: {
                producto_id: producto_id,
                sucursales: sucursales,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                toastSuccess("Asignación de sucursales actualizada");
                cargarMateriales();
                mostrarEstadoEspera();
            },
            error: function (err) {
                console.error(err);
                toastError("Error al asignar sucursales");
            }
        });

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

