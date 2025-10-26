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
                <label>Data de Nascimento:</label>
                <input type="date" name="data_nascimento" value="<?= $_SESSION['usuario']['data_nascimento'] ?? '' ?>">
            </div>

            <div class="form-row">
                <label>Endereço:</label>
                <input type="text" name="endereco"
                    value="<?= htmlspecialchars($_SESSION['usuario']['endereco'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Plano:</label>
                <select name="plano">
                    <option value="">Selecione</option>
                    <option value="mensal" <?= (($_SESSION['usuario']['plano'] ?? '') === 'mensal') ? 'selected' : '' ?>>
                        Mensal</option>
                    <option value="trimestral" <?= (($_SESSION['usuario']['plano'] ?? '') === 'trimestral') ? 'selected' : '' ?>>Trimestral</option>
                    <option value="anual" <?= (($_SESSION['usuario']['plano'] ?? '') === 'anual') ? 'selected' : '' ?>>
                        Anual</option>
                </select>
            </div>

            <div class="form-row">
                <label>Objetivo:</label>
                <input type="text" name="objetivo"
                    value="<?= htmlspecialchars($_SESSION['usuario']['objetivo'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Gênero:</label>
                <select name="genero">
                    <option value="">Selecione</option>
                    <option value="masculino" <?= (($_SESSION['usuario']['genero'] ?? '') === 'masculino') ? 'selected' : '' ?>>Masculino</option>
                    <option value="feminino" <?= (($_SESSION['usuario']['genero'] ?? '') === 'feminino') ? 'selected' : '' ?>>Feminino</option>
                    <option value="outro" <?= (($_SESSION['usuario']['genero'] ?? '') === 'outro') ? 'selected' : '' ?>>
                        Outro</option>
                </select>
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