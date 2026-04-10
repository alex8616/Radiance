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
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Usuarios</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Usuarios</h5>
                                    <a href="#" class="btn btn-outline-primary" id="btnAgregarUsuario">Agregar</a>
                                </div>
                                <table class="table table-striped table-hover" id="TableUsuarios">
                                <thead>
                                    <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th>Sucursal</th>
                                    <th>Rol</th>
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

<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    mostrarEstadoEspera()
    cargarUsuarios()
    
    document.addEventListener("DOMContentLoaded", function() {
        const contenedor = document.getElementById("ContenidoDiv");
        const btnAgregar = document.getElementById("btnAgregarUsuario");

        btnAgregar.addEventListener("click", function(e) {
            e.preventDefault();

            // Formulario que se insertará
            const formulario = `
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Nuevo registro</h5>
                    </div>

                    <div class="card-body">
                        <form id="formUsuario">
                            <div class="form-group">
                                <label for="nombre">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>

                            <div class="form-group">
                                <label for="documento">Documento</label>
                                <input type="text" class="form-control" id="documento" name="documento" required>
                            </div>

                            <div class="form-group">
                                <label for="celular">Celular</label>
                                <input type="text" class="form-control" id="celular" name="celular" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="role">Rol</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Seleccione un rol</option>
                                    <option value="admin">Admin</option>
                                    <option value="doctor">Doctor</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Repetir Contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <!-- Checkbox para mostrar/ocultar -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="mostrarPasswords">
                                <label class="form-check-label" for="mostrarPasswords">
                                    Mostrar contraseñas
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="btnGuardar" disabled>
                                Guardar
                            </button>
                        </form>
                    </div>
                </div>
            `;

            // Insertar el formulario en tu contenedor
            $("#ContenidoDiv").html(formulario);

            // Mostrar/ocultar ambas contraseñas
            document.getElementById("mostrarPasswords").addEventListener("change", function() {
                const pass = document.getElementById("password");
                const passConfirm = document.getElementById("password_confirmation");

                if (this.checked) {
                    pass.type = "text";
                    passConfirm.type = "text";
                } else {
                    pass.type = "password";
                    passConfirm.type = "password";
                }
            });

            // Validar contraseñas iguales y habilitar botón
            function validarPasswords() {
                const pass = document.getElementById("password").value;
                const passConfirm = document.getElementById("password_confirmation").value;
                const btnGuardar = document.getElementById("btnGuardar");

                if (pass && passConfirm && pass === passConfirm) {
                    btnGuardar.disabled = false;
                } else {
                    btnGuardar.disabled = true;
                }
            }

            document.getElementById("password").addEventListener("input", validarPasswords);
            document.getElementById("password_confirmation").addEventListener("input", validarPasswords);


            // Enviar formulario por AJAX
            $("#formUsuario").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/usuarios-create',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(resp) {
                        toastSuccess("Usuario creado correctamente");
                        mostrarEstadoEspera();
                        cargarUsuarios();
                        $("#btnGuardar").prop("disabled", true);
                    },
                    error: function(err) {
                        console.error("Error al crear usuario:", err);
                        toastError("Hubo un problema al crear el usuario");
                    }
                });
            });
        });
    });
    
    function cargarUsuarios() {
        $.ajax({
            url: "/usuarios-get",
            type: "GET",
            dataType: "json",
            success: function(data) {
                let filas = "";

                data.forEach(function(item) {
                    filas += `
                        <tr data-id="${item.id}">
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td>${item.ci}</td>
                            <td>${item.celular}</td>
                            <td>${item.email}</td>
                            <td>-</td> <!-- No viene sucursal en el JSON -->
                            <td>${item.role}</td>
                        </tr>
                    `;
                });

                $("#TableUsuarios tbody").html(filas);
            },
            error: function(err) {
                console.error("Error al cargar usuarios:", err);
                toastError("No se pudieron cargar los usuarios");
            }
        });
    }

    $(document).on("click", "#TableUsuarios tbody tr", function() {
        const id        = $(this).data("id");
        const nombre    = $(this).find("td:eq(1)").text();
        const documento = $(this).find("td:eq(2)").text();
        const celular   = $(this).find("td:eq(3)").text();
        const correo    = $(this).find("td:eq(4)").text();
        const sucursal  = $(this).find("td:eq(5)").text();
        const rol       = $(this).find("td:eq(6)").text();

        $("#TableUsuarios tbody tr").removeClass("table-active");
        $(this).addClass("table-active");

        $("#ContenidoDiv").html(`
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detalle de Usuario</h5>
                        <div class="btn-group" role="group">
                            <a type="button" class="btn mb-2 btn-primary" onclick="editarUsuario(${id})">
                                <i class="fe fe-edit-3" style="color: white"></i> 
                            </a>
                            <a type="button" class="btn mb-2 btn-danger" onclick="eliminarUsuario(${id})">
                                <i class="fe fe-delete" style="color: white"></i> 
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2"><div class="col-md-6 text-muted">ID:</div><div class="col-md-6">${id}</div></div>
                    <div class="row mb-2"><div class="col-md-6 text-muted">Nombre:</div><div class="col-md-6">${nombre}</div></div>
                    <div class="row mb-2"><div class="col-md-6 text-muted">Documento:</div><div class="col-md-6">${documento}</div></div>
                    <div class="row mb-2"><div class="col-md-6 text-muted">Celular:</div><div class="col-md-6">${celular}</div></div>
                    <div class="row mb-2"><div class="col-md-6 text-muted">Correo:</div><div class="col-md-6">${correo}</div></div>
                    <div class="row mb-2"><div class="col-md-6 text-muted">Sucursal:</div><div class="col-md-6">${sucursal}</div></div>
                    <div class="row mb-2"><div class="col-md-6 text-muted">Rol:</div><div class="col-md-6">${rol}</div></div>

                    <!-- Bloque adicional -->
                    <div class="mt-4">
                        <label class="font-weight-bold text-primary">Asignar sucursales:</label>
                        <p class="text-muted">Aquí puedes asignar nuevas sucursales a este usuario.</p>
                        <button class="btn btn-outline-info btn-sm" onclick="mostrarFormularioAsignar(${id})">
                            <i class="fe fe-plus"></i> Asignar
                        </button>
                    </div>

                    <!-- Tabla de sucursales asignadas -->
                    <div class="mt-4">
                        <h6>Sucursales asignadas</h6>
                        <table class="table table-sm table-bordered" id="TableSucursalesAsignadas">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenarán las sucursales asignadas -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `);

        cargarSucursalesAsignadas(id);
    });

    function cargarSucursalesAsignadas(userId) {
        $.ajax({
            url: "/usuarios-sucursales/" + userId,
            type: "GET",
            dataType: "json",
            success: function(resp) {

                let sucursales = resp.data; // 🔥 aquí está la data real

                let filas = "";

                // ✅ Si no tiene sucursales
                if (!sucursales || sucursales.length === 0) {
                    filas = `
                        <tr>
                            <td colspan="3" class="text-center">
                                No tiene sucursales asignadas
                            </td>
                        </tr>
                    `;
                } else {
                    // ✅ Si tiene datos
                    filas = sucursales.map(s => `
                        <tr>
                            <td>${s.id}</td>
                            <td>${s.nombre}</td>
                            <td>${s.direccion}</td>
                        </tr>
                    `).join("");
                }

                $("#TableSucursalesAsignadas tbody").html(filas);
            },
            error: function(err) {
            }
        });
    }

    function mostrarFormularioAsignar(userId) {
        $.ajax({
            url: "/sucursales-get",
            type: "GET",
            dataType: "json",
            success: function(sucursales) {
                let options = sucursales.map(s => `<option value="${s.id}">${s.nombre} - ${s.direccion}</option>`).join("");

                $("#ContenidoDiv").append(`
                    <div class="card mt-3" style="padding: 15px; background-color: #FFF1D3;">
                        <div class="card-header">Asignar sucursal</div>
                        <div class="card-body">
                            <form id="formAsignarSucursal">
                                <input type="hidden" name="user_id" value="${userId}">
                                <div class="form-group">
                                    <label for="sucursal_id">Seleccione sucursal</label>
                                    <select class="form-control" id="sucursal_id" name="sucursal_id" required>
                                        <option value="">-- Seleccione --</option>
                                        ${options}
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">Guardar asignación</button>
                            </form>
                        </div>
                    </div>
                `);

                // Manejo del submit
                $("#formAsignarSucursal").on("submit", function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "/usuarios-asignar-sucursal",
                        type: "POST",
                        data: $(this).serialize(),
                        success: function(resp) {
                            toastSuccess("Sucursal asignada correctamente");
                            $("#formAsignarSucursal").closest(".card").remove();
                            cargarSucursalesAsignadas(userId);
                        },
                        error: function(err) {
                            console.error("Error al asignar sucursal:", err);
                            toastError("Hubo un problema al asignar la sucursal");
                        }
                    });
                });
            },
            error: function(err) {
                console.error("Error al obtener sucursales:", err);
                toastError("No se pudieron cargar las sucursales");
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
                            cargarUsuarios()
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

