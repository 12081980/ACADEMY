<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <base href="/ACADEMY/public">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ACADEMY/public/css/style.css">
</head>

<body>

<header>
    <div class="container">
        <nav>

            <!-- BOTÃO HAMBURGUER -->
            <button class="hamburger" onclick="toggleMenu()" aria-label="Menu">☰</button>

            <!-- MENU -->
            <ul id="menu">
                <li><a href="/ACADEMY/public/admin/dashboard">🏠 Dashboard</a></li>
                <li><a href="/ACADEMY/public/admin/lista_usuario">👥 Usuários</a></li>
                <li><a href="/ACADEMY/public/admin/relatoriosAcesso">📊 Relatórios de Acesso</a></li>

                <li>
                    <form action="/ACADEMY/public/auth/logout" method="post">
                        <button type="submit" class="menu-link btn-logout">
                            🚪 Sair
                        </button>
                    </form>
                </li>
            </ul>

        </nav>
    </div>
</header>
<script>
function toggleMenu() {
    document.getElementById('menu').classList.toggle('open');
}
</script>

</body>