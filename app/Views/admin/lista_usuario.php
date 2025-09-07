<?php
include __DIR__ . '/../../Views/templates/header.php';
?>
<h1>Usuários e Treinos</h1>
<table border="1" cellpadding="5">
    <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Treinos Realizados</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td>
                <?php if (!empty($usuario['treinos'])): ?>
                    <ul>
                        <?php foreach ($usuario['treinos'] as $treino): ?>
                            <li>
                                <?= htmlspecialchars($treino['treino_nome']) ?>
                                - <?= !empty($treino['data_treino']) ? date('d/m/Y', strtotime($treino['data_treino'])) : '—' ?>
                                (<?= htmlspecialchars($treino['tipo_treino'] ?? '—') ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
include __DIR__ . '/../../Views/templates/footer.php';
?>