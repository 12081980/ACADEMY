<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <base href="/ACADEMY/public">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="/ACADEMY/public/css/style.css"> 

</head>

<body>
    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$notificacoes = [];
$novas = 0;

if (isset($_SESSION['usuario']['id'])) {
    require_once __DIR__ . '/../../Models/NotificacaoModel.php';

    $notificacaoModel = new NotificacaoModel($GLOBALS['conn']);

    $notificacoes = $notificacaoModel->listarPorUsuario($_SESSION['usuario']['id']);
    $novas = $notificacaoModel->listarNaoLidas($_SESSION['usuario']['id']);
}
?>

<?php
$mostrarVoltar = false; // evita warning
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$novas = 0;

if (isset($_SESSION['usuario']['id'])) {
    require_once __DIR__ . '/../../Models/NotificacaoModel.php';

    $notificacaoModel = new NotificacaoModel($GLOBALS['conn']);
    $novas = $notificacaoModel->listarNaoLidas($_SESSION['usuario']['id']);
}
?>
 <header class="site-header">
    <nav class="container">
            <button class="hamburger" onclick="toggleMenu()" aria-label="Menu">☰</button>

            <ul id="menu">
                <li><a href="/ACADEMY/public/home">INÍCIO</a></li>

                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><a href="/ACADEMY/public/treinos/realizados"> TREINOS REALIZADOS</a></li>
                    <li><a href="/ACADEMY/public/treinos/em_andamento"> TREINOS EM ANDAMENTO</a></li>
                    <li><a href="/ACADEMY/public/treinos/graficos"> GRÁFICO DE EVOLUÇÃO</a></li>
<?php if ($novas > 0): ?>
    <li>
        <a href="/ACADEMY/public/notificacoes" class="menu-notificacao">
            🔔 Notificações <strong>(<?= $novas ?>)</strong>
        </a>
    </li>
<?php endif; ?>


                    <li class="menu-perfil">
                        <a href="/ACADEMY/public/usuario/perfil">
                            👋 <?= htmlspecialchars(explode(' ', trim($_SESSION['usuario']['nome']))[0]) ?> ⚙️
                        </a>
                    </li>

                    <li>
                        <form action="/ACADEMY/public/auth/logout" method="post">
                            <button type="submit" class="menu-link btn-logout">Sair</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li>
                        <button onclick="abrirModal('modalLogin')" class="menu-link">LOGIN</button>
                    </li>
                    <li>
                        <button onclick="abrirModal('modalCadastro')" class="menu-link">CADASTRO</button>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
    <main>
        <div class="containerBody">
            <!-- Modal Login -->
            <div id="modalLogin" class="modal">
                <div class="modal-content">
                    <button class="close-btn" onclick="fecharModal('modalLogin')">&times;</button>
                    <h2>Login</h2>
                    <form id="formLogin" method="POST" action="/ACADEMY/public/auth/login">
                        <label for="emailLogin">Email:</label>
                        <input type="email" id="emailLogin" name="email" required>

                        <label for="senhaLogin">Senha:</label>
                        <input type="password" id="senhaLogin" name="senha" required>

                        <button type="submit">Entrar</button>
                    </form>

                    <?php if (!empty($_SESSION['erro_login'])): ?>
                        <div style="color:red; margin-top:10px;">
                            <?= $_SESSION['erro_login'];
                            unset($_SESSION['erro_login']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal Cadastro -->
            <div id="modalCadastro" class="modal">
                <div class="modal-content">
                    <span class="fechar" onclick="fecharModal('modalCadastro')">&times;</span>
                    <form id="formCadastro" method="POST" action="/ACADEMY/public/register">

                        <label for="nome">Nome:</label>
                        <!-- <input type="text" id="nome" name="nome" <?= htmlspecialchars(ucfirst(strtolower(explode(' ', trim($_SESSION['usuario']['nome']))[0]))) ?>
 style="text-transform: uppercase;" required> -->
                        <input type="text" id="nome" name="nome" value="<?= trim($_SESSION['usuario']['nome'] ?? '') ?>" style="text-transform: uppercase;" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>

                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000">

                        <!-- <label for="cep">CEP:</label>
    <input type="text" id="cep" name="cep" maxlength="9"> -->

                        <label for="cidade">Cidade:</label>
                        <input type="text" id="cidade" name="cidade" style="text-transform: uppercase;" required>

                        <label for="estado">Estado:</label>
                        <select id="estado" name="estado" required>
                            <option value="">Selecione</option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MG">MG</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RO">RO</option>
                            <option value="RS">RS</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SE">SE</option>
                            <option value="SP">SP</option>
                            <option value="TO">TO</option>
                        </select>

                        <label for="bairro">Bairro:</label>
                        <input type="text" id="bairro" name="bairro" style="text-transform: uppercase;" required>

                        <label for="rua">Rua:</label>
                        <input type="text" id="rua" name="rua" style="text-transform: uppercase;" required>

                        <label for="numero">Número:</label>
                        <input type="text" id="numero" name="numero" required>

                        <input type="hidden" name="tipo" value="aluno">
                        <button type="submit">Cadastrar</button>
                    </form>


                    <?php if (!empty($_SESSION['erro_cadastro'])): ?>
                        <div style="color:red; margin-top:10px;">
                            <?= $_SESSION['erro_cadastro'];
                            unset($_SESSION['erro_cadastro']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <script>
                // Abrir/fechar modais
                function abrirModal(id) {
                    document.getElementById(id)?.classList.add('open');
                }

                function fecharModal(id) {
                    document.getElementById(id)?.classList.remove('open');
                }
            </script>

            <script>
                function toggleMenu() {
                    document.getElementById('menu').classList.toggle('open');
                }
            </script>