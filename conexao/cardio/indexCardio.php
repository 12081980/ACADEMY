<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h2> Cardio</h2>

    <div class="cards">

        <!-- INICIAR -->
        <div class="card">
            <h3> Iniciar Cardio</h3>
            <p>Caminhada ou corrida</p>
            <button class="btn" data-modal="modalCardio">
                Iniciar
            </button>
        </div>

        <!-- ANDAMENTO -->
        <div class="card">
            <h3> Em andamento</h3>

            <?php if ($cardioEmAndamento): ?>
                <p>Existe um cardio ativo</p>
                <a href="/ACADEMY/public/cardio/andamento" class="btn">
                    Ver andamento
                </a>
            <?php else: ?>
                <p>Nenhum cardio ativo</p>
                <button class="btn disabled" disabled>
                    Nenhum ativo
                </button>
            <?php endif; ?>
        </div>

        <!-- HISTÓRICO -->
        <div class="card">
            <h3> Histórico</h3>
            <p>Cardios realizados</p>
            <a href="/ACADEMY/public/cardio/historico" class="btn">
                Ver histórico
            </a>
        </div>

    </div>
</div>

<!-- 🔽 MODAL REUTILIZADO -->
<?php include __DIR__ . '/modalCardio.php'; ?>

<!-- 🔽 SCRIPT -->
<script src="/ACADEMY/public/js/cardio.js"></script>
<?php include __DIR__ . '/../templates/footer.php'; ?>