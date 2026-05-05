<style>
    /* Fondo cuando editas */
    .services.editando .services__card {
        opacity: 0.3;
        transform: scale(0.95);
        pointer-events: none;
        transition: 0.3s;
    }

    /* Card activa */
    .services__card.activa {
        opacity: 1 !important;
        border: 2px solid #007bff;
        transform: scale(1) !important;
        pointer-events: auto !important;
        z-index: 10;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

</style>

<section class="services">
    <div class="services__grid">

        <?php foreach ($servicios as $s): ?>

        <form method="POST" action="/servicios/actualizar" class="services__card">

            <!-- ID oculto -->
            <input type="hidden" name="id" value="<?= $s["id"] ?>">

            <img src="/assets/img/<?= $s["imagen"] ?>" class="img">

            <h3 class="card-nombre"><?= $s["nombre"] ?></h3>
            <p class="card-especialidad"><?= $s["especialidad"] ?></p>
            <p class="card-telefono"><?= $s["telefono"] ?></p>
            <p class="card-descripcion"><?= $s["descripcion"] ?></p>

            <button type="button" class="btn-edit">Editar</button>
            <button type="button" class="btn-delete">EliminarRRR</button>
        </form>

        <?php endforeach; ?>

    </div>
</section>

<script>
    document.querySelectorAll(".btn-edit").forEach(boton => {

        boton.addEventListener("click", function(e) {
            e.preventDefault();

            const card = this.closest(".services__card");
            const container = document.querySelector(".services");

            // ======================================================
            // 🔵 GUARDAR (cuando ya está editando)
            // ======================================================
            if (this.classList.contains("editando")) {

                const formData = new FormData(card);

                this.disabled = true;
                this.textContent = "Guardando...";

                fetch("/servicios/actualizar", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => {
                        if (!res.ok) throw new Error("Error HTTP");
                        return res.json();
                    })
                    .then(data => {

                        console.log("Respuesta:", data);

                        if (data.success) {

                            // Volver a texto
                            card.querySelector('input[name="nombre"]').outerHTML =
                                `<h3 class="card-nombre">${formData.get("nombre")}</h3>`;

                            card.querySelector('input[name="especialidad"]').outerHTML =
                                `<p class="card-especialidad">${formData.get("especialidad")}</p>`;

                            card.querySelector('input[name="telefono"]').outerHTML =
                                `<p class="card-telefono">${formData.get("telefono")}</p>`;

                            card.querySelector('textarea[name="descripcion"]').outerHTML =
                                `<p class="card-descripcion">${formData.get("descripcion")}</p>`;

                            // Reset estado
                            this.textContent = "Editar";
                            this.classList.remove("editando");
                            this.disabled = false;

                            card.classList.remove("activa");
                            container.classList.remove("editando");

                            const cancel = card.querySelector(".btn-cancel");
                            if (cancel) cancel.remove();

                        } else {
                            alert(data.error || "Error al actualizar");
                            this.disabled = false;
                            this.textContent = "Guardar";
                        }

                    })
                    .catch(err => {
                        console.error("Error:", err);
                        alert("Error de conexión o servidor");
                        this.disabled = false;
                        this.textContent = "Guardar";
                    });

                return;
            }

            // ======================================================
            // 🟢 EDITAR (primer click)
            // ======================================================

            container.classList.add("editando");

            document.querySelectorAll(".services__card").forEach(c => {
                c.classList.remove("activa");
            });

            card.classList.add("activa");

            this.classList.add("editando");

            // Guardar valores originales
            const nombre = card.querySelector(".card-nombre").textContent;
            const especialidad = card.querySelector(".card-especialidad").textContent;
            const telefono = card.querySelector(".card-telefono").textContent;
            const descripcion = card.querySelector(".card-descripcion").textContent;
            const img = card.querySelector(".img");
            const file = card.querySelector(".file");

            card.dataset.nombre = nombre;
            card.dataset.especialidad = especialidad;
            card.dataset.telefono = telefono;
            card.dataset.descripcion = descripcion;



            // Convertir a inputs
            card.querySelector(".card-nombre").outerHTML =
                `<input type="text" name="nombre" value="${nombre}">`;

            card.querySelector(".card-especialidad").outerHTML =
                `<input type="text" name="especialidad" value="${especialidad}">`;

            card.querySelector(".card-telefono").outerHTML =
                `<input type="text" name="telefono" value="${telefono}">`;

            card.querySelector(".card-descripcion").outerHTML =
                `<textarea name="descripcion">${descripcion}</textarea>`;

            this.textContent = "Guardar";

            // ======================================================
            // 🔴 BOTÓN CANCELAR
            // ======================================================
            if (!card.querySelector(".btn-cancel")) {

                const btnCancel = document.createElement("button");
                btnCancel.textContent = "Cancelar";
                btnCancel.type = "button";
                btnCancel.classList.add("btn-cancel");

                card.appendChild(btnCancel);

                btnCancel.addEventListener("click", function() {

                    card.querySelector('input[name="nombre"]').outerHTML =
                        `<h3 class="card-nombre">${card.dataset.nombre}</h3>`;

                    card.querySelector('input[name="especialidad"]').outerHTML =
                        `<p class="card-especialidad">${card.dataset.especialidad}</p>`;

                    card.querySelector('input[name="telefono"]').outerHTML =
                        `<p class="card-telefono">${card.dataset.telefono}</p>`;

                    card.querySelector('textarea[name="descripcion"]').outerHTML =
                        `<p class="card-descripcion">${card.dataset.descripcion}</p>`;

                    // Reset botón editar
                    const btnEdit = card.querySelector(".btn-edit");
                    btnEdit.textContent = "Editar";
                    btnEdit.classList.remove("editando");
                    btnEdit.disabled = false;

                    // Reset UI
                    card.classList.remove("activa");
                    container.classList.remove("editando");

                    this.remove();
                });
            }

        });

    });

    document.querySelectorAll(".btn-delete").forEach(boton => {

        boton.addEventListener("click", function(e) {
            e.preventDefault();

            const card = this.closest(".services__card");
            const container = document.querySelector(".services");

            // 🚫 Seguridad: No borrar si hay otra edición activa
            if (container.classList.contains("editando")) {
                alert("Termina la edición antes de eliminar.");
                return;
            }

            const idServicio = card.querySelector('input[name="id"]').value;
            const nombreServicio = card.querySelector('.card-nombre').textContent;

            // Confirmación personalizada
            const confirmar = confirm(`¿Estás seguro de eliminar: "${nombreServicio}"?`);
            if (!confirmar) return;

            // 🔄 Feedback visual
            this.disabled = true;
            const textoOriginal = this.textContent;
            this.textContent = "Eliminando...";

            // Petición al servidor
            fetch("/servicios/delete", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: idServicio
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error("Error en la respuesta del servidor");
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        // Animación de salida
                        card.style.transition = "all 0.4s ease";
                        card.style.opacity = "0";
                        card.style.transform = "scale(0.8)";

                        setTimeout(() => {
                            card.remove();
                        }, 400);
                    } else {
                        throw new Error(data.message || "Error desconocido");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("❌ " + err.message);
                    // Revertir cambios en el botón si falla
                    this.disabled = false;
                    this.textContent = textoOriginal;
                });
        });
    });

</script>
