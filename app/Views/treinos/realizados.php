<?php include __DIR__ . '/../templates/header.php'; ?>

<?php
$paginaAtual = $paginaAtual ?? 1;
$totalPaginas = !empty($totalPaginas) ? $totalPaginas : 1;
$treinos = !empty($treinos) ? $treinos : [];
?>


<div class="container">  

    <table class="treinos-table" border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Data do Treino</th>
                <th>Tipo</th>
                <th>Exercício</th>
                <th>Séries</th>
                <th>Repetições</th>
                <th>Peso (kg)</th>
                <th>Total de Exercícios</th>
                <th>Peso Total (kg)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($treinos)): ?>
                <?php foreach ($treinos as $treino): ?>
                    <?php
                    $totalExercicios = !empty($treino['exercicios']) ? count($treino['exercicios']) : 0;
                    $pesoTotal = !empty($treino['exercicios']) ? array_sum(array_column($treino['exercicios'], 'carga')) : 0;

                    $nomesExercicios = [];
                    $seriesEx = [];
                    $repsEx = [];
                    $pesoEx = [];

                    if (!empty($treino['exercicios'])) {
                        foreach ($treino['exercicios'] as $ex) {
                            $nomesExercicios[] = $ex['nome_exercicio'];
                            $seriesEx[] = $ex['series'];
                            $repsEx[] = $ex['repeticoes'];
                            $pesoEx[] = number_format($ex['carga'], 2, ',', '.');
                        }
                    }
                    ?>
                    <tr>
                        <td>
                            <?= !empty($treino['data_treino'])
                                ? date('d/m/Y', strtotime($treino['data_treino']))
                                : 'Data não informada'; ?>
                        </td>
                        <td><?= htmlspecialchars($treino['tipo']); ?></td>
                        <td><?= !empty($nomesExercicios) ? implode(', ', $nomesExercicios) : 'Nenhum exercício registrado'; ?></td>
                        <td><?= !empty($seriesEx) ? implode(', ', $seriesEx) : '-'; ?></td>
                        <td><?= !empty($repsEx) ? implode(', ', $repsEx) : '-'; ?></td>
                        <td><?= !empty($pesoEx) ? implode(', ', $pesoEx) : '-'; ?></td>
                        <td><?= $totalExercicios; ?></td>
                        <td><?= number_format($pesoTotal, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align:center;">Nenhum treino realizado ainda.</td>
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
                   <a href="/ACADEMY/public/treinos/realizados?pagina=<?= $i ?>" 
   style="margin:0 5px; text-decoration:none; <?= $paginaAtual == $i ? 'font-weight: bold; color: red;' : '' ?>">
   <?= $i ?>
</a>



                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
