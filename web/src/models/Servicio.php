<?php
class Servicio
{
    private static function db()
    {
        $db = new Database();
        return $db->getConnection();
    }

    public static function getAll()
    {
        $conn = self::db();
        return $conn->query("SELECT * FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $conn = self::db();

        $stmt = $conn->prepare("SELECT * FROM servicios WHERE id = :id");
        $stmt->execute([":id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function crear($data)
    {
        $conn = self::db();

        $sql = "INSERT INTO servicios (nombre, especialidad, telefono, descripcion, imagen)
                VALUES (:nombre, :especialidad, :telefono, :descripcion, :imagen)";

        $stmt = $conn->prepare($sql);

        return $stmt->execute([
            ":nombre" => $data["nombre"],
            ":especialidad" => $data["especialidad"],
            ":telefono" => $data["telefono"],
            ":descripcion" => $data["descripcion"],
            ":imagen" => $data["imagen"] ?? null,
        ]);
    }

    public static function update($id, $data)
    {
        $db = new Database();
        $conn = $db->getConnection();

        $sql = "UPDATE servicios SET
        nombre = :nombre,
        especialidad = :especialidad,
        telefono = :telefono,
        descripcion = :descripcion";

        if (!empty($data["imagen"])) {
            $sql .= ", imagen = :imagen";
        }

        $sql .= " WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $params = [
            ":nombre" => $data["nombre"],
            ":especialidad" => $data["especialidad"],
            ":telefono" => $data["telefono"],
            ":descripcion" => $data["descripcion"],
            ":id" => (int) $id,
        ];

        if (!empty($data["imagen"])) {
            $params[":imagen"] = $data["imagen"];
        }

        return $stmt->execute($params);
    }

    public static function delete($id)
    {
        $conn = self::db();

        $stmt = $conn->prepare("SELECT imagen FROM servicios WHERE id = :id");
        $stmt->execute([":id" => $id]);
        $servicio = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("DELETE FROM servicios WHERE id = :id");
        $ok = $stmt->execute([":id" => $id]);

        if ($ok && !empty($servicio["imagen"])) {
            $ruta = __DIR__ . "/../../public/assets/img/" . $servicio["imagen"];
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        return $ok;
    }
}
