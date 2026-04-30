<?php

class AboutController {

    public function inicio() {
        $title = "About";

        include __DIR__ . '/../views/inc/header.php';
        include __DIR__ . '/../views/about/inicio.php';
        include __DIR__ . '/../views/inc/footer.php';
    }
}
