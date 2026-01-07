<?php
include __DIR__ . '/../../Views/templates/header.php';
?>
<?php if (!empty($_SESSION['mensagem'])): ?>
    <div class="alert alert-<?= $_SESSION['tipo_mensagem'] ?? 'info' ?>">
        <?= $_SESSION['mensagem'] ?>
    </div>

    <?php
        unset($_SESSION['mensagem']);
        unset($_SESSION['tipo_mensagem']);
    ?>
<?php endif; ?>


<div class="perfil">
    <div class="perfil-grid">
        <form id="formPerfil" class="perfil-form">

            <div class="form-row">
                <label>Nome:</label>
                <input type="text" name="nome" class="upper" 
                    value="<?= htmlspecialchars($_SESSION['usuario']['nome'] ?? '') ?>" required>
            </div>

            <div class="form-row">
                <label>Email:</label>
                <input type="email" name="email" 
                    value="<?= htmlspecialchars($_SESSION['usuario']['email'] ?? '') ?>" required>
            </div>

            <div class="form-row">
                <label>Senha:</label>
                <input type="password" name="senha" placeholder="••••••">
            </div>

            <div class="form-row">
                <label>Telefone:</label>
                <input type="text" name="telefone" id="telefone"
                    value="<?= htmlspecialchars($_SESSION['usuario']['telefone'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Cidade:</label>
                <input type="text" name="cidade" class="upper" 
                    value="<?= htmlspecialchars($_SESSION['usuario']['cidade'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Estado:</label>
                <select name="estado" required>
                    <option value="">Selecione</option>
                    <?php 
                        $estados = ["AC","AL","AP","AM","BA","CE","DF","ES","GO","MA","MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN","RS","RO","RR","SC","SP","SE","TO"];
                        $atual = $_SESSION['usuario']['estado'] ?? '';
                        foreach ($estados as $uf) {
                            $sel = ($uf === $atual) ? "selected" : "";
                            echo "<option value='$uf' $sel>$uf</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-row">
                <label>Bairro:</label>
                <input type="text" name="bairro" class="upper"
                    value="<?= htmlspecialchars($_SESSION['usuario']['bairro'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Rua:</label>
                <input type="text" name="rua" class="upper"
                    value="<?= htmlspecialchars($_SESSION['usuario']['rua'] ?? '') ?>">
            </div>

            <div class="form-row">
                <label>Número:</label>
                <input type="text" name="numero"
                    value="<?= htmlspecialchars($_SESSION['usuario']['numero'] ?? '') ?>">
            </div>

           <div class="botoesPerfil">

  <button
    type="button"
    class="btn btn-info"
    onclick="window.location.href='/ACADEMY/public/usuario/avaliacaoVer/<?=$_SESSION['usuario']['id']?>'">
    Minhas Avaliações
</button>


    <button type="submit" class="btn btn-success">
        Salvar Alterações
    </button>

    

    <button type="button" id="btnExcluirPerfil" class="btn btn-danger">
        Excluir Perfil
    </button>
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
<script>
/* 🔠 Campos maiúsculos */
document.querySelectorAll(".upper").forEach(campo => {
    campo.addEventListener("input", () => {
        campo.value = campo.value.toUpperCase();
    });
});

/* 📞 Máscara de telefone */
const tel = document.getElementById("telefone");
tel.addEventListener("input", () => {
    let v = tel.value.replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
    v = v.replace(/(\d{5})(\d)/, "$1-$2");
    tel.value = v;
});
</script>


<?php
include __DIR__ . '/../../Views/templates/footer.php';
?>