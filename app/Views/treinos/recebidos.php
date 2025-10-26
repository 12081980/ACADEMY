<h1>Meus Treinos Recebidos</h1>

<p>Bem-vindo, <strong><?= $_SESSION['usuario']['nome'] ?></strong></p>

<?php if (empty($treinos)): ?>
    <p>Você ainda não recebeu nenhum treino.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Data de Envio</th>
        </tr>
        <?php foreach ($treinos as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['titulo']) ?></td>
                <td><?= nl2br(htmlspecialchars($t['descricao'])) ?></td>
                <td><?= date('d/m/Y', strtotime($t['data_criacao'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<br>
<a href="/ACADEMY/public/home">Voltar</a>