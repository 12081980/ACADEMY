<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>

<div class="container">

    <h2>📊 Relatório de Acessos</h2>

    <?php if (!empty($lista)): ?>
        <table class="tabela">
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
                <?php foreach ($lista as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['nome_usuario']) ?></td>
                        <td><?= htmlspecialchars($log['tipo_usuario']) ?></td>
                        <td><?= htmlspecialchars($log['ip']) ?></td>
                        <td><?= htmlspecialchars($log['navegador']) ?></td>
                        <td><?= date('d/m/Y', strtotime($log['data_acesso'])) ?></td>
                        <td><?= htmlspecialchars($log['hora_acesso']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>⚠ Nenhum acesso registrado.</p>
    <?php endif; ?>

</div>
