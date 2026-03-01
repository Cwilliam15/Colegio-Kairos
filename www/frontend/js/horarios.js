/* ============================================================
   CONFIGURAR RUTA API
===============================================================*/

const API = "/backend/php_be/horarios/";

/* ============================================================
   UTILIDADES
===============================================================*/

function horaToMin(hora) {
    const [h, m] = hora.split(":").map(Number);
    return h * 60 + m;
}

function hayChoque(nuevaEntrada, nuevaSalida, horariosExistentes) {
    const inicioNuevo = horaToMin(nuevaEntrada);
    const finNuevo = horaToMin(nuevaSalida);

    for (let h of horariosExistentes) {

        if (!h.Hora_Entrada || !h.Hora_Salida) continue;

        const inicioExistente = horaToMin(h.Hora_Entrada);
        const finExistente = horaToMin(h.Hora_Salida);

        if (inicioNuevo < finExistente && finNuevo > inicioExistente) {
            return true;
        }
    }
    return false;
}

/* ============================================================
   SELECCIONAR DIAS CON COLOR DINÁMICO
===============================================================*/

document.addEventListener("change", function (e) {

    if (e.target.matches(".dia input")) {

        const label = e.target.closest(".dia");
        const card = e.target.closest(".card-jornada");
        const colorPicker = card.querySelector(".color-grupo");

        if (e.target.checked) {
            label.classList.add("activo");
            label.style.backgroundColor = colorPicker.value;
            label.style.color = "#fff";
        } else {
            label.classList.remove("activo");
            label.style.backgroundColor = "";
            label.style.color = "";
        }
    }
});

/* ============================================================
   ACTUALIZAR COLOR SI CAMBIA EL PICKER
===============================================================*/

document.addEventListener("input", function (e) {

    if (e.target.matches(".color-grupo")) {

        const card = e.target.closest(".card-jornada");
        const nuevoColor = e.target.value;

        card.querySelectorAll(".dia input:checked").forEach(input => {
            const label = input.closest(".dia");
            label.style.backgroundColor = nuevoColor;
            label.style.color = "#fff";
        });
    }
});

/* ============================================================
   GUARDAR
===============================================================*/

document.addEventListener("click", async function (e) {

    if (e.target.classList.contains("btn-agregar")) {

        const card = e.target.closest(".card-jornada");

        const jornada = card.dataset.jornada;
        const entrada = card.querySelector(".entrada").value;
        const salida = card.querySelector(".salida").value;
        const obs = card.querySelector(".obs").value;
        const color = card.querySelector(".color-grupo")?.value || "#3498db";

        const diasSeleccionados = [];

        card.querySelectorAll(".dia input:checked").forEach(d => {
            diasSeleccionados.push(d.value);
        });

        if (!entrada || !salida) {
            alert("Debe ingresar hora de entrada y salida");
            return;
        }

        if (horaToMin(entrada) >= horaToMin(salida)) {
            alert("La hora de entrada debe ser menor que la de salida");
            return;
        }

        if (diasSeleccionados.length === 0) {
            alert("Seleccione al menos un día");
            return;
        }

        try {

            for (let fecha of diasSeleccionados) {

                await fetch(API + "guardar.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        Id_Jornada: jornada,
                        Fecha: fecha,
                        Hora_Entrada: entrada,
                        Hora_Salida: salida,
                        Observaciones: obs,
                        Color: color
                    })
                });
            }

            alert("Horario(s) guardado(s)");
            limpiarFormulario(card);
            listarHorarios(jornada);

        } catch (error) {
            console.error(error);
            alert("Error al guardar");
        }
    }
});

/* ============================================================
   LISTAR HORARIOS
===============================================================*/

async function listarHorarios(jornada) {

    try {

        const res = await fetch(API + "listar.php?jornada=" + jornada);
        const data = await res.json();

        const card = document.querySelector(`.card-jornada[data-jornada="${jornada}"]`);
        const lista = card.querySelector(".lista-grupos");

        lista.innerHTML = "";

        data.forEach(h => {

            lista.innerHTML += `
            <div class="grupo-item" data-id="${h.Id_Horario}" 
                 style="border-left: 8px solid ${h.Color || '#3498db'}">
                
                <strong>${h.Fecha}</strong><br>
                ${h.Hora_Entrada} - ${h.Hora_Salida}<br>
                <small>${h.Observaciones ?? ""}</small><br><br>
            </div>
            `;
        });

    } catch (error) {
        console.error("Error al listar:", error);
    }
}

/* ============================================================
   LIMPIAR FORMULARIO
===============================================================*/

function limpiarFormulario(card) {

    card.querySelectorAll(".dia input").forEach(d => {
        d.checked = false;
        const label = d.closest(".dia");
        label.classList.remove("activo");
        label.style.backgroundColor = "";
        label.style.color = "";
    });

    card.querySelector(".entrada").value = "";
    card.querySelector(".salida").value = "";
    card.querySelector(".obs").value = "";
}

/* ============================================================
   CARGAR AL INICIAR
===============================================================*/

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".card-jornada").forEach(card => {
        listarHorarios(card.dataset.jornada);
    });
});
