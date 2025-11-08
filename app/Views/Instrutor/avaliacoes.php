<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

<div class="container">
    <h2>🏋️ Escolher Aluno para Avaliação Física</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['nome']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <a href="/ACADEMY/public/instrutor/avaliacaoFicha?usuario_id=<?= $user['id'] ?>"
                                class="btn btn-primary">
                                Preencher Ficha
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nenhum aluno cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>