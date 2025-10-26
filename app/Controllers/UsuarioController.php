<?php
require_once __DIR__ . '/../Models/UsuarioModel.php';

class UsuarioController
{
    private $usuarioModel;
    private $conn; // ✅ DECLARE AQUI

    public function __construct($conn)
    {
        $this->conn = $conn; // ✅ define a conexão
        $this->usuarioModel = new UsuarioModel($conn);
    }

    public function perfil()
    {
        session_start();
        if (!isset($_SESSION['usuario']['id'])) {
            header("Location: /login");
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $usuario = $this->usuarioModel->getById($usuarioId);
        require_once __DIR__ . '/../Views/usuario/perfil.php';
    }

    public function excluir($id)
    {
        if ($this->usuarioModel->delete($id)) {
            $_SESSION['mensagem'] = "Usuário excluído com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao excluir usuário!";
        }
        header("Location: /ACADEMY/public/admin/lista_usuario");
        exit;
    }

    public function atualizar()
    {
        session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido.']);
            exit;
        }

        if (!isset($_SESSION['usuario']['id'])) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $telefone = trim($_POST['telefone'] ?? null);
        $data_nascimento = $_POST['data_nascimento'] ?? null;
        $endereco = trim($_POST['endereco'] ?? null);
        $plano = $_POST['plano'] ?? null;
        $objetivo = trim($_POST['objetivo'] ?? null);
        $genero = $_POST['genero'] ?? null;

        if (!$nome || !$email) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha os campos obrigatórios!']);
            exit;
        }

        $query = "UPDATE usuario SET 
            nome = :nome, 
            email = :email, 
            telefone = :telefone, 
            data_nascimento = :data_nascimento, 
            endereco = :endereco, 
            plano = :plano, 
            objetivo = :objetivo, 
            genero = :genero";

        $params = [
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':data_nascimento' => $data_nascimento,
            ':endereco' => $endereco,
            ':plano' => $plano,
            ':objetivo' => $objetivo,
            ':genero' => $genero,
            ':id' => $usuarioId
        ];

        if (!empty($senha)) {
            $query .= ", senha = :senha";
            $params[':senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }

        $query .= " WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute($params)) {
                $_SESSION['usuario'] = array_merge($_SESSION['usuario'], [
                    'nome' => $nome,
                    'email' => $email,
                    'telefone' => $telefone,
                    'data_nascimento' => $data_nascimento,
                    'endereco' => $endereco,
                    'plano' => $plano,
                    'objetivo' => $objetivo,
                    'genero' => $genero
                ]);

                echo json_encode([
                    'status' => 'sucesso',
                    'mensagem' => 'Perfil atualizado com sucesso!',
                    'redirect' => '/ACADEMY/public/usuario/perfil'
                ]);
            } else {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao atualizar perfil.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro: ' . $e->getMessage()]);
        }
    }

    public function excluirPerfil()
    {
        session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido.']);
            exit;
        }

        if (!isset($_SESSION['usuario']['id'])) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];

        try {
            $stmt = $this->conn->prepare("DELETE FROM usuario WHERE id = :id");
            $stmt->bindParam(':id', $usuarioId);

            if ($stmt->execute()) {
                session_unset();
                session_destroy();

                echo json_encode([
                    'status' => 'sucesso',
                    'mensagem' => 'Perfil excluído com sucesso!',
                    'redirect' => '/ACADEMY/public/home'
                ]);
            } else {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao excluir perfil.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao excluir perfil: ' . $e->getMessage()]);
        }
    }
    public function home()
    {
        session_start();
        // Redireciona para a home pública
        header('Location: /ACADEMY/public');
        exit;
    }

}