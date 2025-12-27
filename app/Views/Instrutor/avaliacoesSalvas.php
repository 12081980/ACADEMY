<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

<div class="container">
    <h2>Avaliações Salvas</h2>

    <table>
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Data da Avaliação</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($avaliacoes)): ?>
                <?php foreach ($avaliacoes as $av): ?>
                    <tr>
                        <td><?= htmlspecialchars($av['nome_usuario']) ?></td>
                        <td><?= date("d/m/Y", strtotime($av['data_avaliacao'])) ?></td>

                        <td>
                            <a href="/ACADEMY/public/instrutor/avaliacaoVer/<?= $av['id'] ?>" class="btn btn-primary">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nenhuma avaliação encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>