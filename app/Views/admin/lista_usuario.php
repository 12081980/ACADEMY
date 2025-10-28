<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>


<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['nome'] ?? '') ?></td>
                    <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($user['tipo'] ?? 'Usuário') ?></td>
                    <td class="actions">
                        <a href="/ACADEMY/public/admin/editar_usuario/<?= htmlspecialchars($user['id'] ?? '') ?>"
                            class="button editar">Editar</a>
                        <button class="button excluir"
                            onclick="excluirUsuario(<?= htmlspecialchars($user['id'] ?? '0') ?>)">Excluir</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center;">Nenhum usuário encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    function excluirUsuario(id) {
        if (!confirm('Tem certeza que deseja excluir este usuário?')) return;

        fetch(`/ACADEMY/public/admin/excluir_usuario/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        })
            .then(res => res.json())
            .then(data => {
                alert(data.mensagem);
                if (data.status === 'sucesso') {
                    location.reload(); // 🔄 Atualiza a página para refletir a exclusão
                }
            })
            .catch(() => alert('Erro ao excluir usuário.'));
    }

</script>

<?php include __DIR__ . '/../templates/footerAdmin.php'; ?>