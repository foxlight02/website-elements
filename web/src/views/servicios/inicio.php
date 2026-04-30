<style>
    .modal {
        display: none;
        /* oculto por defecto */
        position: fixed;
        /* 🔥 se sobrepone a TODO */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        /* fondo oscuro */
    }

    .modal__content {
        background: white;
        padding: 20px;
        width: 400px;
        margin: 10% auto;
        border-radius: 10px;
    }

</style>

<section class="services">
    <div class="services__grid">

        <?php foreach ($servicios as $s): ?>
        <div class="services__card" data-id="<?= $s["id"] ?>">

            <img src="<?= URL_PATH ?>/assets/img/<?= $s["imagen"] ?>">

            <h3 class="card-nombre"><?= $s["nombre"] ?></h3>
            <p class="card-especialidad"><?= $s["especialidad"] ?></p>
            <p class="card-telefono"><?= $s["telefono"] ?></p>
            <p class="card-descripcion"><?= $s["descripcion"] ?></p>

            <button class="btn-edit" data-id="<?= $s["id"] ?>" data-nombre="<?= $s[
    "nombre"
] ?>" data-especialidad="<?= $s["especialidad"] ?>" data-telefono="<?= $s["telefono"] ?>" data-descripcion="<?= $s[
    "descripcion"
] ?>" data-imagen="<?= $s["imagen"] ?>">
                Editar
            </button>

        </div>
        <?php endforeach; ?>

    </div>
</section>

<!-- MODAL -->
<div id="modalEdit" class="modal">
    <div class="modal__content">

        <span id="closeModal">X</span>

        <form id="formEdit" enctype="multipart/form-data">

            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="imagen_actual" id="edit_imagen_actual">

            <input type="text" name="nombre" id="edit_nombre">
            <input type="text" name="especialidad" id="edit_especialidad">
            <input type="text" name="telefono" id="edit_telefono">
            <textarea name="descripcion" id="edit_descripcion"></textarea>

            <input type="file" name="imagen">

            <button type="submit">Guardar</button>

        </form>

    </div>
</div>

<script>
    const URL_PATH = "<?= URL_PATH ?>";

    const modal = document.getElementById("modalEdit");

    // abrir modal
    document.querySelectorAll(".btn-edit").forEach(btn => {
        btn.addEventListener("click", () => {

            edit_id.value = btn.dataset.id;
            edit_nombre.value = btn.dataset.nombre;
            edit_especialidad.value = btn.dataset.especialidad;
            edit_telefono.value = btn.dataset.telefono;
            edit_descripcion.value = btn.dataset.descripcion;
            edit_imagen_actual.value = btn.dataset.imagen;

            modal.style.display = "block";
        });
    });

    // submit
    formEdit.addEventListener("submit", async (e) => {
        e.preventDefault();

        const data = new FormData(formEdit);
        const id = data.get("id");

        const res = await fetch(`${URL_PATH}/servicios/update/${id}`, {
            method: "POST",
            body: data
        });

        const json = await res.json();

        if (json.status === "ok") {

            const card = document.querySelector(`.services__card[data-id='${id}']`);

            card.querySelector(".card-nombre").innerText = data.get("nombre");
            card.querySelector(".card-especialidad").innerText = data.get("especialidad");
            card.querySelector(".card-telefono").innerText = data.get("telefono");
            card.querySelector(".card-descripcion").innerText = data.get("descripcion");

            if (json.imagen) {
                card.querySelector("img").src =
                    `${URL_PATH}/assets/img/${json.imagen}?t=${Date.now()}`;
            }

            modal.style.display = "none";
        }
    });

</script>

</script>
