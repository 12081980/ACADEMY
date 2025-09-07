<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Treinos dos Alunos</title>
</head>

<body>
    <h1>Treinos Registrados</h1>
    <table border="1" cellpadding="8">
        <tr>
            <th>Aluno</th>
            <th>Data</th>
            <th>Treino</th>
            <th>Detalhes</th>
        </tr>
        <?php foreach ($treinos as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['aluno']) ?></td>
                <td><?= htmlspecialchars($t['data']) ?></td>
                <td><?= htmlspecialchars($t['treino']) ?></td>
                <td><?= htmlspecialchars($t['detalhes']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>