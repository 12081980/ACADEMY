<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h2>📩 Notificações</h2>

    <?php if (!empty($notificacoes)): ?>
        <ul class="notificacoes-lista">
            <?php foreach ($notificacoes as $n): ?>
                <?php
                $link = '#';
                if (!empty($n['treino_id'])) {
                    $link = "/ACADEMY/public/notificacoes/ver?id=" . urlencode($n['id']);
                }
                ?>
                <li>
                    <?php if ($link !== '#'): ?>
                        <a href="<?= $link ?>" class="notificacao-link">
                            <?= htmlspecialchars($n['mensagem']) ?>
                        </a>
                    <?php else: ?>
                        <?= htmlspecialchars($n['mensagem']) ?>
                    <?php endif; ?>
                    — <small><?= htmlspecialchars($n['data_envio'] ?? '') ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhuma notificação encontrada.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>