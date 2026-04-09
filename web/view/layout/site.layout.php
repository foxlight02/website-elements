<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?? 'Mi Sitio' ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<!-- ===== HEADER ===== -->
<header class="header">
    <div class="container">
        <nav class="navbar">
            <div class="navbar__logo">
                <i data-lucide="lightbulb"></i>
            </div>

            <ul class="navbar__menu">
                <li><a class="navbar__link" href="/">Home</a></li>
                <li><a class="navbar__link" href="/services">Services</a></li>
                <li><a class="navbar__link" href="/about">About</a></li>
                <li><a class="navbar__link" href="/contact">Contact</a></li>
            </ul>

            <div class="navbar__cta">
                <a href="#" class="navbar__button">HANDYMAN</a>
            </div>
        </nav>
    </div>
</header>

<!-- ===== CONTENIDO DINÁMICO ===== -->
<main>
    <?= $content ?>
</main>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="container">
        <p class="footer__bottom">
            © <?= date('Y') ?> Handyman. Todos los derechos reservados.
        </p>
    </div>
</footer>

<script>
    lucide.createIcons();
</script>

</body>
</html>
