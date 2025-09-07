<?php


$usuario = $_SESSION['usuario']['nome'] ?? 'Usuário';
$treino = $_SESSION['treino_em_andamento'] ?? null;
?>
<?php
include __DIR__ . '/../../Views/templates/header.php';
?>
<h1>Treino em Andamento</h1>

<!-- <p><strong>Usuário:</strong> <?= htmlspecialchars($usuario) ?></p> -->

<?php if ($treino): ?>
    <p><strong>Nome do Treino:</strong> <?= htmlspecialchars($treino['nome']) ?></p>
    <p><strong>Início:</strong> <?= date('d/m/Y H:i:s', strtotime($treino['inicio'])) ?></p>

    <h3>Exercícios:</h3>
    <ul>
        <?php foreach ($treino['exercicios'] as $exercicio): ?>
            <li>
                <?= htmlspecialchars($exercicio['nome']) ?> -
                <?= htmlspecialchars($exercicio['series']) ?> séries de
                <?= htmlspecialchars($exercicio['repeticoes']) ?> reps
                <?= isset($exercicio['peso']) ? '- ' . htmlspecialchars($exercicio['peso']) . ' kg' : '' ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <form method="post" action="/ACADEMY/public/treinos/finalizar">
        <button type="submit">Finalizar Treino</button>
    </form>

    <br>
    <a href="/ACADEMY/public/home">
        <button type="button">Voltar</button>
    </a>

<?php else: ?>
    <p>Nenhum treino em andamento.</p>
<?php endif; ?>

<?php
include __DIR__ . '/../../Views/templates/footer.php';
?>