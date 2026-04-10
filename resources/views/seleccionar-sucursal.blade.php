<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Sucursal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body style="background-color:#f4f6f9;">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card" style="width:400px;">
        <div class="card-header text-center">
            <strong>Seleccione su sucursal</strong>
        </div>
        <div class="card-body">

            <select id="sucursal_id" class="form-control mb-3">
                <option value="">Cargando...</option>
            </select>

            <button id="btnSeleccionar" class="btn btn-primary btn-block">
                Continuar
            </button>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    // 🔥 Cargar sucursales del doctor
    $.ajax({
        url: "/doctor-sucursales",
        type: "GET",
        success: function(resp){

            let opciones = "";

            if(resp.data.length === 0){
                opciones = `<option value="">No tienes sucursales asignadas</option>`;
            }else{
                opciones = `<option value="">-- Seleccione --</option>`;
                resp.data.forEach(s => {
                    opciones += `<option value="${s.id}">${s.nombre}</option>`;
                });
            }

            $("#sucursal_id").html(opciones);
        }
    });

    // 🔥 Guardar selección
    $("#btnSeleccionar").click(function(){

        let sucursal_id = $("#sucursal_id").val();

        if(!sucursal_id){
            alert("Debe seleccionar una sucursal");
            return;
        }

        $.ajax({
            url: "/seleccionar-sucursal",
            type: "POST",
            data: {
                sucursal_id: sucursal_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(){
                // 🔥 Redirigir al dashboard
                window.location.href = "/dashboard";
            },
            error: function(){
                alert("Error al seleccionar sucursal");
            }
        });
    });

});
</script>

</body>
</html>