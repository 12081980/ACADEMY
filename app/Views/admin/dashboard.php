 <?php include __DIR__ . '/../templates/menuAdmin.php'; ?>   

    <div class="cards">
        <div class="card users">
            <h2>👥 Usuários Cadastrados</h2>
            <p><?= $totalUsuarios ?? 0 ?></p>
        </div>

        <div class="card students">
            <h2>🧑‍🎓 Alunos Ativos</h2>
            <p><?= $totalAlunos ?? 0 ?></p>
        </div>

        <div class="card trainers">
            <h2>🏋️‍♂️ Instrutores</h2>
            <p><?= $totalInstrutores ?? 0 ?></p>
        </div>
    </div>

    <div class="actions">
        <a href="/ACADEMY/public/admin/lista_usuario" class="button">👤 Gerenciar Usuários</a>
        <a href="/ACADEMY/public/admin/sistema" class="button">⚙️ Configurações do Sistema</a>
        <a href="/ACADEMY/public/admin/relatoriosAcesso" class="button">📑 Relatórios de Acesso</a>

    </div>


<?php include __DIR__ . '/../templates/footerAdmin.php'; ?>
