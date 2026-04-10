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
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Sucursales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Maps</a>
                    </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Sucursales</h5>
                                    <a href="#" class="btn btn-outline-primary" id="btnAgregarSucursal">Agregar</a>
                                </div>
                                <table class="table table-striped table-hover" id="TableSucursales">
                                <thead>
                                    <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th>Celular</th>
                                    <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card shadow">
                            <div id="DivMapa" style="height: 400px;"></div>
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

<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    mostrarEstadoEspera()
    cargarSucursales()
    
    document.addEventListener("DOMContentLoaded", function() {
        const contenedor = document.getElementById("ContenidoDiv");
        const btnAgregar = document.getElementById("btnAgregarSucursal");

        btnAgregar.addEventListener("click", function(e) {
            e.preventDefault();

            // Formulario que se insertará
            const formulario = `
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Nuevo registro</h5>
                    </div>

                    <!-- Body (formulario) -->
                    <div class="card-body">
                        <form id="formSucursal">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>

                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>

                            <div class="form-group">
                                <label for="phone">Ubicacion</label>
                                <input type="text" class="form-control" id="Ubicacion" name="Ubicacion" placeholder="-19.588735209869807, -65.75420436794899">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Guardar
                            </button>
                        </form>
                    </div>
                </div>
            `;

            contenedor.innerHTML = formulario;

            // Evento submit usando delegación (IMPORTANTE)
            $(document).on("submit", "#formSucursal", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "/sucursales-create",
                    type: "POST",
                    data: {
                        nombre: $("#nombre").val(),
                        direccion: $("#direccion").val(),
                        telefono: $("#telefono").val(),
                        ubicacion: $("#Ubicacion").val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        mostrarEstadoEspera();
                        cargarSucursales()
                        toastSuccess("Registro guardado correctamente");
                    },
                    error: function(error) {
                        console.error(error);
                        toastError("Ocurrió un error al guardar");
                    }
                });
            });
        });
    });
    
    function cargarSucursales() {
        $.ajax({
            url: "/sucursales-get",
            type: "GET",
            success: function(data) {

                let filas = "";

                data.forEach(function(item) {


                    filas += `
                        <tr 
                            data-id="${item.id}"
                            data-lat="${item.latitud}"
                            data-lng="${item.longitud}"
                        >
                            <td>${item.id}</td>
                            <td>${item.nombre}</td>
                            <td>${item.direccion}</td>
                            <td>${item.telefono || '-'}</td>
                            <td>${item.created_at.split('T')[0]}</td>
                        </tr>
                    `;
                });

                $("#TableSucursales tbody").html(filas);
            }
        });
    }

    $(document).on("click", "#TableSucursales tbody tr", function() {

        const id = $(this).data("id");

        // 🔥 convertir a número (IMPORTANTE)
        const lat = parseFloat($(this).data("lat"));
        const lng = parseFloat($(this).data("lng"));


        // 🚨 validar coordenadas
        if (isNaN(lat) || isNaN(lng)) {
            console.error("Coordenadas inválidas:", lat, lng);
            alert("Esta sucursal no tiene ubicación válida");
            return;
        }

        const nombre = $(this).find("td:eq(1)").text();
        const direccion = $(this).find("td:eq(2)").text();
        const telefono = $(this).find("td:eq(3)").text();
        const fecha = $(this).find("td:eq(4)").text();

        let fechaConvertido = new Date(fecha).toISOString().split('T')[0];

        $("#TableSucursales tbody tr").removeClass("table-active");
        $(this).addClass("table-active");

        $("#ContenidoDiv").html(`
            <div class="card">
                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detalle de Sucursal</h5>

                        <div class="btn-group" role="group">
                            <a type="button" class="btn mb-2 btn-primary" onclick="editarSucursal(${id})">
                                <i class="fe fe-edit-3" style="color: white"></i> 
                            </a>
                            <a type="button" class="btn mb-2 btn-danger" onclick="eliminarSucursal(${id})">
                                <i class="fe fe-delete" style="color: white"></i> 
                            </a>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6 font-weight-bold text-muted">ID:</div>
                        <div class="col-md-6">${id}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 font-weight-bold text-muted">Nombre:</div>
                        <div class="col-md-6">${nombre}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 font-weight-bold text-muted">Dirección:</div>
                        <div class="col-md-6">${direccion}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 font-weight-bold text-muted">Teléfono:</div>
                        <div class="col-md-6">${telefono || '-'}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 font-weight-bold text-muted">Fecha registro:</div>
                        <div class="col-md-6">${fechaConvertido}</div>
                    </div>

                    <!-- MAPA -->
                    <div id="map" style="height: 300px;"></div>
                </div>
            </div>
        `);

        // 🧠 Esperar a que el DOM renderice
        setTimeout(() => {

            // 🔥 eliminar mapa anterior si existe (evita errores)
            if (window.mapa) {
                window.mapa.remove();
            }

            // Crear mapa
            window.mapa = L.map('map').setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(window.mapa);

            L.marker([lat, lng]).addTo(window.mapa)
                .bindPopup(nombre)
                .openPopup();

        }, 100);
    });

    function editarSucursal(id) {
        $.ajax({
            url: '/sucursales-get/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let formulario = `
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Editar ${data.nombre}</h5>
                        </div>
                        <div class="card-body">
                            <form id="formEditarSucursal">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="${data.id}">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="${data.nombre}">
                                </div>
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="${data.direccion}">
                                </div>
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="${data.telefono}">
                                </div>
                                <div class="mb-3">
                                    <label for="latitud" class="form-label">Latitud</label>
                                    <input type="text" class="form-control" id="latitud" name="latitud" value="${data.latitud}">
                                </div>
                                <div class="mb-3">
                                    <label for="longitud" class="form-label">Longitud</label>
                                    <input type="text" class="form-control" id="longitud" name="longitud" value="${data.longitud}">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
                            </form>
                        </div>
                    </div>
                `;

                $("#ContenidoDiv").html(formulario);

                $("#formEditarSucursal").on("submit", function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: '/sucursales-update/' + data.id,
                        type: 'POST', // Laravel interpreta PUT gracias al hidden _method
                        data: $(this).serialize(),
                        success: function(resp) {
                            mostrarEstadoEspera();
                            cargarSucursales()
                            toastSuccess("Registro Actualizado correctamente");
                        },
                        error: function(err) {
                            toastError("Ocurrió un error al guardar");
                        }
                    });
                });
            },
            error: function(err) {
                console.error("Error al obtener sucursal:", err);
            }
        });
    }

    function eliminarSucursal(id) {
        toastConfirm("¿Seguro que deseas eliminar esta sucursal?", 
            function() {
                // Acción si el usuario confirma
                $.ajax({
                    url: '/sucursales-delete/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE' },
                    success: function(resp) {
                        if(resp.success) {
                            toastSuccess(resp.message);
                            mostrarEstadoEspera();
                            cargarSucursales()
                        } else {
                            toastWarning(resp.message);
                        }
                    },
                    error: function(err) {
                        console.error("Error:", err);
                        toastError("Hubo un problema al eliminar la sucursal");
                    }
                });
            },
            function() {
                // Acción si el usuario cancela
                toastWarning("Eliminación cancelada");
            }
        );
    }

    
    function cargarMapaSucursales() {
        $.ajax({
            url: "/sucursales-get",
            type: "GET",
            success: function(data) {

                if (window.mapaGeneral) {
                    window.mapaGeneral.remove();
                }

                window.mapaGeneral = L.map('DivMapa').setView([-19.58, -65.75], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(window.mapaGeneral);

                const bounds = [];

                data.forEach(function(item) {

                    const lat = parseFloat(item.latitud);
                    const lng = parseFloat(item.longitud);

                    if (!isNaN(lat) && !isNaN(lng)) {

                        const marker = L.marker([lat, lng]).addTo(window.mapaGeneral);

                        // 🔥 Tooltip siempre visible
                        marker.bindTooltip(`
                            <b>${item.nombre}</b><br>
                            ${item.direccion}
                        `, {
                            permanent: true,
                            direction: 'top',
                            offset: [0, -10]
                        });

                        bounds.push([lat, lng]);
                    }
                });

                if (bounds.length > 0) {
                    window.mapaGeneral.fitBounds(bounds, { padding: [50, 50] });
                }

                // 🔥 SOLUCIÓN CLAVE
                setTimeout(() => {
                    window.mapaGeneral.invalidateSize();
                }, 200);
            }
        });
    }
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

