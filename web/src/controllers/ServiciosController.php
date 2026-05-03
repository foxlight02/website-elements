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
        header("Content-Type: application/json");

        try {
            // 1. Detectar si viene JSON o FormData
            $json = file_get_contents("php://input");
            $datos = json_decode($json, true);

            if (!$datos) {
                $datos = $_POST;
            }

            // 2. Limpiar datos
            $nombre = trim($datos["nombre"] ?? "");
            $especialidad = trim($datos["especialidad"] ?? "");
            $telefono = trim($datos["telefono"] ?? "");
            $descripcion = trim($datos["descripcion"] ?? "");

            // 3. Validación
            if ($nombre === "" || $especialidad === "") {
                throw new Exception("Nombre y especialidad son obligatorios");
            }

            // ---------------------------------------------------------
            // 4. INSERT REAL en la base de datos
            // ---------------------------------------------------------
            $db = new Database();
            $conn = $db->getConnection();

            $sql = "INSERT INTO servicios (nombre, especialidad, telefono, descripcion)
                VALUES (:nombre, :especialidad, :telefono, :descripcion)";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ":nombre" => $nombre,
                ":especialidad" => $especialidad,
                ":telefono" => $telefono,
                ":descripcion" => $descripcion,
            ]);

            $nuevoId = $conn->lastInsertId();

            // ---------------------------------------------------------
            // 5. Respuesta OK
            // ---------------------------------------------------------
            echo json_encode([
                "success" => true,
                "message" => "Servicio creado correctamente",
                "data" => [
                    "id" => $nuevoId,
                    "nombre" => htmlspecialchars($nombre),
                    "especialidad" => htmlspecialchars($especialidad),
                    "telefono" => htmlspecialchars($telefono),
                    "descripcion" => htmlspecialchars($descripcion),
                ],
            ]);
        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "success" => false,
                "message" => "Error al guardar",
                "error" => $e->getMessage(),
            ]);
        }

        exit();
    }

    // 🔹 EDIT (formulario)
    public function edit()
    {
        $id = $_GET["id"] ?? null;

        $servicio = Servicio::getById($id);

        include __DIR__ . "/../views/inc/header.php";
        require __DIR__ . "/../views/servicios/edit.php";
        include __DIR__ . "/../views/inc/footer.php";
    }

    // 🔹 DELETE
    public function delete()
    {
        header("Content-Type: application/json");

        try {
            // Leer datos del cuerpo de la petición (JSON)
            $json = file_get_contents("php://input");
            $datos = json_decode($json, true);

            // Si no es JSON, intentar leer desde $_POST tradicional
            if (!$datos) {
                $datos = $_POST;
            }

            // Validar y limpiar ID
            $id = isset($datos["id"]) ? (int) $datos["id"] : 0;

            if ($id <= 0) {
                throw new Exception("ID inválido.");
            }

            // Ejecutar eliminación en el Modelo
            $resultado = Servicio::delete($id);

            if (!$resultado) {
                throw new Exception("No se pudo eliminar el registro.");
            }

            // Respuesta de éxito
            echo json_encode([
                "success" => true,
                "message" => "Servicio eliminado correctamente.",
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
    // 🔹 UPDATE
    public function update()
    {
        header("Content-Type: application/json");

        try {
            // 1. Leer datos (JSON o FormData)
            $json = file_get_contents("php://input");
            $datos = json_decode($json, true);

            if (!$datos) {
                $datos = $_POST;
            }

            // 2. Limpiar datos
            $id = isset($datos["id"]) ? (int) $datos["id"] : 0;
            $nombre = trim($datos["nombre"] ?? "");
            $especialidad = trim($datos["especialidad"] ?? "");
            $telefono = trim($datos["telefono"] ?? "");
            $descripcion = trim($datos["descripcion"] ?? "");

            // 3. Validación
            if ($id <= 0) {
                throw new Exception("ID inválido");
            }

            if ($nombre === "" || $especialidad === "") {
                throw new Exception("Nombre y especialidad son obligatorios");
            }

            // 4. Llamar al modelo
            $resultado = Servicio::update($id, [
                "nombre" => $nombre,
                "especialidad" => $especialidad,
                "telefono" => $telefono,
                "descripcion" => $descripcion,
            ]);

            // 5. Respuesta
            echo json_encode([
                "success" => true,
                "updated" => $resultado,
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
