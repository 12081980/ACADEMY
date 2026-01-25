<?php include __DIR__ . '/../templates/menuAdmin.php'; ?>
<?php
$paginaAtual = $paginaAtual ?? 1;
$totalPaginas = !empty($totalPaginas) ? $totalPaginas : 1;
$treinos = !empty($treinos) ? $treinos : [];
?>
<?php if (!empty($_SESSION['msg_sucesso'])): ?>
    <div class="alert sucesso">
        <?= $_SESSION['msg_sucesso'] ?>
    </div>
    <?php unset($_SESSION['msg_sucesso']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['msg_erro'])): ?>
    <div class="alert erro">
        <?= $_SESSION['msg_erro'] ?>
    </div>
    <?php unset($_SESSION['msg_erro']); ?>
<?php endif; ?>

<div class="container">
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
                        <td><?= htmlspecialchars($user['tipo'] ?? '') ?></td>
                        <td class="actions">
                           <a href="/ACADEMY/public/admin/editarUsuario/<?= (int)$user['id'] ?>"
   class="button editar">
   Editar
</a>



                           <form action="/ACADEMY/public/admin/excluir_usuario" method="POST" 
      onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');"
      style="display:inline;">
      
    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
    
   <button class="button excluir" onclick="excluirUsuario(<?= (int)$user['id'] ?>)">
    Excluir
</button>


</form>

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
    <?php if ($totalPaginas > 1): ?>
        <div class="paginacao" style="margin-top:20px; text-align:center;">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i == $paginaAtual): ?>
                    <strong>[<?= $i ?>]</strong>
                <?php else: ?>
                    <a href="/ACADEMY/public/admin/lista_usuario?pagina=<?= $i ?>"
                        style="margin:0 5px; text-decoration:none; <?= $paginaAtual == $i ? 'font-weight: bold; color: red;' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

   <script>
function excluirUsuario(id) {
    if (!confirm('Tem certeza que deseja excluir este usuário?')) return;

    fetch(`/ACADEMY/public/admin/excluir_usuario/${id}`, {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        alert(data.mensagem);

        if (data.status === 'sucesso') {
            window.location.href = '/ACADEMY/public/admin/lista_usuario';
        }
    })
    .catch(() => alert('Erro inesperado.'));
}
</script>


     <?php include __DIR__ . '/../templates/footerAdmin.php'; ?>