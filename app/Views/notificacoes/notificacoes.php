<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h2>📩 Notificações</h2>

    <?php if (!empty($notificacoes)): ?>
        <ul class="notificacoes-lista">
            <?php foreach ($notificacoes as $n): ?>
                <li>
                    <?php
                    // Determina o link de redirecionamento com base no tipo da notificação
                    $link = '#';
                    if (!empty($n['treino_id'])) {
                        // Notificação de treino
                        $link = "/ACADEMY/public/notificacoes/ver?id=" . urlencode($n['id']);
                    } elseif (!empty($n['link_personalizado'])) {
                        // Caso o banco tenha um campo 'link_personalizado' (opcional)
                        $link = htmlspecialchars($n['link_personalizado']);
                    }
                    ?>

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