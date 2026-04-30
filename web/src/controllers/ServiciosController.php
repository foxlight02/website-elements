<?php

// ✔ SIEMPRE arriba, fuera de la clase
require_once __DIR__ . "/../models/Servicio.php";

class ServiciosController
{
    // 🔹 LISTADO (cards)
    public function inicio()
    {
        $title = "Servicios";

        $servicios = Servicio::getAll();

        include __DIR__ . "/../views/inc/header.php";
        include __DIR__ . "/../views/servicios/inicio.php";
        include __DIR__ . "/../views/inc/footer.php";
    }

    // 🔹 FORM CREAR
    public function new()
    {
        $title = "Nuevo Servicio";

        include __DIR__ . "/../views/inc/header.php";
        include __DIR__ . "/../views/servicios/new.php";
        include __DIR__ . "/../views/inc/footer.php";
    }

    // 🔹 GUARDAR
    public function guardar()
    {
        Servicio::crear($_POST);

        // ✔ usar rutas amigables
        header("Location: " . URL_PATH . "/servicios");
        exit();
    } // 🔹 EDIT (formulario)
    public function edit()
    {
        $id = $_GET["id"] ?? null;

        $servicio = Servicio::getById($id);

        include __DIR__ . "/../views/inc/header.php";
        require __DIR__ . "/../views/servicios/edit.php";
        include __DIR__ . "/../views/inc/footer.php";
    }

    // 🔹 UPDATE
    public function update()
    {
        require_once __DIR__ . "/../models/Servicio.php";

        header("Content-Type: application/json");

        $id = $_GET["id"];

        $nombreImagen = $_POST["imagen_actual"];

        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
            $nombreImagen = time() . "_" . $_FILES["imagen"]["name"];

            move_uploaded_file($_FILES["imagen"]["tmp_name"], __DIR__ . "/../../public/assets/img/" . $nombreImagen);
        }

        $data = [
            "nombre" => $_POST["nombre"],
            "especialidad" => $_POST["especialidad"],
            "telefono" => $_POST["telefono"],
            "descripcion" => $_POST["descripcion"],
            "imagen" => $nombreImagen,
        ];

        $ok = Servicio::update($id, $data);

        echo json_encode([
            "status" => $ok ? "ok" : "error",
            "imagen" => $nombreImagen,
        ]);
    }

    // 🔹 DELETE
    public function delete()
    {
        $id = $_GET["id"];

        Servicio::delete($id);

        header("Location: " . URL_PATH . "/servicios");
        exit();
    }
}
