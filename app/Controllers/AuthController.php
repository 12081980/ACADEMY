<?php
require_once __DIR__ . '/../../config/conn.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';

class AuthController
{
    private $usuarioModel;

    public function __construct()
    {
        session_start();

        if (!isset($GLOBALS['conn']) || !$GLOBALS['conn']) {
            die(json_encode([
                'status' => 'erro',
                'mensagem' => 'Erro: conexão com o banco de dados não foi inicializada.'
            ]));
        }

        $this->usuarioModel = new UsuarioModel($GLOBALS['conn']);
    }

    public function login()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email'] ?? '');
                $senha = trim($_POST['senha'] ?? '');

                if (empty($email) || empty($senha)) {
                    echo json_encode([
                        'status' => 'erro',
                        'mensagem' => 'Preencha todos os campos.'
                    ]);
                    return;
                }

                $usuario = $this->usuarioModel->buscarPorEmail($email);

                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    $_SESSION['usuario'] = [
                        'id' => $usuario['id'],
                        'nome' => $usuario['nome'],
                        'tipo' => $usuario['tipo']
                    ];

                    echo json_encode([
                        'status' => 'sucesso',
                        'mensagem' => 'Login realizado com sucesso!',
                        'redirect' => $usuario['tipo'] === 'admin'
                            ? '/ACADEMY/public/admin/lista_usuario'
                            : '/ACADEMY/public/home'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'erro',
                        'mensagem' => 'Email ou senha incorretos!'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'erro',
                    'mensagem' => 'Método inválido.'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'erro',
                'mensagem' => 'Erro no servidor: ' . $e->getMessage()
            ]);
        }
    }

    public function register()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode([
                    "status" => "erro",
                    "mensagem" => "Método inválido!"
                ]);
                return;
            }

            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');

            if (empty($nome) || empty($email) || empty($senha)) {
                echo json_encode([
                    "status" => "erro",
                    "mensagem" => "Preencha todos os campos!"
                ]);
                return;
            }

            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $this->usuarioModel->criar(nome: $nome, email: $email, senha: $hash, tipo: 'usuario');

            echo json_encode([
                "status" => "sucesso",
                "mensagem" => "Cadastro realizado com sucesso!",
                "redirect" => "/ACADEMY/public/home"
            ]);
        } catch (Throwable $e) {
            echo json_encode([
                "status" => "erro",
                "mensagem" => "Erro no servidor: " . $e->getMessage()
            ]);
        }
    }




    public function logout()
    {
        session_destroy();
        header("Location: /ACADEMY/public/home");
        exit;
    }
}
