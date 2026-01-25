<?php include __DIR__ . '/../templates/header.php'; ?>

<h2 class="titulo-pagina"> Histórico de Cardio</h2>



<?php if (empty($lista)): ?>
    <p class="msg-vazia">Nenhum cardio registrado.</p>
<?php endif; ?>

<div class="cards-cardio">
<?php foreach ($lista as $c): ?>
    <div class="card-cardio">
        <h3><?= htmlspecialchars($c['tipo']) ?></h3>

        <div class="info">
            <span><strong>Tempo:</strong> <?= $c['tempo_min'] ?> min</span>
            <span><strong>Ritmo:</strong> <?= $c['ritmo'] ?: '—' ?></span>
        </div>

        <small>
            <?= date('d/m/Y H:i', strtotime($c['data_inicio'])) ?>
        </small>
    </div>
<?php endforeach; ?>
</div>

<!-- PAGINAÇÃO -->
<?php if ($totalPaginas > 1): ?>
<div class="paginacao">
    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="/ACADEMY/public/cardio/historico?page=<?= $i ?>"
           class="btn btn-pagina <?= $i == $pagina ? 'ativo' : '' ?>">
            <?= $i ?>
        </a>
        
    <?php endfor; ?>
</div>
<a href="/ACADEMY/public/cardio/indexCardio" class="btn btn-voltar">
     Voltar
</a>
<?php endif; ?>

<?php include __DIR__ . '/../templates/footer.php'; ?>
