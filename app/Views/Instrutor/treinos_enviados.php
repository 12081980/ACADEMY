<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

<div class="container">
    <h2>📋 Treinos Enviados</h2>

    <?php if (!empty($treinos)): ?>
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Email</th>
                    <th>Treino</th>
                    <th>Data de Envio</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($treinos as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['aluno_nome']) ?></td>
                        <td><?= htmlspecialchars($t['email']) ?></td>
                        <td><?= htmlspecialchars($t['tipo']) ?></td>
                        <td><?= htmlspecialchars($t['data_envio']) ?></td>
                        <td>
                            <a href="/ACADEMY/public/treinos/ver/<?= urlencode($t['id']) ?>" class="btn">Ver Treino</a>
                        </td>
                        <td>
                            <a href="/ACADEMY/public/treinos/ver/<?= urlencode($t['id']) ?>" class="btn">Ver</a>
                            <a href="/ACADEMY/public/instrutor/editar_treino?id=<?= urlencode($t['id']) ?>"
                                class="btn">Editar</a>
                            <a href="/ACADEMY/public/instrutor/excluir_treino?id=<?= urlencode($t['id']) ?>" class="btn"
                                onclick="return confirm('Deseja realmente excluir este treino?')">Excluir</a>
                        </td>
                        <td>
                            <a href="/ACADEMY/public/treinos/ver/<?= urlencode($t['id']) ?>" class="btn">Ver</a>
                            <a href="/ACADEMY/public/instrutor/editar_treino?id=<?= urlencode($t['id']) ?>"
                                class="btn">Editar</a>
                            <a href="/ACADEMY/public/instrutor/excluir_treino?id=<?= urlencode($t['id']) ?>" class="btn"
                                onclick="return confirm('Deseja realmente excluir este treino?')">Excluir</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum treino enviado até o momento.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../templates/footerInstrutor.php'; ?>