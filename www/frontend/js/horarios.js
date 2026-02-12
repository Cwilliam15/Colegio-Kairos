const API = "../../php_be/horarios/";

let jornadaActual = "1";
let diasSeleccionados = [];

/* ===============================
   SELECCIONAR DIAS
=================================*/

document.addEventListener("click", function (e) {

    if (e.target.classList.contains("dia")) {

        const fecha = e.target.dataset.fecha;

        if (diasSeleccionados.includes(fecha)) {
            diasSeleccionados = diasSeleccionados.filter(d => d !== fecha);
            e.target.classList.remove("activo");
        } else {
            diasSeleccionados.push(fecha);
            e.target.classList.add("activo");
        }
    }
});

/* ===============================
   GUARDAR HORARIO
=================================*/

async function guardarHorario() {

    const entrada = document.getElementById("horaEntrada").value;
    const salida = document.getElementById("horaSalida").value;
    const obs = document.getElementById("observaciones").value;

    if (diasSeleccionados.length === 0) {
        alert("Seleccione al menos un día");
        return;
    }

    const res = await fetch(API + "guardar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            jornada: jornadaActual,
            dias: diasSeleccionados,
            entrada,
            salida,
            obs
        })
    });

    const data = await res.json();

    alert(data.message);

    diasSeleccionados = [];
    document.querySelectorAll(".dia").forEach(d => d.classList.remove("activo"));

    listarHorarios();
}

/* ===============================
   LISTAR HORARIOS
=================================*/

async function listarHorarios() {

    const res = await fetch(API + "listar.php?jornada=" + jornadaActual);
    const data = await res.json();

    console.log("Horarios:", data);
}

listarHorarios();


/* ============================================================
   ====================  VALIDACION QR  =======================
===============================================================*/

/*
Este método se ejecuta cuando el lector QR
envía el código (generalmente simula Enter)
*/

document.getElementById("qrInput").addEventListener("keypress", async function (e) {

    if (e.key === "Enter") {

        const carnet = this.value.trim();
        this.value = "";

        validarHorarioQR(carnet);
    }
});


async function validarHorarioQR(carnet) {

    const res = await fetch("../../php_be/asistencia/validar_qr.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ carnet })
    });

    const data = await res.json();

    mostrarResultado(data);
}


function mostrarResultado(data) {

    const box = document.getElementById("resultadoQR");

    box.className = "";

    if (data.estado === "A_TIEMPO") {
        box.classList.add("verde");
        box.innerHTML = "🟢 A TIEMPO";
    }

    else if (data.estado === "TARDE") {
        box.classList.add("amarillo");
        box.innerHTML = "🟡 TARDE";
    }

    else if (data.estado === "FUERA") {
        box.classList.add("rojo");
        box.innerHTML = "🔴 FUERA DE HORARIO";
    }

    else {
        box.classList.add("gris");
        box.innerHTML = "⚠ SIN HORARIO";
    }
}
