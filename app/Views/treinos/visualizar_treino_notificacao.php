<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h2>🏋️ Treino Enviado</h2>

    <?php if (!empty($treino)): ?>
        <p><strong>Tipo:</strong> <?= htmlspecialchars($treino['tipo']) ?></p>
        <p><strong>Data de Envio:</strong> <?= htmlspecialchars($treino['data_envio'] ?? '') ?></p>

        <h3>Exercícios</h3>
        <?php if (!empty($exercicios)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Exercício</th>
                        <th>Séries</th>
                        <th>Repetições</th>
                        <th>Carga (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($exercicios as $ex): ?>
                        <tr>
                            <td><?= htmlspecialchars($ex['nome']) ?></td>
                            <td><?= htmlspecialchars($ex['series']) ?></td>
                            <td><?= htmlspecialchars($ex['repeticoes']) ?></td>
                            <td><?= htmlspecialchars($ex['carga']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Não há exercícios cadastrados neste treino.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Treino não encontrado.</p>
    <?php endif; ?>

    <a href="/ACADEMY/public/notificacoes" class="btn">← Voltar</a>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>