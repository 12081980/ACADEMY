<?php
include __DIR__ . '/../../Views/templates/header.php';
?>
<h1>Usuários e Treinos Realizados</h1>
<table border="1" cellpadding="5">
    <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Treino</th>
        <th>Data do Treino</th>
        <th>Tipo de Treino</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= htmlspecialchars($usuario['treino_nome'] ?? '—') ?></td>
            <td><?= !empty($usuario['data_treino']) ? date('d/m/Y', strtotime($usuario['data_treino'])) : '—' ?></td>
            <td><?= htmlspecialchars($usuario['tipo_treino'] ?? '—') ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h1>Usuários e Treinos</h1>
<table border="1" cellpadding="5">
    <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Treino</th>
        <th>Data do Treino</th>
        <th>Tipo de Treino</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= htmlspecialchars($usuario['treino_nome'] ?? '—') ?></td>
            <td><?= !empty($usuario['data_treino']) ? date('d/m/Y', strtotime($usuario['data_treino'])) : '—' ?></td>
            <td><?= htmlspecialchars($usuario['tipo_treino'] ?? '—') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
include __DIR__ . '/../../Views/templates/footer.php';
?>