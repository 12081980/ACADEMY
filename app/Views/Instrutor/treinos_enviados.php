<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

<div class="container">
<table class="tabela-treinos">
    <thead>
        <tr>
            <th>Aluno</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Data de Envio</th>
        </tr>
    </thead>

    <tbody>
        <?php if ($treinos): ?>
            <?php foreach ($treinos as $t): ?>
                <tr class="linha-principal">
                    <td><?= htmlspecialchars($t['aluno_nome']) ?></td>
                    <td><?= htmlspecialchars($t['aluno_email']) ?></td>
                    <td><?= htmlspecialchars($t['tipo']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($t['criado_em'])) ?></td>
                </tr>

                <?php if (!empty($t['exercicios'])): ?>
                    <tr class="linha-detalhes">
                        <td colspan="4">
                            <table class="tabela-exercicios">
                                <thead>
                                    <tr>
                                        <th>Exercício</th>
                                        <th>Séries</th>
                                        <th>Repetições</th>
                                        <th>Carga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($t['exercicios'] as $ex): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($ex['nome_exercicio']) ?></td>
                                            <td><?= $ex['series'] ?></td>
                                            <td><?= $ex['repeticoes'] ?></td>
                                            <td><?= $ex['carga'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="vazio">Nenhum treino enviado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- PAGINAÇÃO -->
<?php
$urlBase = strtok($_SERVER['REQUEST_URI'], '?');
?>

<?php if ($totalPaginas > 1): ?>
    <nav class="paginacao">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <?php if ($i == $paginaAtual): ?>
                <span class="pagina ativa"><?= $i ?></span>
            <?php else: ?>
                <a class="pagina"
                   href="<?= $urlBase ?>?pagina=<?= $i ?>">
                    <?= $i ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>
    </nav>
<?php endif; ?>


</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
