<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="card cardio-andamento">

    <div class="cardio-info">
        <div>
            <span>Tipo</span>
            <strong><?= htmlspecialchars($cardio['tipo']) ?></strong>
        </div>

        <div>
            <span>Tempo planejado</span>
            <strong><?= $cardio['tempo_min'] ?> min</strong>
        </div>

        <?php if ($cardio['ritmo']): ?>
        <div>
            <span>Ritmo</span>
            <strong><?= htmlspecialchars($cardio['ritmo']) ?></strong>
        </div>
        <?php endif; ?>
    </div>

    <div class="cardio-timer">
        <!-- <span>⏱</span> -->
        <h3 id="timer">00:00</h3>
    </div>
<div class="progress-wrapper">
    <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
    </div>
    <small id="progressText">0%</small>
</div>

    <form method="post" action="/ACADEMY/public/cardio/finalizar">
        <button type="submit" class="btn-finalizar">
            Finalizar Cardio
        </button>
    </form>

</div>


<script>
const inicio = new Date("<?= $cardio['data_inicio'] ?>").getTime();
const tempoPlanejadoMin = <?= (int)$cardio['tempo_min'] ?>;
const tempoPlanejadoMs = tempoPlanejadoMin * 60 * 1000;

const timerEl = document.getElementById('timer');
const progressFill = document.getElementById('progressFill');
const progressText = document.getElementById('progressText');

setInterval(() => {
    const agora = new Date().getTime();
    const decorrido = agora - inicio;

    // TIMER
    const segundos = Math.floor(decorrido / 1000);
    const min = String(Math.floor(segundos / 60)).padStart(2, '0');
    const sec = String(segundos % 60).padStart(2, '0');
    timerEl.innerText = `⏱ ${min}:${sec}`;

    // PROGRESSO
    let percentual = Math.min((decorrido / tempoPlanejadoMs) * 100, 100);
    percentual = Math.floor(percentual);

    progressFill.style.width = percentual + '%';
    progressText.innerText = percentual + '% concluído';

}, 1000);
</script>


<?php include __DIR__ . '/../templates/footer.php'; ?>
