<?php
require_once __DIR__ . "/../src/config/db.php";

// 👇 AQUÍ ESTÁ LA CLAVE (sin url)
$entidad = $_GET["entidad"] ?? "home";
$action = $_GET["action"] ?? "inicio";
$id = $_GET["id"] ?? null;

// controllers válidos
$controllers = [
    "home" => "HomeController",
    "servicios" => "ServiciosController",
    "about" => "AboutController",
    "contacto" => "ContactoController",
];

if (!isset($controllers[$entidad])) {
    die("404");
}

// cargar controller
$controllerName = $controllers[$entidad];
require_once __DIR__ . "/../src/controllers/$controllerName.php";

$controller = new $controllerName();

// pasar ID
if ($id) {
    $_GET["id"] = $id;
}

// validar acción
if (!method_exists($controller, $action)) {
    die("Acción no existe");
}

// ejecutar
$controller->$action();
