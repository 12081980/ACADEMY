<?php
include __DIR__ . '/../../Views/templates/header.php';
?>
<h2>Meu Perfil</h2>
<div class="perfil">


    <form id="formPerfil" method="post" action="/ACADEMY/public/usuario/atualizar">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($_SESSION['usuario']['nome'] ?? '') ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['usuario']['email'] ?? '') ?>" required>

        <label>Senha:</label>
        <input type="password" name="senha" placeholder="••••••">

        <div class="botoesPerfil">
            <button type="submit">Salvar Alterações</button>
            <button type="button" onclick="excluirPerfil()">Excluir Perfil</button>
        </div>
    </form>
</div>
<script>
    function excluirPerfil() {
        if (confirm("Tem certeza que deseja excluir seu perfil?")) {
            window.location.href = "/ACADEMY/public/usuario/excluir";
        }
    }
</script>

<?php
include __DIR__ . '/../../Views/templates/footer.php';
?>