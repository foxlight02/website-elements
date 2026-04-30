<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Handyman Services" />

    <title><?= $title ?? "Mi Sitio" ?></title>



    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<header class="header">
    <div class="container">
         <?php
         include __DIR__ . "/nav.php";
         define("URL_PATH", "http://handyman.test/");
         ?>
    </div>
</header>
