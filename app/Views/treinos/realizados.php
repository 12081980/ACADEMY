<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Treinos Realizados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .paginacao {
            margin-top: 20px;
            text-align: center;
        }

        .paginacao a {
            margin: 0 5px;
            padding: 8px 12px;
            border: 1px solid #007bff;
            border-radius: 5px;
            text-decoration: none;
            color: #007bff;
        }

        .paginacao a.ativo,
        .paginacao a:hover {
            background: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>
    <h1>Treinos Realizados</h1>

    <?php if (!empty($treinos)): ?>
        <table>
            <tr>
                <th>Nome</th>
                <th>Data</th>
                <th>Duração (min)</th>
            </tr>
            <?php foreach ($treinos as $treino): ?>
                <tr>
                    <td><?= htmlspecialchars($treino['nome']) ?></td>
                    <td><?= htmlspecialchars($treino['data_realizacao']) ?></td>
                    <td><?= htmlspecialchars($treino['duracao']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="paginacao">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" class="<?= ($i == $pagina) ? 'ativo' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php else: ?>
        <p>Nenhum treino realizado encontrado.</p>
    <?php endif; ?>
    <!-- Botão Voltar -->
    <button onclick="window.location.href='/ACADEMY/public/home'">Voltar</button>
</body>

</html>