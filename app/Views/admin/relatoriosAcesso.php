<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>

<!-- <style>
.table-relatorio {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}
.table-relatorio th, .table-relatorio td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
.table-relatorio th {
    background: #1976D2;
    color: white;
}
</style> -->

<div class="container">
    <h2>📑 Relatório de Acessos</h2>

    <table class="table-relatorio">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Tipo</th>
                <th>IP</th>
                <th>Navegador</th>
                <th>Data</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log['nome']) ?></td>
                <td><?= htmlspecialchars($log['tipo_usuario']) ?></td>
                <td><?= $log['ip'] ?></td>
                <td><?= substr($log['navegador'], 0, 35) ?>...</td>
                <td><?= date('d/m/Y', strtotime($log['data_acesso'])) ?></td>
                <td><?= date('H:i', strtotime($log['data_acesso'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../templates/footerAdmin.php'; ?>
