<?php include __DIR__ . '/../templates/header.php'; ?>

<h2>🏃 Cardio em andamento</h2>

<div class="card">
    <h3><?= $cardio['tipo'] ?></h3>
    <p><strong>Tempo:</strong> <?= $cardio['tempo_min'] ?> min</p>
    <p><strong>Ritmo:</strong> <?= $cardio['ritmo'] ?: '—' ?></p>

    <button id="finalizarCardio" class="btn">
        Finalizar
    </button>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>