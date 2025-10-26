<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>

<div class="container">
    <h2>✏️ Editar Treino</h2>

    <?php if (!empty($treino)): ?>
        <form method="POST" action="/ACADEMY/public/instrutor/atualizar_treino">
            <input type="hidden" name="id" value="<?= htmlspecialchars($treino['id']) ?>">

            <label for="tipo">Tipo do Treino:</label>
            <select name="tipo" id="tipo" required>
                <option value="A" <?= $treino['tipo'] === 'A' ? 'selected' : '' ?>>Treino A</option>
                <option value="B" <?= $treino['tipo'] === 'B' ? 'selected' : '' ?>>Treino B</option>
                <option value="C" <?= $treino['tipo'] === 'C' ? 'selected' : '' ?>>Treino C</option>
                <option value="D" <?= $treino['tipo'] === 'D' ? 'selected' : '' ?>>Treino D</option>
            </select>

            <br><br>
            <button type="submit" class="btn">Salvar Alterações</button>
        </form>
    <?php else: ?>
        <p>Treino não encontrado.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../templates/footerInstrutor.php'; ?>