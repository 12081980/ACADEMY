<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h2>🏋️‍♂️ Treinos Realizados</h2>

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
                            $nomesExercicios[] = $ex['nome'];
                            $seriesEx[] = $ex['series'];
                            $repsEx[] = $ex['repeticoes'];
                            $pesoEx[] = number_format($ex['carga'], 2, ',', '.');
                        }
                    }
                    ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($treino['data_inicio'])); ?></td>
                        <td><?= htmlspecialchars($treino['tipo']); ?></td>
                        <td><?= !empty($nomesExercicios) ? implode(', ', $nomesExercicios) : 'Nenhum exercício registrado'; ?>
                        </td>
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
</div>

<style>
    .treinos-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .treinos-table th,
    .treinos-table td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: center;
    }

    .treinos-table th {
        background-color: #007bff;
    }
</style>
<?php include __DIR__ . '/../templates/footer.php'; ?>