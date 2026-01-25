<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <base href="/ACADEMY/public">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ACADEMY/public/css/style.css">
</head>

<body>

<header class="site-header">
    <nav class="container">

        <!-- BOTÃO HAMBURGER -->
        <button class="hamburger" onclick="toggleMenu()" aria-label="Menu">☰</button>

        <!-- MENU -->
        <ul id="menu">
            <li>
                <a href="/ACADEMY/public/admin/dashboard"> DASHBOARD</a>
            </li>

            <li>
                <a href="/ACADEMY/public/admin/lista_usuario">USUÁRIOS</a>
            </li>

            <li>
                <a href="/ACADEMY/public/admin/relatoriosAcesso"> RELATÓRIOS</a>
            </li>

            <li class="menu-perfil">
                <a href="#">
                    👋 <?= htmlspecialchars(explode(' ', trim($_SESSION['usuario']['nome']))[0] ?? 'Admin') ?>
                </a>
            </li>

            <li>
                <form action="/ACADEMY/public/auth/logout" method="post">
                    <button type="submit" class="menu-link btn-logout">
                         SAIR
                    </button>
                </form>
            </li>
        </ul>

    </nav>
</header>

<script>
function toggleMenu() {
    document.getElementById('menu').classList.toggle('open');
}
</script>
