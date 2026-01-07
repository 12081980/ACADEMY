<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container notificacao-avaliacao">

    <h2>📊 Nova Avaliação Física</h2>

   <?php if (!$avaliacao): ?>
    <p style="color:red;">Avaliação não encontrada.</p>
    <?php return; ?>
<?php endif; ?>

<p>
    <strong>Data da avaliação:</strong>
    <?= date('d/m/Y H:i', strtotime($avaliacao['data_avaliacao'])) ?>
</p>


    <p>
        Esta avaliação foi registrada pelo instrutor.
    </p>

    <a
        href="/ACADEMY/public/usuario/avaliacaoVer/<?= $avaliacao['id'] ?>"
        class="btn"
    >
        👁️ Ver Avaliação
    </a>

    <br><br>

    <a href="/ACADEMY/public/notificacoes" class="btn-voltar">
        ⬅ Voltar
    </a>

</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
