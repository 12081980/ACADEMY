<?php
class GraficoController
{
    public function index()
    {
        include __DIR__ . '/../../Views/templates/header.php';
        echo "<h2>Gráfico de evolução</h2>";
        include __DIR__ . '/../../Views/templates/footer.php';
    }
}
