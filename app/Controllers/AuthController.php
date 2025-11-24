<?php
require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';

class AuthController
{
    private $conn;

    public function __construct($conn = '')
    {
        if ($conn) {
            $this->conn = $conn;
        } else {
            require __DIR__ . '/../../core/conn.php';
            $this->conn = $conn ?? (isset($conn) ? $conn : '');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();

            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                $_SESSION['erro_login'] = "Preencha todos os campos!";
                header("Location: /ACADEMY/public");
                exit;
            }

            $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && (password_verify($senha, $usuario['senha']) || $senha === $usuario['senha'])) {

                // Atualiza senha antiga para hash, se necessário
                if ($senha === $usuario['senha']) {
                    $hash = password_hash($senha, PASSWORD_DEFAULT);
                    $up = $this->conn->prepare("UPDATE usuario SET senha = :hash WHERE id = :id");
                    $up->execute([':hash' => $hash, ':id' => $usuario['id']]);
                }

                // Define tipo padrão (caso não exista)
                $tipo = $usuario['tipo'] ?? 'aluno';

                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'tipo' => $tipo
                ];

                // Redirecionamento conforme tipo de usuário
                switch ($tipo) {
                    case 'admin':
                        header("Location: /ACADEMY/public/admin/dashboard");
                        break;
                    case 'instrutor':
                        header("Location: /ACADEMY/public/instrutor/dashboardInstrutor");
                        break;
                    default:
                        header("Location: /ACADEMY/public");
                        break;
                }
                exit;
            }

            // Falha no login
            $_SESSION['erro_login'] = "Email ou senha incorretos!";
            header("Location: /ACADEMY/public");
            exit;
        }

        // Se for GET, redireciona para página inicial
        header("Location: /ACADEMY/public");
        exit;
    }



    public function register()
    {
      

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $telefone = trim($_POST['telefone'] ?? '');           
            $cidade = trim($_POST['cidade'] ?? '');
            $estado = trim($_POST['estado'] ?? '');
            $bairro = trim($_POST['bairro'] ?? '');
            $rua = trim($_POST['rua'] ?? '');
            $numero = trim($_POST['numero'] ?? '');
            $tipo = $_POST['tipo'] ?? '';

            if (!$nome || !$email || !$senha) {
                $_SESSION['erro_cadastro'] = "Preencha todos os campos obrigatórios!";
                header("Location: /ACADEMY/public/home");
                exit;
            }

            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("
            INSERT INTO usuario 
            (nome, email, senha, telefone, cidade, estado, bairro, rua, numero, tipo)
            VALUES (:nome, :email, :senha, :telefone,  :cidade, :estado, :bairro, :rua, :numero, :tipo)
        ");

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $hash);
            $stmt->bindParam(':telefone', $telefone);          
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':bairro', $bairro);
            $stmt->bindParam(':rua', $rua);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':tipo', $tipo);

            if ($stmt->execute()) {
                $_SESSION['usuario'] = [
                    'id' => $this->conn->lastInsertId(),
                    'nome' => $nome,
                    'email' => $email,
                    'telefone' => $telefone,                 
                    'cidade' => $cidade,
                    'estado' => $estado,
                    'bairro' => $bairro,
                    'rua' => $rua,
                    'numero' => $numero,
                    'tipo' => $tipo
                ];
                header("Location: /ACADEMY/public/home");
                exit;
            } else {
                $_SESSION['erro_cadastro'] = "Erro ao cadastrar usuário.";
                header("Location: /ACADEMY/public/home");
                exit;
            }
        }

        header("Location: /ACADEMY/public/home");
        exit;
    }



    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /ACADEMY/public/home");
        exit;
    }
}

