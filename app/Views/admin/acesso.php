<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>
<div class="container">  
    <?php if (!empty($lista) && is_array($lista)): ?>
        <table border="1" cellpadding="8" cellspacing="0">
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
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($log['data_acesso']))) ?></td>
                        <td><?= htmlspecialchars($log['hora_acesso']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="margin-top:15px; font-weight:bold; color:#555;">
            ⚠ Nenhum acesso registrado ainda.
        </p>
    <?php endif; ?>
</div>
