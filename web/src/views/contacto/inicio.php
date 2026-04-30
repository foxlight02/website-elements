
<style>
/* Fondo general */
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 40px;
}

/* Contenedor */
h2 {
    text-align: center;
    color: #333;
}

/* Formulario tipo card */
#formContacto {
    max-width: 400px;
    margin: 30px auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Inputs */
#formContacto input,
#formContacto textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    font-size: 14px;
    transition: 0.2s;
}

/* Focus bonito */
#formContacto input:focus,
#formContacto textarea:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 5px rgba(74,144,226,0.4);
}

/* Botón */
#formContacto button {
    width: 100%;
    padding: 12px;
    background: #4a90e2;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}

/* Hover botón */
#formContacto button:hover {
    background: #357bd8;
}

/* Respuesta */
#respuesta {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
}

</style>


<h2>Contacto</h2>

<form id="formContacto">
    <input type="text" name="nombre" placeholder="Nombre"><br><br>
    <input type="email" name="correo" placeholder="Correo"><br><br>
    <textarea name="mensaje" placeholder="Mensaje"></textarea><br><br>

    <button type="submit">Enviar</button>
</form>

<div id="respuesta"></div>

<script>
document.getElementById("formContacto").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("/contacto/enviar", {
        method: "POST",
        body: formData // 👈 FORM DATA (NO JSON)
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);

        if (data.success) {
            document.getElementById("respuesta").innerHTML =
                "✅ Guardado<br>Nombre: " + data.data.nombre;
        } else {
            document.getElementById("respuesta").innerHTML =
                "❌ Error";
        }
    })
    .catch(err => {
        console.error(err);
        document.getElementById("respuesta").innerHTML = "Error en la petición";
    });
});
</script>
