<?php

class HomeController {

    public function inicio() {
        $title = "Home";

        include __DIR__ . '/../views/inc/header.php';
        include __DIR__ . '/../views/home/inicio.php';
        include __DIR__ . '/../views/inc/footer.php';
    }
}
