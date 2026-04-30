<button
    class="btn-edit"
    data-id="<?= $s['id'] ?>"
    data-nombre="<?= htmlspecialchars($s['nombre']) ?>"
    data-especialidad="<?= htmlspecialchars($s['especialidad']) ?>"
    data-telefono="<?= htmlspecialchars($s['telefono']) ?>"
    data-descripcion="<?= htmlspecialchars($s['descripcion']) ?>"
    data-imagen="<?= $s['imagen'] ?>"
>
    ✏️ EditarRR
</button>

<div id="modalEditar" style="display:none; position:fixed; background:white; border:1px solid #000; padding:20px; z-index:1000;">
    <form id="formUpdate" enctype="multipart/form-data">
        <input type="hidden" name="id" id="edit-id">
        <input type="hidden" name="imagen_actual" id="edit-imagen-actual">

        <label>Nombre:</label>
        <input type="text" name="nombre" id="edit-nombre">

        <label>Especialidad:</label>
        <input type="text" name="especialidad" id="edit-especialidad">

        <label>Teléfono:</label>
        <input type="text" name="telefono" id="edit-telefono">

        <label>Descripción:</label>
        <textarea name="descripcion" id="edit-descripcion"></textarea>

        <label>Imagen:</label>
        <div id="prev-img"></div>
        <input type="file" name="imagen">

        <button type="submit">Guardar Cambios 💡</button>
        <button type="button" onclick="cerrarModal()">Cancelar</button>
    </form>
</div>
