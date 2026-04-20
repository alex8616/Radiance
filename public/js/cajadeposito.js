$(document).ready(function () {
    FechaSelectCajaDeposito();
    InputRangoFechasControl();
    mostrarEstadoEsperaDeposito();

    document
        .getElementById("addcajaDepositos")
        .addEventListener("click", function () {
            var detalleDiv = document.getElementById(
                "detalleMovimientoCajaDeposito",
            );

            let formHtml = `
                <form id="form-register-egreso">
                    <div class="card-header" style="background: rgb(24, 36, 51); color: white">
                        <div class="row" style="width: 100%;">
                            <div class="col-12 col-sm-8">
                                <h4 class="card-title" style="color: white">REGISTRAR</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Categoría</label>
                            <select id="categoriaEgreso" class="form-control" style="width: 100%;">
                                <option value="Gasto">Gasto</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Descripción</label>
                            <textarea id="descripcionEgreso" rows="3" class="form-control convertmayusculas"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Monto</label>
                            <input type="number" id="montoEgreso" class="form-control" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex" style="text-align: right">
                            <button type="button" class="btn me-auto" id="btn-cancelar-egreso">CANCELAR</button>
                            <button type="button" class="btn btn-primary" id="btn-registrar-egreso">GUARDAR</button>
                        </div>
                    </div>
                </form>
            `;

            detalleDiv.innerHTML = formHtml;

            // Acción de cancelar
            document
                .getElementById("btn-cancelar-egreso")
                .addEventListener("click", function () {
                    detalleDiv.innerHTML = "";
                });

            // Acción de guardar
            $("#btn-registrar-egreso").on("click", function () {
                let egreso = {
                    categoria: $("#categoriaEgreso").val(),
                    descripcion: $("#descripcionEgreso").val(),
                    monto: $("#montoEgreso").val(),
                    metodo_pago: $("#metodoPagoEgreso").val(),
                    factura: $("#facturaEgreso").length
                        ? $("#facturaEgreso").val()
                        : null,
                    tipo: "Egreso",
                    fecha: new Date().toISOString().slice(0, 10), // YYYY-MM-DD
                };

                $.ajax({
                    url: "/movimientos-caja/egreso",
                    type: "POST",
                    data: egreso,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content",
                        ),
                    },
                    success: function (data) {
                        console.log("Guardado correctamente:", data);
                        $("#detalleMovimientoCajaDeposito").html("");
                    },
                    error: function (xhr, status, error) {
                        console.error(
                            "Error al guardar egreso:",
                            xhr.responseText,
                        );
                        alert("Error al registrar el egreso");
                    },
                });
            });
        });

    function MostrarCajaDeposito(response) {
        var tbody = $("#tabla-caja-Deposito tbody");
        tbody.empty();

        // Actualizar totales generales
        $("#IngresoDeposito").text(response.IngresoDeposito);
        $("#TotalDeposito").text(
            parseFloat(response.IngresoDeposito).toFixed(2),
        );

        let saldoAcumulado = 0; // inicializamos el saldo acumulado

        // Recorrer movimientos de caja
        response.CajaDeposito.forEach(function (item, index) {
            let colorBorde = item.tipo === "Ingreso" ? "green" : "red";
            let ingresoColor = item.tipo === "Ingreso" ? "green" : "#2C3333";
            let egresoColor = item.tipo === "Egreso" ? "red" : "#2C3333";

            // actualizar saldo acumulado
            saldoAcumulado +=
                (item.tipo === "Ingreso" ? parseFloat(item.monto) : 0) -
                (item.tipo === "Egreso" ? parseFloat(item.monto) : 0);

            let row = `
                    <tr class="clickable-row" 
                        data-id="${item.id}" 
                        data-categoria="${item.categoria}" 
                        data-descripcion="${item.descripcion}" 
                        data-fecha="${item.fecha}" 
                        data-monto="${item.monto}" 
                        data-metodo="${item.metodo_pago}" 
                        data-sucursal="${item.caja.sucursal.nombre}"
                        style="border-left: 3px solid ${colorBorde}">
                        <td>${index + 1}</td>
                        <td>${item.caja.sucursal.nombre}</td>
                        <td>${item.categoria}</td>
                        <td>${item.descripcion ?? ""}</td>
                        <td>${item.fecha}</td>
                        <td style="font-weight:${item.tipo === "Ingreso" ? "bold" : "normal"}; color:${ingresoColor}">
                            ${item.tipo === "Ingreso" ? item.monto : "0"}
                        </td>
                        <td style="font-weight:${item.tipo === "Egreso" ? "bold" : "normal"}; color:${egresoColor}">
                            ${item.tipo === "Egreso" ? item.monto : "0"}
                        </td>
                        <td style="background:#40A2E3; color:white; font-weight:bold; text-align:center">
                            ${saldoAcumulado.toFixed(2)}
                        </td>
                    </tr>
                `;
            tbody.append(row);
        });

        // Evento click en filas
        $("#tabla-caja-Deposito tbody").on(
            "click",
            ".clickable-row",
            function () {
                $("#tabla-caja-Deposito tbody tr").removeClass("selected-row");
                $(this).addClass("selected-row");

                // Recuperar el objeto original desde response usando el id
                let movimiento = response.CajaDeposito.find(
                    (m) => m.id == $(this).data("id"),
                );

                // Validar que exista
                if (!movimiento) {
                    console.warn(
                        "Movimiento no encontrado para la fila seleccionada",
                    );
                    return;
                }

                let tipoProceso = movimiento.tipo || "No definido";
                let colorProceso =
                    tipoProceso === "Ingreso"
                        ? "green"
                        : tipoProceso === "Egreso"
                          ? "red"
                          : "#686D76";

                let detalle = `
                    <div class="card-header" style="background: rgb(24, 36, 51); color: white">
                        <div class="row" style="width: 100%;">
                            <div class="col-12 col-sm-8">
                                <h4 class="card-title" style="color:white">INFORMACION DETALLE</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">SUCURSAL:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data("sucursal")}</label></div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">FECHA REGISTRO:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data("fecha")}</label></div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">CATEGORIA:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data("categoria")}</label></div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">DESCRIPCION:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data("descripcion")}</label></div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">TIPO DE PROCESO:</label>
                            <div class="col">
                                <label class="col-8 col-form-label" 
                                    style="font-weight:bold; font-size:16px; color:${colorProceso}">
                                    ${tipoProceso}
                                </label>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-4 col-form-label" style="font-weight: bold;">MONTO:</label>
                            <div class="col"><label class="col-8 col-form-label" style="color:#686D76">${$(this).data("monto")}</label></div>
                        </div>
                    </div>
                `;

                // Si tiene tratamiento, mostrar datos adicionales
                if (movimiento && movimiento.tratamiento) {
                    detalle += `
                        <div class="card shadow mt-3">
                            <div class="card-header" id="headingTratamiento">
                                <a role="button" href="#collapseTratamiento${movimiento.id}" 
                                data-toggle="collapse" data-target="#collapseTratamiento${movimiento.id}" 
                                aria-expanded="false" aria-controls="collapseTratamiento${movimiento.id}">
                                <strong>Datos del Tratamiento</strong>
                                </a>
                            </div>
                            <div id="collapseTratamiento${movimiento.id}" class="collapse" aria-labelledby="headingTratamiento" data-parent="#detalleMovimientoCajaDeposito">
                                <div class="card-body">

                                    <!-- Información general -->
                                    <table class="table table-sm table-striped">
                                        <tbody>
                                            <tr><th>Descripción</th><td>${movimiento.tratamiento.descripcion}</td></tr>
                                            <tr><th>Estado</th><td>${movimiento.tratamiento.estado}</td></tr>
                                            <tr><th>Fechas</th><td>Inicio: ${movimiento.tratamiento.fecha_inicio}<br>Fin estimada: ${movimiento.tratamiento.fecha_fin_estimada}</td></tr>
                                            <tr><th>Costos</th><td>Total: ${movimiento.tratamiento.costo_total}<br>Diferencia: ${movimiento.tratamiento.diferencia_costo}<br>Saldo: ${movimiento.tratamiento.saldo_total}</td></tr>
                                        </tbody>
                                    </table>

                                    <!-- Paciente -->
                                    <h6 class="bg-dark text-white p-2 mt-3">Paciente</h6>
                                    <p>${movimiento.tratamiento.paciente.nombre} ${movimiento.tratamiento.paciente.apellido_paterno} ${movimiento.tratamiento.paciente.apellido_materno} - CI: ${movimiento.tratamiento.paciente.ci} - Tel: ${movimiento.tratamiento.paciente.telefono}</p>

                                    <!-- Doctor -->
                                    <h6 class="bg-dark text-white p-2 mt-3">Doctor</h6>
                                    <p>${movimiento.tratamiento.doctor.nombre} (${movimiento.tratamiento.doctor.especialidad}) - Tel: ${movimiento.tratamiento.doctor.telefono}</p>

                                    <!-- Sesiones -->
                                    <h6 class="bg-dark text-white p-2 mt-3">Sesiones</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead><tr><th>#</th><th>Fecha Atención</th><th>Análisis</th><th>Plan Acción</th></tr></thead>
                                        <tbody>
                                            ${movimiento.tratamiento.sesiones
                                                .map(
                                                    (s, i) => `
                                                <tr><td>${i + 1}</td><td>${s.fecha_atencion}</td><td>${s.analisis}</td><td>${s.plan_accion}</td></tr>
                                            `,
                                                )
                                                .join("")}
                                        </tbody>
                                    </table>

                                    <!-- Pagos -->
                                    <h6 class="bg-dark text-white p-2 mt-3">Pagos</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead><tr><th>#</th><th>Fecha</th><th>Monto</th><th>Método</th></tr></thead>
                                        <tbody>
                                            ${movimiento.tratamiento.pagos
                                                .map(
                                                    (p, i) => `
                                                <tr><td>${i + 1}</td><td>${p.fecha}</td><td>${p.monto}</td><td>${p.metodo_pago}</td></tr>
                                            `,
                                                )
                                                .join("")}
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    `;
                }

                detalle += `</div>`; // cerrar card-body

                $("#detalleMovimientoCajaDeposito").html(detalle);
            },
        );
    }

    function FechaSelectCajaDeposito() {
        var today = new Date();
        var currentDay = today.getDate();
        var currentMonth = today.getMonth();
        var currentYear = today.getFullYear();
        var monthsOfYear = [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre",
        ];

        $("#MesCajaDeposito").empty();
        $("#AnioCajaDeposito").empty();

        for (var month = 0; month < 12; month++) {
            $("#MesCajaDeposito").append(
                '<option value="' +
                    (month + 1) +
                    '">' +
                    monthsOfYear[month] +
                    "</option>",
            );
        }
        $("#MesCajaDeposito").val(currentMonth + 1);

        var startYear = currentYear - 6;
        var endYear = currentYear;
        for (var year = startYear; year <= endYear; year++) {
            $("#AnioCajaDeposito").append(
                '<option value="' + year + '">' + year + "</option>",
            );
        }
        $("#AnioCajaDeposito").val(currentYear);

        function updateDaySelectorNovedad() {
            var selectedMonth = parseInt($("#MesCajaDeposito").val());
            var selectedYear = parseInt($("#AnioVenta").val());

            var selectedYear = today.getFullYear();
            var daysInMonth = new Date(
                selectedYear,
                selectedMonth,
                0,
            ).getDate();
            $("#DiaCajaDeposito").empty();
            for (var day = 1; day <= daysInMonth; day++) {
                $("#DiaCajaDeposito").append(
                    '<option value="' + day + '">' + day + "</option>",
                );
            }
            if (currentDay > daysInMonth) {
                $("#DiaCajaDeposito").val(daysInMonth);
            } else {
                $("#DiaCajaDeposito").val(currentDay);
            }
        }

        updateDaySelectorNovedad();

        $("#DateCajaDeposito").on("change", function () {
            var selectedValue = $(this).val();
            switch (selectedValue) {
                case "DiarioCajaDeposito":
                    $("#DiaCajaDepositoContainer").show();
                    $("#AnioCajaDepositoContainer").show();
                    $("#FechaInicioContainerCajaDeposito").hide();
                    $("#FechaFinContainerCajaDeposito").hide();
                    break;
                case "MensualidadCajaDeposito":
                    $("#MesCajaDepositoContainer").show();
                    $("#AnioCajaDepositoContainer").show();
                    $("#DiaCajaDepositoContainer").hide();
                    $("#FechaInicioContainerCajaDeposito").hide();
                    $("#FechaFinContainerCajaDeposito").hide();
                    break;
                case "RangoCajaDeposito":
                    $("#DiaCajaDepositoContainer").hide();
                    $("#MesCajaDepositoContainer").hide();
                    $("#AnioCajaDepositoContainer").hide();
                    $("#FechaInicioContainerCajaDeposito").show();
                    $("#FechaFinContainerCajaDeposito").show();
                    break;
            }
            FiltrarDatosCajaDeposito();
        });

        $("#MesCajaDeposito").on("change", function () {
            updateDaySelectorNovedad();
            FiltrarDatosCajaDeposito();
        });

        $("#AnioCajaDeposito").on("change", function () {
            FiltrarDatosCajaDeposito();
        });

        $("#DiaCajaDeposito").on("change", function () {
            FiltrarDatosCajaDeposito();
        });

        $("#FechaInicioContainerCajaDeposito").on("change", function () {
            FiltrarDatosCajaDeposito();
        });

        $("#FechaFinContainerCajaDeposito").on("change", function () {
            FiltrarDatosCajaDeposito();
        });

        $("#DateCajaDeposito").trigger("change");
    }

    function FiltrarDatosCajaDeposito() {
        var today = new Date();
        var selectedYear = $("#AnioCajaDeposito").val();
        var tipoFiltro = $("#DateCajaDeposito").val();
        var dia = $("#DiaCajaDeposito").val();
        var mes = $("#MesCajaDeposito").val();
        var anio = selectedYear;
        var fechaInicio = $("#fechaInicioCajaDeposito").val();
        var fechaFin = $("#fechaFinCajaDeposito").val();

        $.ajax({
            url: "/filtrar-datos-caja-Deposito",
            method: "GET",
            data: {
                tipoFiltro: tipoFiltro,
                dia: dia,
                mes: mes,
                anio: anio,
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
            },
            success: function (response) {
                MostrarCajaDeposito(response);
            },
            error: function (error) {
                console.error("Error al filtrar datos:", error);
            },
        });
    }

    function InputRangoFechasControl() {
        var today = new Date();
        var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        var formatDate = function (date) {
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            return date.getFullYear() + "-" + month + "-" + day;
        };
        var fechaInicioCajaDeposito = document.getElementById(
            "fechaInicioCajaDeposito",
        );
        var fechaFinCajaDeposito = document.getElementById(
            "fechaFinCajaDeposito",
        );
        fechaInicioCajaDeposito.min = formatDate(firstDay);
        fechaInicioCajaDeposito.max = formatDate(lastDay);
        fechaFinCajaDeposito.min = formatDate(firstDay);
        fechaFinCajaDeposito.max = formatDate(lastDay);
        fechaInicioCajaDeposito.value = formatDate(today);
        fechaFinCajaDeposito.value = formatDate(today);
    }
});

function mostrarEstadoEsperaDeposito() {
    const contenedor = document.getElementById("detalleMovimientoCajaDeposito");
    if (!contenedor) return;

    contenedor.innerHTML = `
            <div class="d-flex flex-column justify-content-center align-items-center" style="height:auto;">
                <img src="/svg/grid.svg" alt="En espera" style="width:60%; height:auto;">
                <span class="mt-3 text-muted h5">En espera</span>
            </div>
        `;
}
