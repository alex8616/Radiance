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
                                    <h5 class="card-title mb-0">Categoria Tratamiento</h5>
                                    <a href="#" class="btn btn-outline-primary" id="btnAgregarCategoria">Agregar</a>
                                </div>

                                <input type="text" id="searchMaterial" class="form-control mb-3" placeholder="Buscar material...">

                                <table class="table table-striped table-hover" id="TableCategoria">
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
    .fila-categoria {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fila-categoria:hover {
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
    cargarCategoria();

    $(document).on("keyup", "#searchMaterial", function () {
        let valor = $(this).val().toLowerCase();
        $("#TableCategoria tbody tr").each(function () {
            let nombre = $(this).find("td:eq(1)").text().toLowerCase();
            $(this).toggle(nombre.includes(valor));
        });
    });

    $(document).on('click', '#btnAgregarCategoria', function(e) {
        e.preventDefault();

        $('#ContenidoDiv').html(`
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 text-primary">
                        <i class="fa fa-plus me-1"></i> Nueva categoria
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" id="nombreCategoria" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea id="descripcionCategoria" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-success px-4 btnGuardarNuevoCategoria">
                            Guardar
                        </button>
                        <button class="btn btn-outline-secondary px-4 btnCancelarCategoria">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        `);
    });

    // ==========================
    // 🔹 Guardar material
    // ==========================
    $(document).on('click', '.btnGuardarNuevoCategoria', function() {
        let nombre = $('#nombreCategoria').val();
        let descripcion = $('#descripcionCategoria').val();
        let sucursales = [];

        $.ajax({
            url: '/Categoria-Tratamiento-store',
            method: 'POST',
            data: {
                nombre: nombre,
                descripcion: descripcion,
                sucursales: sucursales,
                _token: $('meta[name="csrf-token"]').attr('content') // importante para Laravel
            },
            success: function(response) {
                toastSuccess("Material creado correctamente");
                cargarCategoria();
                mostrarEstadoEspera();
            },
            error: function(err) {
                console.error(err);
                toastError("Error al guardar el material");
            }
        });
    });



    // ==========================
    // 🔹 CARGAR Categoria Tratamiento
    // ==========================
   function cargarCategoria() {
        console.log("cargando categorias");

        $.ajax({
            url: '/categoria-Tratamiento-get',
            method: 'GET',
            success: function(data) {
                let tbody = $('#TableCategoria tbody');
                tbody.empty();

                data.forEach(m => {
                    let fila = `
                        <tr class="fila-categoria"
                            data-id="${m.id}"
                            data-nombre="${m.nombre}"
                            data-descripcion="${m.descripcion ?? ''}"
                        >
                            <td>${m.id}</td>
                            <td>${m.nombre}</td>
                            <td>${m.descripcion ?? ''}</td>
                        </tr>
                    `;
                    tbody.append(fila);
                });
            },
            error: function(err) {
                console.error('Error cargando Categoria Tratamiento:', err);
            }
        });
    }

    // ==========================
    // 🔹 CLICK EN FILA (VER)
    // ==========================
    $(document).on('click', '.fila-categoria', function () {

        $('.fila-categoria').removeClass('fila-seleccionada');
        $(this).addClass('fila-seleccionada');

        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let descripcion = $(this).data('descripcion');

        $('#ContenidoDiv').html(`
            <div class="card border-0 shadow-sm rounded-3">
                
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-primary">
                        <i class="fa fa-cube me-1"></i> Categoria seleccionado
                    </h6>
                    <button 
                        class="btn btn-sm btn-outline-primary btnEditarCategoria"
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
                </div>

            </div>
        `);
    });

    // ==========================
    // 🔹 CLICK EDITAR
    // ==========================
    $(document).on("click", ".btnEditarCategoria", function () {

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
                    <button class="btn btn-success px-4 btnGuardarCategoria" data-id="${id}">
                        Guardar
                    </button>
                    <button class="btn btn-outline-secondary px-4 btnCancelarCategoria">
                        Cancelar
                    </button>
                </div>

            </div>
        `);

    });

    $(document).on('click', '.sucursal-item', function () {
        $(this).toggleClass('selected bg-primary text-white');
    });

    // ==========================
    // 🔹 GUARDAR
    // ==========================
    $(document).on("click", ".btnGuardarCategoria", function () {

        const id = $(this).data("id");

        const nombre = $('#editNombre').val();
        const descripcion = $('#editDescripcion').val();

        $.ajax({
            url: `/Categoria-Tratamiento/${id}`,
            method: 'PUT',
            data: {
                nombre: nombre,
                descripcion: descripcion
            },
            success: function () {
                toastSuccess("Actualizado correctamente");
                cargarCategoria();
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
    $(document).on("click", ".btnCancelarCategoria", function () {
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

