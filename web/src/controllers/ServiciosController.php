<?php

// ✔ Modelo
require_once __DIR__ . "/../models/Servicio.php";

class ServiciosController
{
    // ==========================
    // 🔹 LISTADO
    // ==========================
    public function inicio()
    {
        $title = "Servicios";

        $servicios = Servicio::getAll();

        include __DIR__ . "/../views/inc/header.php";
        include __DIR__ . "/../views/servicios/inicio.php";
        include __DIR__ . "/../views/inc/footer.php";
    }

    // ==========================
    // 🔹 NUEVO
    // ==========================
    public function new()
    {
        $title = "Nuevo Servicio";

        include __DIR__ . "/../views/inc/header.php";
        include __DIR__ . "/../views/servicios/new.php";
        include __DIR__ . "/../views/inc/footer.php";
    }

    // ==========================
    // 🔹 GUARDAR
    // ==========================
    public function guardar()
    {
        header("Content-Type: application/json");

        try {
            $json = file_get_contents("php://input");
            $datos = json_decode($json, true);

            if (!$datos) {
                $datos = $_POST;
            }

            $nombre = trim($datos["nombre"] ?? "");
            $especialidad = trim($datos["especialidad"] ?? "");
            $telefono = trim($datos["telefono"] ?? "");
            $descripcion = trim($datos["descripcion"] ?? "");

            // VALIDACIONES
            if ($nombre === "" || $especialidad === "") {
                throw new Exception("Nombre y especialidad obligatorios");
            }

            // INSERT
            $db = new Database();
            $conn = $db->getConnection();

            $sql = "INSERT INTO servicios
            (
                nombre,
                especialidad,
                telefono,
                descripcion
            )
            VALUES
            (
                :nombre,
                :especialidad,
                :telefono,
                :descripcion
            )";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ":nombre" => $nombre,
                ":especialidad" => $especialidad,
                ":telefono" => $telefono,
                ":descripcion" => $descripcion,
            ]);

            $nuevoId = $conn->lastInsertId();

            echo json_encode([
                "success" => true,
                "message" => "Servicio creado",
                "data" => [
                    "id" => $nuevoId,
                    "nombre" => $nombre,
                    "especialidad" => $especialidad,
                    "telefono" => $telefono,
                    "descripcion" => $descripcion,
                ],
            ]);
        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "success" => false,
                "error" => $e->getMessage(),
            ]);
        }

        exit();
    }

    // ==========================
    // 🔹 EDIT
    // ==========================
    public function edit()
    {
        $id = $_GET["id"] ?? null;

        $servicio = Servicio::getById($id);

        include __DIR__ . "/../views/inc/header.php";
        include __DIR__ . "/../views/servicios/edit.php";
        include __DIR__ . "/../views/inc/footer.php";
    }

    // ==========================
    // 🔹 DELETE
    // ==========================
    public function delete()
    {
        header("Content-Type: application/json");

        try {
            $json = file_get_contents("php://input");

            $datos = json_decode($json, true);

            if (!$datos) {
                $datos = $_POST;
            }

            $id = (int) ($datos["id"] ?? 0);

            if ($id <= 0) {
                throw new Exception("ID inválido");
            }

            $servicio = Servicio::find($id);

            if (!$servicio) {
                throw new Exception("Servicio no encontrado");
            }

            // 🔥 BORRAR IMAGEN
            if (!empty($servicio["imagen"])) {
                $ruta = __DIR__ . "/../../public/assets/img/" . $servicio["imagen"];

                if (file_exists($ruta)) {
                    unlink($ruta);
                }
            }

            // 🔥 DELETE BD
            Servicio::delete($id);

            echo json_encode([
                "success" => true,
                "message" => "Servicio eliminado",
            ]);
        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "success" => false,
                "message" => $e->getMessage(),
            ]);
        }

        exit();
    }

    // ==========================
    // 🔹 UPDATE
    // ==========================
    public function update()
    {
        header("Content-Type: application/json");

        try {
            // ==========================
            // 1. DATOS
            // ==========================
            $id = (int) ($_POST["id"] ?? 0);

            $nombre = trim($_POST["nombre"] ?? "");

            $especialidad = trim($_POST["especialidad"] ?? "");

            $telefono = trim($_POST["telefono"] ?? "");

            $descripcion = trim($_POST["descripcion"] ?? "");

            // ==========================
            // VALIDACIONES
            // ==========================
            if ($id <= 0) {
                throw new Exception("ID inválido");
            }

            if ($nombre === "" || $especialidad === "") {
                throw new Exception("Nombre y especialidad obligatorios");
            }

            // ==========================
            // 2. BUSCAR SERVICIO
            // ==========================
            $servicio = Servicio::find($id);

            if (!$servicio) {
                throw new Exception("Servicio no encontrado");
            }

            $imagenVieja = $servicio["imagen"] ?? null;

            $nombreImagen = null;

            // ==========================
            // 3. NUEVA IMAGEN
            // ==========================
            if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
                $archivo = $_FILES["imagen"];

                // ==========================
                // VALIDAR TIPO
                // ==========================
                $tiposPermitidos = ["image/jpeg", "image/png", "image/webp"];

                if (!in_array($archivo["type"], $tiposPermitidos)) {
                    throw new Exception("Solo JPG PNG WEBP");
                }

                // ==========================
                // VALIDAR PESO
                // ==========================
                $max = 2 * 1024 * 1024;

                if ($archivo["size"] > $max) {
                    throw new Exception("Máximo 2MB");
                }

                // ==========================
                // GENERAR NOMBRE
                // ==========================
                $extension = pathinfo($archivo["name"], PATHINFO_EXTENSION);

                $nombreImagen = time() . "." . $extension;

                // ==========================
                // RUTA
                // ==========================
                $ruta = __DIR__ . "/../../public/assets/img/" . $nombreImagen;

                // ==========================
                // GUARDAR IMAGEN
                // ==========================
                move_uploaded_file($archivo["tmp_name"], $ruta);

                // ==========================
                // BORRAR IMAGEN VIEJA
                // ==========================
                if ($imagenVieja) {
                    $rutaVieja = __DIR__ . "/../../public/assets/img/" . $imagenVieja;

                    if (file_exists($rutaVieja)) {
                        unlink($rutaVieja);
                    }
                }
            }

            // ==========================
            // 4. UPDATE BD
            // ==========================
            Servicio::update($id, [
                "nombre" => $nombre,

                "especialidad" => $especialidad,

                "telefono" => $telefono,

                "descripcion" => $descripcion,

                "imagen" => $nombreImagen,
            ]);

            // ==========================
            // 5. RESPUESTA
            // ==========================
            echo json_encode([
                "success" => true,

                "imagen" => $nombreImagen ? "/assets/img/" . $nombreImagen : null,
            ]);
        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "success" => false,

                "error" => $e->getMessage(),
            ]);
        }

        exit();
    }
}
