<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <base href="/ACADEMY/public">

    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <div class="container">
        <nav>
            <ul>
                <li><a href="/ACADEMY/public/home">Início</a></li>

                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><a href="/ACADEMY/public/treinos/realizados">📋 TREINOS REALIZADOS</a></li>
                    <li><a href="/ACADEMY/public/treinos/em_andamento">📊 TREINOS EM ANDAMENTO</a></li>
                    <li><a href="/ACADEMY/public/treinos/graficos">📊 GRÁFICO DE EVOLUÇÃO</a></li>
                <?php endif; ?>
<?php
// Inicia a sessão caso não tenha sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
$usuarioLogado = isset($_SESSION['usuario']['id']);

// Conta notificações não lidas
$novas = 0;
if ($usuarioLogado && !empty($notificacoes)) {
    $novas = count(array_filter($notificacoes, fn($n) => empty($n['lida']) || $n['lida'] == 0));
}

// Exibe o link apenas se houver notificações
if ($usuarioLogado && $novas > 0):
?>
<li>
    <a href="/ACADEMY/public/notificacoes" class="menu-notificacao">
        🔔 Notificações <strong>(<?= $novas ?> novas)</strong>
    </a>
</li>
<?php endif; ?>



  



                <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'usuario'): ?>
                    <li><a href="/ACADEMY/public/treinos/recebidos">Treinos Recebidos</a></li>
                <?php endif; ?>

                <li>
                    <div class="botoes">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <span style="color: #fff; font-weight: bold;">
                                👋 Olá, <a href="/ACADEMY/public/usuario/perfil"><?= htmlspecialchars($_SESSION['usuario']['nome'] ?? '') ?></a>
                            </span>
                            <form action="/ACADEMY/public/auth/logout" method="post" style="display: inline;">
                                <button type="submit">Sair</button>
                            </form>
                        <?php else: ?>
                            <button onclick="abrirModal('modalLogin')">Login</button>
                            <button onclick="abrirModal('modalCadastro')">Cadastro</button>
                        <?php endif; ?>
                    </div>
                </li>
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
            <?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?>
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
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone">

          <label for="cidade">Cidade:</label>
<input type="text" id="cidade" name="cidade" required>

<label for="estado">Estado:</label>
<input type="text" id="estado" name="estado" required>

<label for="bairro">Bairro:</label>
<input type="text" id="bairro" name="bairro" required>

<label for="rua">Rua:</label>
<input type="text" id="rua" name="rua" required>

<label for="numero">Número:</label>
<input type="text" id="numero" name="numero" required>


            <input type="hidden" name="tipo" value="aluno">
            <button type="submit">Cadastrar</button>
        </form>

        <?php if (!empty($_SESSION['erro_cadastro'])): ?>
          <div style="color:red; margin-top:10px;">
            <?= $_SESSION['erro_cadastro']; unset($_SESSION['erro_cadastro']); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

<script>
  // Abrir/fechar modais
  function abrirModal(id) { document.getElementById(id)?.classList.add('open'); }
  function fecharModal(id) { document.getElementById(id)?.classList.remove('open'); }
</script>

