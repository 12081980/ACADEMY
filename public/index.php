<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Sao_Paulo');

// ====== AUTOLOAD DAS DEPENDÊNCIAS BÁSICAS ======
require_once __DIR__ . '/../core/conn.php';
require_once __DIR__ . '/../core/Router.php';

// ====== INSTÂNCIA DO ROUTER ======
$router = new Router($conn);

// ==================================================
// ✅ ROTAS PRINCIPAIS DO SISTEMA
// ==================================================
$router->addRoute('/', 'HomeController@index');
$router->addRoute('/home', 'HomeController@index');

// ==================================================
// ✅ AUTENTICAÇÃO
// ==================================================
$router->addRoute('/login', 'AuthController@login', ['GET', 'POST']);
$router->addRoute('/auth/login', 'AuthController@login', ['GET', 'POST']);
$router->addRoute('/auth/register', 'AuthController@register', ['GET']);
$router->addRoute('/auth/cadastro', 'AuthController@cadastro', ['GET', 'POST']);
$router->addRoute('/auth/logout', 'AuthController@logout', ['GET', 'POST']);
// ==================================================
// ✅ NOTIFICAÇÕES
$router->addRoute('/notificacoes', 'NotificacoesController@index');
$router->addRoute('/instrutor/treinos_enviados', 'InstrutorController@treinos_enviados');
$router->addRoute('/instrutor/editar_treino', 'InstrutorController@editar_treino');
$router->addRoute('/instrutor/atualizar_treino', 'InstrutorController@atualizar_treino');
$router->addRoute('/instrutor/excluir_treino', 'InstrutorController@excluir_treino');
$router->addRoute('/instrutor/treinos_enviados', 'InstrutorController@treinos_enviados');
$router->addRoute('/instrutor/editar_treino', 'InstrutorController@editar_treino');
$router->addRoute('/instrutor/atualizar_treino', 'InstrutorController@atualizar_treino');
$router->addRoute('/instrutor/excluir_treino', 'InstrutorController@excluir_treino');

$router->addRoute('/notificacoes/ver', 'NotificacoesController@ver');

// ==================================================
// ✅ TREINOS
// ==================================================
$router->addRoute('/treinos/iniciar', 'TreinosController@iniciar', ['POST']);
$router->addRoute('/treinos/em_andamento', 'TreinosController@em_andamento', ['GET']);
$router->addRoute('/treinos/finalizar', 'TreinosController@finalizar', ['POST']);
$router->addRoute('/treinos/realizados', 'TreinosController@realizados', ['GET']);
$router->addRoute('/treinos/graficos', 'TreinosController@graficos', ['GET']);

// Alias para compatibilidade
$router->addRoute('/treinos/em_andamento', 'TreinosController@em_andamento', ['GET', 'POST']);

// Exibir o formulário de edição (GET)
$router->addRoute('/admin/editar_usuario/{id}', 'AdminController@editarUsuario', 'GET');

// Salvar alterações (POST)
$router->addRoute('/admin/editar_usuario/{id}', 'AdminController@atualizarUsuario', 'POST');
$router->addRoute('/admin/editar_usuario/{id}', 'AdminController@editarUsuario');
$router->addRoute('/admin/editar_usuario/{id}', 'AdminController@atualizarUsuario');
$router->addRoute('/instrutor/avaliacoes', 'InstrutorController@listarUsuariosParaAvaliacao');
$router->addRoute('/instrutor/avaliacaoEscolher', 'InstrutorController@avaliacaoEscolher');

$router->addRoute('/instrutor/avaliacaoFicha', 'InstrutorController@avaliacaoFicha');
$router->addRoute('/instrutor/salvarAvaliacao', 'InstrutorController@salvarAvaliacao');







// ==================================================
// ✅ ADMINISTRAÇÃO
// ==================================================
$router->addRoute('/admin/login', 'AdminController@login', ['GET', 'POST']);
$router->addRoute('/admin/treinos', 'AdminController@treinos', ['GET']);
$router->addRoute(
    '/admin/usuario',
    'AdminController@listaUsuario',
    ['GET']
);
$router->addRoute('/admin/lista_usuario', 'AdminController@listaUsuarios', ['GET']);
$router->addRoute('/admin/dashboard', 'AdminController@dashboard', ['GET']);

$router->addRoute('/admin/ver_usuario/{id}', 'AdminController@verUsuario', ['GET']);
$router->addRoute('/admin/excluir_usuario/{id}', 'AdminController@excluirUsuario', ['POST']);

// ==================================================
// ✅ USUÁRIO
// ==================================================
$router->addRoute('/usuario/perfil', 'UsuarioController@perfil', ['GET']);
$router->addRoute('/usuario/atualizar', 'UsuarioController@atualizar', ['POST']);
$router->addRoute('/usuario/excluir-perfil', 'UsuarioController@excluirPerfil', ['POST']);
$router->addRoute('/usuario/excluirTreino/{id}', 'UsuarioController@excluirTreino', ['POST']);
$router->addRoute('/usuario/excluir', 'UsuarioController@excluir', ['POST']);
$router->addRoute('/usuario/lista', 'UsuarioController@lista', ['GET']);
$router->addRoute('/usuario/editar', 'UsuarioController@editar', ['GET', 'POST']);
$router->addRoute('/usuario/home', 'UsuarioController@home', ['GET']);
$router->addRoute('/usuario/alterarSenha', 'UsuarioController@alterarSenha', ['POST']);

// ✅ Rotas do Instrutor (corrigidas)
$router->addRoute('/instrutor/dashboardInstrutor', 'InstrutorController@dashboard', ['GET']);
$router->addRoute('/instrutor/enviar', 'InstrutorController@enviarTreinoForm', ['GET']);
$router->addRoute('/instrutor/enviar_treino', 'InstrutorController@enviarTreino', ['POST']);
$router->addRoute('/treinos/recebidos', 'TreinoController@recebidos', ['GET']);

$router->addRoute('/instrutor/enviar_treino', 'InstrutorController@enviarTreinoForm', ['GET']);
$router->addRoute('/instrutor/enviar_treino', 'InstrutorController@enviarTreino', ['POST']);

// ==================================================
// ✅ REGISTRO
// ==================================================
$router->addRoute('/register', 'AuthController@register', ['GET', 'POST']);
// pseudo-exemplo: ajuste conforme seu Router
$router->addRoute('/instrutor/enviar', 'InstrutorController@enviarTreinoForm', ['GET']);
$router->addRoute('/instrutor/enviar_treino', 'InstrutorController@enviarTreino', ['POST']);

// ==================================================
// ✅ ROTA FINAL: DESPACHA A REQUISIÇÃO
// ==================================================
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove o prefixo "/ACADEMY/public" das URLs
$basePath = '/ACADEMY/public';
if (strpos($url, $basePath) === 0) {
    $url = substr($url, strlen($basePath));
}
// Detecta automaticamente o caminho base
// $basePath = '/'; // no servidor remoto, raiz do domínio
// if (strpos($url, $basePath) === 0) {
//     $url = substr($url, strlen($basePath) - 1);
// }


// Executa a rota correspondente
$router->dispatch($url);
