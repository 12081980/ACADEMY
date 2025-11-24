<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>
<div class="container">
<?php

$paginaAtual = $paginaAtual ?? 1;
$totalPaginas = !empty($totalPaginas) ? $totalPaginas : 1;
$treinos = !empty($treinos) ? $treinos : [];
?>


<table>
    <thead>
        <tr>
            <th>Aluno</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Data de Envio</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($treinos)): ?>
            <?php foreach ($treinos as $t): ?>
                <tr style="background:#f4f4f4">
                    <td><?= htmlspecialchars($t['aluno_nome'] ?? 'Não informado') ?></td>
                    <td><?= htmlspecialchars($t['aluno_email'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($t['tipo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars(!empty($t['criado_em']) ? date('d/m/Y H:i', strtotime($t['criado_em'])) : '-') ?></td>
                </tr>

                <?php if (!empty($t['exercicios'])): ?>
                    <tr>
                        <td colspan="5">
                            <table style="width:100%; border-collapse:collapse; margin:5px 0; background:#fff;">
                                <thead>
                                    <tr style="background:#eaeaea">
                                        <th>Exercício</th>
                                        <th>Séries</th>
                                        <th>Repetições</th>
                                        <th>Carga (kg)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($t['exercicios'] as $ex): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($ex['nome_exercicio'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($ex['series'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($ex['repeticoes'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($ex['carga'] ?? '-') ?></td>
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
                <td colspan="5">Nenhum treino enviado ainda.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

 <?php if ($totalPaginas > 1): ?>
    <div class="paginacao" style="margin-top:20px; text-align:center;">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <?php if ($i == $paginaAtual): ?>
                <strong>[<?= $i ?>]</strong>
            <?php else: ?>
               <a href="/ACADEMY/public/instrutor/treinos/enviados?pagina=<?= $i ?>" 
                   style="margin:0 5px; text-decoration:none; <?= $paginaAtual == $i ? 'font-weight: bold; color: red;' : '' ?>">
                   <?= $i ?>
               </a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>

</div>
 <?php include __DIR__ . '/../templates/footer.php'; ?>  


