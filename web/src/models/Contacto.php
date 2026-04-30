<?php

require_once __DIR__ . "/../config/db.php";

class Contacto
{
    public static function guardar($nombre, $correo, $mensaje)
    {
        try {
            // 🔌 conexión PDO
            $database = new Database();
            $conn = $database->getConnection();

            if (!$conn) {
                return [
                    "status" => "error",
                    "mensaje" => "No se pudo conectar a la BD",
                ];
            }

            // 🧠 SQL con placeholders
            $sql = "INSERT INTO contactos (nombre, correo, mensaje)
                    VALUES (:nombre, :correo, :mensaje)";

            $stmt = $conn->prepare($sql);

            // 🔗 bind con PDO
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":correo", $correo);
            $stmt->bindParam(":mensaje", $mensaje);

            // 🚀 ejecutar
            $stmt->execute();

            return [
                "status" => "ok",
                "mensaje" => "Mensaje guardado correctamente 💾",
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "mensaje" => "Error: " . $e->getMessage(),
            ];
        }
    }
}
