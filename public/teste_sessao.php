<?php
session_start();

echo "<h2>Teste da Sessão do Usuário</h2>";

if (isset($_SESSION['usuario'])) {
    echo "<pre>";
    print_r($_SESSION['usuario']);
    echo "</pre>";
} else {
    echo "⚠ Nenhum usuário logado na sessão!";
}
