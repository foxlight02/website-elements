<style>
.services.editando .services__card {
    opacity: 0.3;
    transform: scale(0.95);
    pointer-events: none;
    transition: 0.3s;
}

.services__card.activa {
    opacity: 1 !important;
    border: 2px solid #007bff;
    transform: scale(1) !important;
    pointer-events: auto !important;
    z-index: 10;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}
</style>

<section class="services">
<div class="services__grid">

<?php foreach ($servicios as $s): ?>
<form class="services__card">

    <input type="hidden" name="id" value="<?= $s["id"] ?>">

    <img src="/assets/img/<?= $s["imagen"] ?>" class="img">

    <input
        type="file"
        name="imagen"
        class="file"
        hidden
        accept="image/png, image/jpeg, image/webp"
    >

    <h3 class="card-nombre"><?= $s["nombre"] ?></h3>

    <p class="card-especialidad"><?= $s["especialidad"] ?></p>

    <p class="card-telefono"><?= $s["telefono"] ?></p>

    <p class="card-descripcion"><?= $s["descripcion"] ?></p>

    <button type="button" class="btn-edit">
        Editar
    </button>

    <button type="button" class="btn-delete">
        Eliminar
    </button>

</form>
<?php endforeach; ?>

</div>
</section>

<script>

const container = document.querySelector(".services");

// ==========================
// 🟡 IMAGEN
// ==========================
document.querySelectorAll(".services__card").forEach(card => {

    const img = card.querySelector(".img");
    const file = card.querySelector(".file");

    if (!img || !file) return;

    // click imagen
    img.addEventListener("click", () => {

        if (card.classList.contains("activa")) {

            file.value = "";

            file.click();
        }

    });

    // seleccionar imagen
    file.addEventListener("change", (e) => {

        const f = e.target.files[0];

        if (!f) return;

        // ==========================
        // VALIDAR TIPO
        // ==========================
        const tipos = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (!tipos.includes(f.type)) {

            alert("Solo JPG PNG WEBP");

            file.value = "";

            return;
        }

        // ==========================
        // VALIDAR PESO
        // ==========================
        const max = 2 * 1024 * 1024;

        if (f.size > max) {

            alert("Máximo 2MB");

            file.value = "";

            return;
        }

        // ==========================
        // PREVIEW
        // ==========================
        img.src = URL.createObjectURL(f);

    });

});

// ==========================
// 🟢 EDITAR / GUARDAR
// ==========================
document.querySelectorAll(".btn-edit").forEach(boton => {

    boton.addEventListener("click", function(e) {

        e.preventDefault();

        const card = this.closest(".services__card");

        // ==========================
        // 🔵 GUARDAR
        // ==========================
        if (this.classList.contains("editando")) {

            const formData = new FormData(card);

            this.disabled = true;

            this.textContent = "Guardando...";

            fetch("/servicios/actualizar", {
                method: "POST",
                body: formData
            })
            .then(res => {

                if (!res.ok) {
                    throw new Error("Error servidor");
                }

                return res.json();

            })
            .then(data => {

                if (data.success) {

                    // actualizar imagen
                    if (data.imagen) {
                        card.querySelector(".img").src = data.imagen;
                    }

                    // volver a texto
                    card.querySelector('input[name="nombre"]').outerHTML =
                        `<h3 class="card-nombre">${formData.get("nombre")}</h3>`;

                    card.querySelector('input[name="especialidad"]').outerHTML =
                        `<p class="card-especialidad">${formData.get("especialidad")}</p>`;

                    card.querySelector('input[name="telefono"]').outerHTML =
                        `<p class="card-telefono">${formData.get("telefono")}</p>`;

                    card.querySelector('textarea[name="descripcion"]').outerHTML =
                        `<p class="card-descripcion">${formData.get("descripcion")}</p>`;

                    this.textContent = "Editar";

                    this.classList.remove("editando");

                    this.disabled = false;

                    card.classList.remove("activa");

                    container.classList.remove("editando");

                    const cancel = card.querySelector(".btn-cancel");

                    if (cancel) {
                        cancel.remove();
                    }

                } else {

                    throw new Error(data.error || "Error al actualizar");

                }

            })
            .catch(err => {

                console.error(err);

                alert("❌ " + err.message);

                this.disabled = false;

                this.textContent = "Guardar";

            });

            return;
        }

        // ==========================
        // 🟢 EDITAR
        // ==========================
        container.classList.add("editando");

        document.querySelectorAll(".services__card")
        .forEach(c => c.classList.remove("activa"));

        card.classList.add("activa");

        this.classList.add("editando");

        const nombre =
            card.querySelector(".card-nombre").textContent;

        const especialidad =
            card.querySelector(".card-especialidad").textContent;

        const telefono =
            card.querySelector(".card-telefono").textContent;

        const descripcion =
            card.querySelector(".card-descripcion").textContent;

        const img =
            card.querySelector(".img");

        // backup
        card.dataset.nombre = nombre;
        card.dataset.especialidad = especialidad;
        card.dataset.telefono = telefono;
        card.dataset.descripcion = descripcion;
        card.dataset.imagen = img.src;

        // inputs
        card.querySelector(".card-nombre").outerHTML =
            `<input type="text" name="nombre" value="${nombre}">`;

        card.querySelector(".card-especialidad").outerHTML =
            `<input type="text" name="especialidad" value="${especialidad}">`;

        card.querySelector(".card-telefono").outerHTML =
            `<input type="text" name="telefono" value="${telefono}">`;

        card.querySelector(".card-descripcion").outerHTML =
            `<textarea name="descripcion">${descripcion}</textarea>`;

        this.textContent = "Guardar";

        // ==========================
        // 🔴 CANCELAR
        // ==========================
        if (!card.querySelector(".btn-cancel")) {

            const btnCancel =
                document.createElement("button");

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

                // restaurar imagen
                card.querySelector(".img").src =
                    card.dataset.imagen;

                const btnEdit =
                    card.querySelector(".btn-edit");

                btnEdit.textContent = "Editar";

                btnEdit.classList.remove("editando");

                btnEdit.disabled = false;

                card.classList.remove("activa");

                container.classList.remove("editando");

                this.remove();

            });

        }

    });

});

// ==========================
// 🔴 DELETE
// ==========================
document.querySelectorAll(".btn-delete").forEach(boton => {

    boton.addEventListener("click", function(e) {

        e.preventDefault();

        const card =
            this.closest(".services__card");

        if (container.classList.contains("editando")) {

            alert("Termina la edición primero");

            return;
        }

        const id =
            card.querySelector('input[name="id"]').value;

        if (!confirm("¿Eliminar?")) return;

        this.disabled = true;

        this.textContent = "Eliminando...";

        fetch("/servicios/delete", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        })
        .then(res => {

            if (!res.ok) {
                throw new Error("Error servidor");
            }

            return res.json();

        })
        .then(data => {

            if (data.success) {

                card.style.opacity = "0";

                card.style.transform = "scale(0.8)";

                setTimeout(() => {

                    card.remove();

                }, 400);

            } else {

                throw new Error(
                    data.message || "Error al eliminar"
                );

            }

        })
        .catch(err => {

            console.error(err);

            alert("❌ " + err.message);

            this.disabled = false;

            this.textContent = "Eliminar";

        });

    });

});

</script>
