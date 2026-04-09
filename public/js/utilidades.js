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
        contenedor.style.bottom = "20px";   // 👈 abajo
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