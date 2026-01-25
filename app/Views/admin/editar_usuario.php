<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>



<form action="/ACADEMY/public/admin/atualizar_usuario/<?= (int)$usuario['id'] ?>"
      method="POST">
  

    <label>Nome:</label>
    <input type="text" name="nome"
           value="<?= htmlspecialchars($usuario['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
           required>

    <label>Email:</label>
    <input type="email" name="email"
           value="<?= htmlspecialchars($usuario['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
           required>

    <label>Tipo:</label>
    <select name="tipo">
        <option value="usuario" <?= ($usuario['tipo'] ?? '') === 'usuario' ? 'selected' : '' ?>>Usuário</option>
        <option value="instrutor" <?= ($usuario['tipo'] ?? '') === 'instrutor' ? 'selected' : '' ?>>Instrutor</option>
        <option value="admin" <?= ($usuario['tipo'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <button type="submit">Salvar</button>
</form>

<?php include __DIR__ . '/../templates/footerAdmin.php'; ?>