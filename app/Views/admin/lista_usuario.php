<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>
<?php
$paginaAtual = $paginaAtual ?? 1;
$totalPaginas = !empty($totalPaginas) ? $totalPaginas : 1;
$treinos = !empty($treinos) ? $treinos : [];
?>
<style>
/* ====== TABELAS PADRÃO PAINEL ADMIN ====== */
table {
    width: 100%;
    border-collapse: collapse;
    /* margin-top: 10px; */
    background: #fff;
    border-radius: 6px;
    overflow: hidden;
    font-size: 14px;
}

thead {
    background: #0a3d62;
    color: #fff;
    text-transform: uppercase;
    font-size: 10px;
}

thead th {
    padding: 12px 10px;
    text-align: left;
    letter-spacing: 0.5px;
}

tbody tr {
    border-bottom: 1px solid #ddd;
}

tbody tr:nth-child(even) {
    background: #f5f7fa;
}

tbody td {
    /* padding: 10px 12px; */
    color: #333;
}

tbody tr:hover {
    background: #e8f0ff;
    transition: .2s ease;
}

/* ====== BOTÕES DA TABELA ====== */
.actions {
    /* display: flex;  */
     gap: 2px;
}

.button {
    padding: 3px 5px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 10px;
    cursor: pointer;
    border: none;
    color: #fff;
    transition: 0.2s ease;
    font-weight: bold;
}

.button.editar {
    background: #007bff;
}

.button.editar:hover {
    background: #0056b3;
}

.button.excluir {
    background: #e63946;
}

.button.excluir:hover {
    background: #b71c1c;
}

/* ====== PAGINAÇÃO ====== */
.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination a {
    padding: 8px 14px;
    background: #eee;
    margin: 3px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    color: #333;
    transition: 0.2s;
}

.pagination a:hover {
    background: #007bff;
    color: #fff;
}

.pagination a.active {
    background: #0a3d62;
    color: white;
}
</style>
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
                        <a href="/ACADEMY/public/admin/editar_usuario/<?= htmlspecialchars($user['id'] ?? '') ?>" class="button editar">Editar</a>
                        <button class="button excluir" onclick="excluirUsuario(<?= htmlspecialchars($user['id'] ?? '0') ?>)">Excluir</button>
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

<!-- PAGINAÇÃO AQUI 👇 -->
<div class="pagination">
    <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="?pagina=<?= $i ?>" class="<?= ($i == $paginaAtual) ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>

<style>
.pagination { margin-top:20px;text-align:center;}
.pagination a {
    padding: 8px 12px; background:#eee; margin:3px;
    border-radius:5px; text-decoration:none; font-weight:bold;
}
.pagination a.active { background:#007bff; color:white; }
</style>

<script>
    function excluirUsuario(id) {
        if (!confirm('Tem certeza que deseja excluir este usuário?')) return;

        fetch(`/ACADEMY/public/admin/excluir_usuario/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.mensagem);
            if (data.status === 'sucesso') {
                location.reload();
            }
        })
        .catch(() => alert('Erro ao excluir usuário.'));
    }
</script>

<?php include __DIR__ . '/../templates/footerAdmin.php'; ?>
