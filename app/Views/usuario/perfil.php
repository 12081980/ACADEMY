<?php
include __DIR__ . '/../../Views/templates/header.php';
?>
<div class="perfil">
    <div class="perfil-grid">
        <form id="formPerfil" class="perfil-form">
            <div class="form-row">
                <label>Nome:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($_SESSION['usuario']['nome'] ?? '') ?>"
                    required>
            </div>

            <div class="form-row">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['usuario']['email'] ?? '') ?>"
                    required>
            </div>

            <div class="form-row">
                <label>Senha:</label>
                <input type="password" name="senha" placeholder="••••••">
            </div>

            <div class="form-row">
                <label>Telefone:</label>
                <input type="text" name="telefone"
                    value="<?= htmlspecialchars($_SESSION['usuario']['telefone'] ?? '') ?>">
            </div>


            <div class="form-row">
                <label>Cidade:</label>
                <input type="text" name="cidade" value="<?= htmlspecialchars($_SESSION['usuario']['cidade'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Estado:</label>
                <input type="text" name="estado" value="<?= htmlspecialchars($_SESSION['usuario']['estado'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Bairro:</label>
                <input type="text" name="bairro" value="<?= htmlspecialchars($_SESSION['usuario']['bairro'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Rua:</label>
                <input type="text" name="rua" value="<?= htmlspecialchars($_SESSION['usuario']['rua'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Número:</label>
                <input type="text" name="numero" value="<?= htmlspecialchars($_SESSION['usuario']['numero'] ?? '') ?>">
            </div>

            <div class="botoesPerfil">
                <button type="submit" class="btn btn-success">Salvar Alterações</button>
                <button type="button" id="btnExcluirPerfil" class="btn btn-danger">Excluir Perfil</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Atualizar perfil via fetch
    document.getElementById('formPerfil').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/ACADEMY/public/usuario/atualizar', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                alert(data.mensagem);
                if (data.status === 'sucesso') {
                    window.location.href = data.redirect; // redireciona após sucesso
                }
            })
            .catch(err => console.error(err));
    });

    // Excluir perfil via fetch
    document.getElementById('btnExcluirPerfil').addEventListener('click', function () {
        if (!confirm("Tem certeza que deseja excluir seu perfil?")) return;

        fetch('/ACADEMY/public/usuario/excluir-perfil', {
            method: 'POST'
        })
            .then(res => res.json())
            .then(data => {
                alert(data.mensagem);
                if (data.status === 'sucesso') {
                    window.location.href = data.redirect;
                }
            })
            .catch(err => console.error(err));
    });
</script>

<?php
include __DIR__ . '/../../Views/templates/footer.php';
?>