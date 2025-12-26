<head>
    <meta charset="UTF-8">

    <!-- IGUAL AO HEADER QUE FUNCIONA -->
    <base href="/ACADEMY/public">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/ACADEMY/public/css/style.css">
</head>

<body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
  <div class="container">
        <nav>
            <button class="hamburger" onclick="toggleMenu()" aria-label="Menu">☰</button>
      

      <ul id="menu">
        <li><a href="/ACADEMY/public/instrutor/dashboardInstrutor">🏠 INÍCIO</a></li>
        <li><a href="/ACADEMY/public/instrutor/enviar">📤 ENVIAR TREINO</a></li>
        <li><a href="/ACADEMY/public/instrutor/treinos_enviados">📋 TREINOS ENVIADOS</a></li>
        <li><a href="/ACADEMY/public/instrutor/avaliacaoEscolher">🧾 AVALIAÇÕES FÍSICAS</a></li>
        <li><a href="/ACADEMY/public/instrutor/avaliacoesSalvas">📋 AVALIAÇÕES REALIZADAS</a></li>
        <li><a href="/ACADEMY/public/auth/logout">🚪 SAIR</a></li>
      </ul>

    </nav>
  </div>
</header>


<main>
    <script>
                function toggleMenu() {
                    document.getElementById('menu').classList.toggle('open');
                }
            </script>
<div class="containerBody">

