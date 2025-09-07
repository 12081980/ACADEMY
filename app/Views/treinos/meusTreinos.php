<?php include __DIR__ . '/../templates/header.php'; ?>

<h1>Meus Treinos</h1>

<?php if (empty($treinos)): ?>
    <p>Você não possui treinos cadastrados.</p>
<?php else: ?>
    <ul>
        <?php foreach ($treinos as $treino): ?>
            <li><?= htmlspecialchars($treino['nome']) ?> — <?= htmlspecialchars($treino['descricao']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include __DIR__ . '/../templates/footer.php'; ?>