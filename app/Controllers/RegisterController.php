<?php
require_once __DIR__ . '/../../config/conn.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';

class RegisterController
{
    private $usuarioModel;

    public function __construct()
    {
        session_start();
        if (!isset($GLOBALS['conn']) || !$GLOBALS['conn']) {
            die(json_encode([
                'status' => 'erro',
                'mensagem' => 'Erro ao conectar com o servidor.'
            ]));
        }
        $this->usuarioModel = new UsuarioModel($GLOBALS['conn']);
        header('Content-Type: application/json');
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

            $this->usuarioModel->criar([
                'nome' => $nome,
                'email' => $email,
                'senha' => $hash,
                'tipo' => 'usuario'
            ]);

            echo json_encode([
                "status" => "sucesso",
                "mensagem" => "Cadastro realizado com sucesso!",
                "redirect" => "/ACADEMY/public/login"
            ]);
        } catch (Throwable $e) {
            echo json_encode([
                "status" => "erro",
                "mensagem" => "Erro no servidor: " . $e->getMessage()
            ]);
        }
    }
}