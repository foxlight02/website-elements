<?php
// 👇 AQUÍ VA EL REQUIRE
// 👇 AQUÍ VA EL REQUIRE
require_once __DIR__ . "/../models/Contacto.php";
class ContactoController
{
    // 👉 mostrar formulario
    public function inicio()
    {
        $title = "Contacto";
        include __DIR__ . "/../views/inc/header.php";
        require __DIR__ . "/../views/contacto/inicio.php";
        include __DIR__ . "/../views/inc/footer.php";
    }

    // 👉 procesar formulario (fetch)
    // 👉 procesar formulario (fetch)

    public function enviar()
    {
        header("Content-Type: application/json");

        try {
            // 🔹 recibir FormData (POST normal)
            $nombre = $_POST["nombre"] ?? "";
            $correo = $_POST["correo"] ?? "";
            $mensaje = $_POST["mensaje"] ?? "";

            // 🔹 conexión DB (PDO)
            $pdo = new PDO("mysql:host=localhost;dbname=handyman", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 🔹 insertar
            $sql = "INSERT INTO contactos (nombre, correo, mensaje) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $correo, $mensaje]);

            // 🔹 respuesta
            echo json_encode([
                "success" => true,
                "message" => "Guardado en BD 🚀",
                "data" => [
                    "nombre" => $nombre,
                    "correo" => $correo,
                    "mensaje" => $mensaje,
                ],
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error en servidor",
                "error" => $e->getMessage(),
            ]);
        }

        exit();
    }
}
