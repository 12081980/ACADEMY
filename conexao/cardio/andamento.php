<?php include __DIR__ . '/../templates/header.php'; ?>

<?php if (!$cardio): ?>
    <p>Nenhum cardio em andamento.</p>

    

    <?php include __DIR__ . '/../templates/footer.php'; ?>
    <?php return; ?>
<?php endif; ?>

<h2> Cardio em Andamento</h2>

<div class="card">
    <p><strong>Tipo:</strong> <?= htmlspecialchars($cardio['tipo']) ?></p>
    <p><strong>Tempo planejado:</strong> <?= $cardio['tempo_min'] ?> min</p>

    <?php if ($cardio['ritmo']): ?>
        <p><strong>Ritmo:</strong> <?= htmlspecialchars($cardio['ritmo']) ?></p>
    <?php endif; ?>

    <h3 id="timer">⏱ 00:00</h3>

    <form method="post" action="/ACADEMY/public/cardio/finalizar">
        <button type="submit" class="btn-iniciar-treino">
            Finalizar Cardio
        </button>
    </form>

   
</div>

<script>
const inicio = new Date("<?= $cardio['data_inicio'] ?>").getTime();

setInterval(() => {
    const agora = new Date().getTime();
    const diff = Math.floor((agora - inicio) / 1000);

    const min = String(Math.floor(diff / 60)).padStart(2, '0');
    const sec = String(diff % 60).padStart(2, '0');

    document.getElementById('timer').innerText = `⏱ ${min}:${sec}`;
}, 1000);
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
