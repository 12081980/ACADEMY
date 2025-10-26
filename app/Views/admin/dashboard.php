<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>



<body>

    <main class="dashboard-container">

        <div class="cards">
            <div class="card">
                <h2>Usuários Cadastrados</h2>
                <p><?= $totalUsuarios ?? 0 ?></p>
            </div>

            <div class="card">
                <h2>Treinos Realizados</h2>
                <p><?= $totalTreinosFinalizados ?? 0 ?></p>
            </div>

            <div class="card">
                <h2>Treinos em Andamento</h2>
                <p><?= $totalTreinosEmAndamento ?? 0 ?></p>
            </div>
        </div>

        <div class="actions">
            <a href="/ACADEMY/public/admin/lista_usuario" class="button">👤 Gerenciar Usuários</a>
            <a href="/ACADEMY/public/admin/treinos_realizados" class="button">🏋️ Treinos Realizados</a>
        </div>

        <?php include __DIR__ . '/../templates/footerAdmin.php'; ?>