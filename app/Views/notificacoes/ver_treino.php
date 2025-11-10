<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h2>🏋️ Treino <?= htmlspecialchars($treino['tipo']) ?></h2>

    <p><strong>Status:</strong> <?= htmlspecialchars($treino['status']) ?></p>
    <p><strong>Data de Envio:</strong> <?= htmlspecialchars($treino['data_inicio']) ?></p>

    <table>
        <thead>
            <tr>
                <th>Exercício</th>
                <th>Séries</th>
                <th>Repetições</th>
                <th>Carga (kg)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($exercicios)): ?>
                <?php foreach ($exercicios as $ex): ?>
                    <tr>
                        <td><?= htmlspecialchars($ex['nome_exercicio'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($ex['series'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($ex['repeticoes'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($ex['carga'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum exercício cadastrado neste treino.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <form id="formIniciarTreino" action="/ACADEMY/public/treinos/iniciar" method="POST" style="display:inline;">
        <input type="hidden" name="id" value="<?= htmlspecialchars($treino['id']) ?>">
        <button type="submit" class="btn">🚀 Iniciar Treino</button>
    </form>


</div>
<script>
    document.getElementById('formIniciarTreino').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.status === 'sucesso') {
                // ✅ Mostra aviso de notificação removida
                alert(result.mensagem + "\n\n📬 A notificação foi removida automaticamente.");

                // Redireciona para a página de treinos em andamento
                window.location.href = result.redirect;
            } else {
                alert(result.mensagem || 'Erro ao iniciar treino.');
            }
        } catch (err) {
            console.error(err);
            alert('Erro inesperado ao iniciar o treino.');
        }
    });
</script>


<?php include __DIR__ . '/../templates/footer.php'; ?>