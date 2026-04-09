@extends('layouts.my-dashboard-layout')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 bg-light">
            <div class="card shadow">
                <div class="card-body">
                     <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Sucursales</h5>
                        <a href="#" class="btn btn-outline-primary" id="btnAgregarSucursal">Agregar</a>
                    </div>
                    <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4 bg-light">
            <div class="card shadow" id="ContenidoDiv">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const contenedor = document.getElementById("ContenidoDiv");

        // HTML con la imagen y el texto "En espera"
        const contenidoEspera = `
            <div class="d-flex flex-column justify-content-center align-items-center" style="height:auto;">
                <img src="/svg/grid.svg" alt="En espera" style="width:60%; height:auto;">
                <span class="mt-3 text-muted h5">En espera</span>
            </div>
        `;

        // Si el div está vacío, insertamos la imagen y el texto
        if (!contenedor.innerHTML.trim()) {
            contenedor.innerHTML = contenidoEspera;
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const contenedor = document.getElementById("ContenidoDiv");
        const btnAgregar = document.getElementById("btnAgregarSucursal");

        btnAgregar.addEventListener("click", function(e) {
            e.preventDefault();

            // Formulario que se insertará
            const formulario = `
                <div class="card-body">
                    <h5 class="card-title">Nuevo registro</h5>
                    <form id="formSucursal">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Dirección</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Fecha</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            `;

            // Insertar el formulario en el div
            contenedor.innerHTML = formulario;
        });
    });
</script>
@endsection

