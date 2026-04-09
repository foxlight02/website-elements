<?php ob_start(); ?>

<section class="showcase">
    <div class="showcase__content">
        <h1>Bienvenido</h1>
        <p>Servicios profesionales</p>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require_once __DIR__ . "../layout/site.layout.php"; ?>
