<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>

<div class="dashboard-container">

    

    <div class="cards">
        <div class="card users">
            <div class="card-icon">👥</div>
            <div class="card-info">
                <span>Usuários Cadastrados</span>
                <strong><?= $totalUsuarios ?? 0 ?></strong>
            </div>
        </div>

        <div class="card trainers">
            <div class="card-icon">🏋️‍♂️</div>
            <div class="card-info">
                <span>Instrutores</span>
                <strong><?= $dados['instrutores_total'] ?? 0 ?></strong>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../templates/footerAdmin.php'; ?>
