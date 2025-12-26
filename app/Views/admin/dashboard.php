
<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>

<div class="dashboard-container">

    
    
    <div class="cards">
        <div class="card users">
            <h2>👥 Usuários Cadastrados</h2>
            <p><?= $totalUsuarios ?? 0 ?></p>
        </div>

        <div class="card trainers">
            <h2>🏋️‍♂️ Instrutores</h2>
            <p><?= $dados['instrutores_total'] ?? 0 ?></p>
        </div>
    </div>
   
</div>

<?php include __DIR__ . '/../templates/footerAdmin.php'; ?>
