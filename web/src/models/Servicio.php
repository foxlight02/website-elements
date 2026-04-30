<?php

require_once __DIR__ . "/../config/db.php";

class Servicio
{
    public static function getAll()
    {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->query("SELECT * FROM servicios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function crear($data)
    {
        $db = new Database();
        $conn = $db->getConnection();

        $sql = "INSERT INTO servicios (nombre, especialidad, telefono, descripcion, imagen)
                VALUES (:nombre, :especialidad, :telefono, :descripcion, :imagen)";

        $stmt = $conn->prepare($sql);

        return $stmt->execute($data);
    } // 🔍 obtener uno
    public static function getById($id)
    {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT * FROM servicios WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $data)
    {
        $db = new Database();
        $conn = $db->getConnection();

        $sql = "UPDATE servicios SET
        nombre = :nombre,
        especialidad = :especialidad,
        telefono = :telefono,
        descripcion = :descripcion,
        imagen = :imagen
        WHERE id = :id";

        $stmt = $conn->prepare($sql);

        return $stmt->execute([
            ":nombre" => $data["nombre"],
            ":especialidad" => $data["especialidad"],
            ":telefono" => $data["telefono"],
            ":descripcion" => $data["descripcion"],
            ":imagen" => $data["imagen"],
            ":id" => $id,
        ]);
    }
    // 🗑 eliminar
    public static function delete($id)
    {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("DELETE FROM servicios WHERE id = :id");
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
}
