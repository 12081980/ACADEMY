if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// Função para verificar se o usuário está logado
function verificarLogin()
{
if (!isset($_SESSION['usuario'])) {
header('Location: /ACADEMY/public/login');
exit;
}
}

// Função para verificar se é admin
function verificarAdmin()
{
verificarLogin();
if ($_SESSION['usuario']['tipo'] !== 'admin') {
header('Location: /ACADEMY/public/home');
exit;
}
}

// Função para verificar se é usuário comum
function verificarUsuario()
{
verificarLogin();
if ($_SESSION['usuario']['tipo'] !== 'usuario') {
header('Location: /ACADEMY/public/home');
exit;
}
}