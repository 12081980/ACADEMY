<?php


if (session_status() === PHP_SESSION_NONE)
    session_start();
?>
<?php include __DIR__ . '/../templates/menuInstrutor.php'; ?>


<p>Bem-vindo, <strong><?= htmlspecialchars($instrutorNome ?? 'Instrutor') ?></strong>!</p>

<?php if (!empty($usuarios)): ?>
    <div class="container">
    <table>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Ação</th>
        </tr>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['nome']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td class="acao">
    <a class="btn btn-enviar" href="/ACADEMY/public/instrutor/enviar?usuario_id=<?= $u['id'] ?>">
        Enviar Treino
    </a>
</td>

            </tr>
        <?php endforeach; ?>
    </table>
    </div>
<?php else: ?>
    <p>Nenhum aluno cadastrado.</p>
<?php endif; ?>
<?php include __DIR__ . '/../templates/footerInstrutor.php'; ?>
</body>
<script>
function toggleMenu() {
    const menu = document.getElementById('menu');
    menu.classList.toggle('open');
}
</script>
</html>