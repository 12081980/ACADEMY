<?php include __DIR__ . '/../../Views/templates/header.php'; ?>

<h2>Detalhes do Usuário</h2>

<div class="usuario-detalhes">
    <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
</div>

<h3>Treinos Realizados</h3>
<?php if (!empty($treinos)): ?>
    <table>
        <thead>
            <tr>
                <th>Nome do Treino</th>
                <th>Data</th>
                <th>Duração (min)</th>
                <th>Qtd Exercícios</th>
                <th>Peso Total (kg)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($treinos as $treino): ?>
                <tr>
                    <td><?= htmlspecialchars($treino['nome'] ?? 'Treino sem nome') ?></td>
                    <td><?= isset($treino['data_realizacao']) ? date('d/m/Y', strtotime($treino['data_realizacao'])) : '—' ?>
                    </td>
                    <td><?= intval($treino['duracao'] ?? 0) ?></td>
                    <td><?= intval($treino['qtd_exercicios'] ?? 0) ?></td>
                    <td><?= floatval($treino['peso_total'] ?? 0) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Este usuário ainda não realizou nenhum treino.</p>
<?php endif; ?>



<?php include __DIR__ . '/../../Views/templates/footer.php'; ?>