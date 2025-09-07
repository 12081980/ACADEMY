<?php
class UsuarioController
{
    private $conn;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Usa a conexão global criada no conn.php (PDO)
        if (!isset($GLOBALS['conn']) || !$GLOBALS['conn']) {
            http_response_code(500);
            exit('Conexão com BD não inicializada.');
        }
        $this->conn = $GLOBALS['conn'];
    }

    private function json($payload, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }

    private function exigirLogin()
    {
        if (empty($_SESSION['usuario']['id'])) {
            $this->json([
                'status' => 'erro',
                'mensagem' => 'Usuário não logado!',
                'redirect' => '/ACADEMY/public/login'
            ], 401);
        }
    }

    // Exibe a página de perfil
    public function perfil()
    {
        $this->exigirLogin();
        $id = (int) $_SESSION['usuario']['id'];

        // Garantir que temos nome/email atualizados via BD
        $stmt = $this->conn->prepare("SELECT nome, email FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['usuario']['nome'] = $usuario['nome'];
            $_SESSION['usuario']['email'] = $usuario['email'];
        }

        include __DIR__ . '/../Views/usuario/perfil.php';
    }

    // Salvar alterações (nome, email, senha opcional)
    public function atualizar()
    {
        $this->exigirLogin();

        $id = (int) $_SESSION['usuario']['id'];
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if ($nome === '' || $email === '') {
            $this->json(['status' => 'erro', 'mensagem' => 'Nome e email são obrigatórios.'], 422);
        }

        try {
            if ($senha !== '') {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id";
                $ok = $this->conn->prepare($sql)->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $hash,
                    ':id' => $id
                ]);
            } else {
                $sql = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
                $ok = $this->conn->prepare($sql)->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':id' => $id
                ]);
            }

            if ($ok) {
                // Atualiza sessão
                $_SESSION['usuario']['nome'] = $nome;
                $_SESSION['usuario']['email'] = $email;

                $this->json([
                    'status' => 'sucesso',
                    'mensagem' => 'Perfil atualizado com sucesso!',
                    'redirect' => '/ACADEMY/public/usuario/home'
                ]);
            } else {
                $this->json(['status' => 'erro', 'mensagem' => 'Falha ao atualizar.'], 500);
            }
        } catch (Throwable $e) {
            $this->json(['status' => 'erro', 'mensagem' => 'Erro no servidor: ' . $e->getMessage()], 500);
        }
    }

    // Excluir perfil + treinos
    public function excluirPerfil()
    {
        $this->exigirLogin();
        $id = (int) $_SESSION['usuario']['id'];

        try {
            $this->conn->beginTransaction();

            // Apaga treinos realizados (tabela/coluna corretas)
            $this->conn->prepare("DELETE FROM treinos_realizados WHERE id_usuario = :id")
                ->execute([':id' => $id]);

            // (Opcional) Se existir uma tabela 'treinos' com outra coluna, tentamos também:
            try {
                $this->conn->prepare("DELETE FROM treinos WHERE id_usuario = :id")->execute([':id' => $id]);
            } catch (Throwable $ignore) {
                // ignora se tabela/coluna não existir
            }

            // Apaga usuário
            $this->conn->prepare("DELETE FROM usuarios WHERE id = :id")->execute([':id' => $id]);

            $this->conn->commit();

            session_destroy();

            $this->json([
                'status' => 'sucesso',
                'mensagem' => 'Perfil excluído com sucesso!',
                'redirect' => '/ACADEMY/public/'
            ]);
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            $this->json(['status' => 'erro', 'mensagem' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }

    public function lista()
    {
        session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: /ACADEMY/public/");
            exit;
        }

        $usuarioModel = new UsuarioModel();
        $treinoModel = new TreinoModel();

        $usuarios = $usuarioModel->getAll();

        foreach ($usuarios as &$usuario) {
            $usuario['treinos'] = $treinoModel->getTreinosRealizadosByUsuario($usuario['id']);
        }

        include __DIR__ . "/../views/usuario/lista.php";
    }

    public function excluir()
    {
        session_start();
        if (!isset($_POST['id'])) {
            echo json_encode(["status" => "erro", "mensagem" => "Usuário não informado!"]);
            exit;
        }

        $usuarioModel = new UsuarioModel();
        $usuarioModel->delete($_POST['id']);

        echo json_encode(["status" => "sucesso", "mensagem" => "Usuário excluído com sucesso!"]);
    }

    public function editar()
    {
        session_start();
        if (!isset($_POST['id'], $_POST['nome'], $_POST['email'])) {
            echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos!"]);
            exit;
        }

        $usuarioModel = new UsuarioModel();
        $usuarioModel->update($_POST['id'], $_POST['nome'], $_POST['email']);

        echo json_encode(["status" => "sucesso", "mensagem" => "Usuário atualizado com sucesso!"]);
    }
}


