function mostrarEstadoEspera() {
    const contenedor = document.getElementById("ContenidoDiv");
    if (!contenedor) return;

    contenedor.innerHTML = `
            <div class="d-flex flex-column justify-content-center align-items-center" style="height:auto;">
                <img src="/svg/grid.svg" alt="En espera" style="width:60%; height:auto;">
                <span class="mt-3 text-muted h5">En espera</span>
            </div>
        `;
}

function crearContenedorToasts() {
    let contenedor = document.getElementById("toastContainer");

    if (!contenedor) {
        contenedor = document.createElement("div");
        contenedor.id = "toastContainer";
        contenedor.style.position = "fixed";
        contenedor.style.bottom = "20px"; // 👈 abajo
        contenedor.style.right = "20px";
        contenedor.style.zIndex = "9999";
        document.body.appendChild(contenedor);
    }

    return contenedor;
}

function generarToast(tipo, titulo, mensaje) {
    const contenedor = crearContenedorToasts();

    let colorClase = "";
    switch (tipo) {
        case "success":
            colorClase = "bg-success text-white";
            break;
        case "error":
            colorClase = "bg-danger text-white";
            break;
        case "warning":
            colorClase = "bg-warning text-dark";
            break;
    }

    const toast = document.createElement("div");
    toast.className = `toast fade show mb-2 ${colorClase}`;
    toast.role = "alert";
    toast.innerHTML = `
        <div class="toast-header ${colorClase}">
            <strong class="mr-auto">${titulo}</strong>
            <small>Ahora</small>
            <button type="button" class="ml-2 mb-1 close" onclick="this.parentElement.parentElement.remove()">
                <span>&times;</span>
            </button>
        </div>
        <div class="toast-body">
            ${mensaje}
        </div>
    `;

    contenedor.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 4000);
}

// Funciones públicas
function toastSuccess(mensaje) {
    generarToast("success", "Éxito", mensaje);
}

function toastError(mensaje) {
    generarToast("error", "Error", mensaje);
}

function toastWarning(mensaje) {
    generarToast("warning", "Advertencia", mensaje);
}

function generarToastConfirm(titulo, mensaje, callbackSi, callbackNo) {
    // Crear overlay difuminado
    let overlay = document.createElement("div");
    overlay.id = "toastOverlay";
    overlay.style.position = "fixed";
    overlay.style.top = "0";
    overlay.style.left = "0";
    overlay.style.width = "100%";
    overlay.style.height = "100%";
    overlay.style.backgroundColor = "rgba(0,0,0,0.5)"; // fondo difuminado
    overlay.style.display = "flex";
    overlay.style.alignItems = "center";
    overlay.style.justifyContent = "center";
    overlay.style.zIndex = "9998";
    document.body.appendChild(overlay);

    // Crear el toast centrado
    const toast = document.createElement("div");
    toast.className = `toast fade show bg-light border`;
    toast.role = "alert";
    toast.style.minWidth = "300px";
    toast.style.maxWidth = "400px";
    toast.style.zIndex = "9999";

    toast.innerHTML = `
        <div class="toast-header bg-secondary text-white">
            <strong class="mr-auto">${titulo}</strong>
            <button type="button" class="ml-2 mb-1 close" onclick="cerrarToastConfirm()">
                <span>&times;</span>
            </button>
        </div>
        <div class="toast-body">
            ${mensaje}
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-success me-2" id="btnToastSi">Sí</button>
                <button class="btn btn-sm btn-danger" id="btnToastNo">No</button>
            </div>
        </div>
    `;

    overlay.appendChild(toast);

    // Eventos de los botones
    toast.querySelector("#btnToastSi").addEventListener("click", () => {
        if (callbackSi) callbackSi();
        cerrarToastConfirm();
    });

    toast.querySelector("#btnToastNo").addEventListener("click", () => {
        if (callbackNo) callbackNo();
        cerrarToastConfirm();
    });
}

// Función para cerrar el toast y overlay
function cerrarToastConfirm() {
    const overlay = document.getElementById("toastOverlay");
    if (overlay) overlay.remove();
}

// Función pública
function toastConfirm(mensaje, callbackSi, callbackNo) {
    generarToastConfirm("Confirmación", mensaje, callbackSi, callbackNo);
}
