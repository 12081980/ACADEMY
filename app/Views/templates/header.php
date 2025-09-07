 <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
   <base href="/ACADEMY/">
<link rel="stylesheet" href="public/css/style.css">


   
</head>
<body>  
 <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<header>
    <div class="container">
        <nav>
            <ul>
                <li><a href="/ACADEMY/public/home">Início</a></li>

                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><a href="/ACADEMY/public/treinos/realizados">📋 Treinos Realizados</a></li>
                    <li><a href="/ACADEMY/public/treinos/em_andamento">Treinos em Andamento</a></li>
                    <li><a href="/ACADEMY/public/treinos/graficos">📊 Ver gráfico de evolução</a></li>
                <?php endif; ?>

                <li>
                    <div class="botoes">
<?php if (isset($_SESSION['usuario'])): ?>
    <span>
        <span style="color: #fff; font-weight: bold;">
                                👋 Olá, <a href="/ACADEMY/public/usuario/perfil"><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></a>
    </span>
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
    <form id="formLogin" onsubmit="return validarLogin()">
      <label for="emailLogin">Email:</label>
      <input type="email" id="emailLogin" name="email" required>

      <label for="senhaLogin">Senha:</label>
      <input type="password" id="senhaLogin" name="senha" required>

      <button type="submit">Entrar</button>
    </form>
    <div id="loginErro" style="color: red; margin-top: 10px;"></div>
  </div>
</div>

<!-- Modal Cadastro -->
<div id="modalCadastro" class="modal">
  <div class="modal-content">
    <button class="close-btn" onclick="fecharModal('modalCadastro')">&times;</button>
    <h2>Cadastro</h2>
    <form id="formCadastro" onsubmit="return validarCadastro()">
      <label for="nomeCadastro">Nome:</label>
      <input type="text" id="nomeCadastro" name="nome" required>

      <label for="emailCadastro">Email:</label>
      <input type="email" id="emailCadastro" name="email" required>

      <label for="senhaCadastro">Senha:</label>
      <input type="password" id="senhaCadastro" name="senha" required>

      <button type="submit">Cadastrar</button>
    </form>
    <div id="cadastroErro" style="color: red; margin-top: 10px;"></div>
  </div>
</div>



