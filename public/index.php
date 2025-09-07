<?php
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/Controllers/TreinosController.php';
$router = new Router();
$router->addRoute('/', 'HomeController@index');
$router->addRoute('/home', 'HomeController@index');
$router->addRoute('/iniciar/iniciar', 'IniciarController@iniciar');
$router->addRoute('/treinos/realizados', 'TreinosController@realizados');
$router->addRoute('/treinos/iniciar', 'TreinosController@iniciar');
$router->addRoute('/treinos/em-andamento', 'TreinosController@emAndamento');
$router->addRoute('/auth/login', 'AuthController@login');
$router->addRoute('/auth/cadastro', 'AuthController@cadastro');
$router->addRoute('/treinos/em_andamento', 'TreinosController@em_andamento');
$router->addRoute('/treinos/finalizar', 'TreinosController@finalizar', ['POST']);
$router->addRoute('/treinos/graficos', 'TreinosController@graficos');
$router->addRoute('/treino/em_andamento', 'TreinosController@emAndamento');
$router->addRoute('/em_andamento', 'TreinosController@emAndamento');
$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/auth/logout', 'AuthController@logout');
$router->addRoute('/auth/cadastro', 'AuthController@cadastro');
$router->addRoute('/admin/usuario', 'AdminController@lista_usuario');
$router->addRoute('/admin/usuario/{id}', 'AdminController@detalhesUsuario');
$router->addRoute('/admin/usuario', 'AdminController@listausuario');
$router->addRoute('/admin/ver_usuario/{id}', 'AdminController@verUsuario');
$router->addRoute('/admin/excluir_usuario/{id}', 'AdminController@excluirUsuario');
$router->addRoute('/admin/lista_usuario', 'AdminController@listaUsuario');
$router->addRoute('/admin/login', 'AdminController@login');
$router->addRoute('/admin/treinos', 'AdminController@treinos');
$router->addRoute('/login/autenticar', 'LoginController@autenticar');
$router->addRoute('/login/autenticar', 'LoginController@autenticar');
$router->addRoute('/admin/usuario', 'AdminController@listausuario');

$router->addRoute('/register', 'RegisterController@register');
$router->addRoute('/register', 'AuthController@register');
$router->addRoute('/admin/lista_usuario', 'AdminController@listaUsuario');

$router->addRoute('/usuario/perfil', 'UsuarioController@perfil');
$router->addRoute('/usuario/atualizar', 'UsuarioController@atualizar');
$router->addRoute('/usuario/excluirTreino/{id}', 'UsuarioController@excluirTreino');
$router->addRoute('/usuario/excluirPerfil', 'UsuarioController@excluirPerfil');



// $controller = new $controllerName($GLOBALS['conn']);



// Pega URL sem base path
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// REMOVE /ACADEMY/public
$basePath = '/ACADEMY/public';
if (strpos($url, $basePath) === 0) {
    $url = substr($url, strlen($basePath));
}

$router->dispatch($url);
