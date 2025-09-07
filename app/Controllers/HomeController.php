<?php
class HomeController
{
    public function index()
    {
        include __DIR__ . '/../Views/templates/header.php';
        include __DIR__ . '/../Views/index.php';
        include __DIR__ . '/../Views/templates/footer.php';
    }
}
