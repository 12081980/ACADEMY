<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h2>Treino em Andamento</h2>

    <?php if (isset($treino) && $treino): ?>
        <div class="card">
            <h3><?= htmlspecialchars($treino['nome']); ?></h3>
            <p><strong>Iniciado em:</strong> <?= date('d/m/Y H:i', strtotime($treino['data_inicio'])); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($treino['status']); ?></p>

            <button id="finalizarTreino" class="btn btn-danger">Finalizar Treino</button>
        </div>
    <?php else: ?>
        <p>Nenhum treino em andamento.</p>
    <?php endif; ?>
</div>

<script>
    document.getElementById('finalizarTreino')?.addEventListener('click', () => {
        if (confirm('Tem certeza que deseja finalizar este treino?')) {
            fetch('/ACADEMY/public/treinos/finalizar', {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'sucesso') {
                        alert(data.mensagem);
                        window.location.href = data.redirect; // ✅ Redireciona corretamente
                    } else {
                        alert(data.mensagem);
                    }
                })
                .catch(error => {
                    console.error('Erro ao finalizar treino:', error);
                    alert('Erro ao finalizar o treino.');
                });
        }
    });
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>