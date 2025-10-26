<form action="/ACADEMY/public/admin/editar_usuario/<?= htmlspecialchars($usuario['id']) ?>" method="POST">
    <label>Nome:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

    <label>Tipo:</label>
    <select name="tipo">
        <option value="Usuário" <?= $usuario['tipo'] == 'Usuário' ? 'selected' : '' ?>>Usuário</option>
        <option value="Instrutor" <?= $usuario['tipo'] == 'Instrutor' ? 'selected' : '' ?>>Instrutor</option>
        <option value="Admin" <?= $usuario['tipo'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <button type="submit">Salvar</button>
</form>