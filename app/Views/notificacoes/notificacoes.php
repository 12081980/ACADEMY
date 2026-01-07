<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h2>📩 Notificações</h2>

    <?php if (!empty($notificacoes)): ?>
        <ul class="notificacoes-lista">
            <?php foreach ($notificacoes as $n): ?>
    <li>
        <a href="/ACADEMY/public/notificacoes/ver?id=<?= urlencode($n['id']) ?>"
           class="notificacao-link">
            <?= htmlspecialchars($n['mensagem']) ?>
        </a>
        — <small><?= htmlspecialchars($n['data_envio']) ?></small>
    </li>
<?php endforeach; ?>

</li>


           
        </ul>
    <?php else: ?>
        <p>Nenhuma notificação encontrada.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>