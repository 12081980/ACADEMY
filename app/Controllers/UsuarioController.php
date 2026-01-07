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
    $usuario = $this->usuarioModel->buscaPorId($usuarioId);

    $_SESSION['usuario'] = $usuario; // 🔥 mantém a sessão sempre atualizada!

    require_once __DIR__ . '/../Views/usuario/perfil.php';
}


    public function excluir($id)
    {
        if ($this->usuarioModel->excluirUsuario($id)) {
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
            echo json_encode(['status' => 'erro', 'mensagem' => 'Método inválido']);
            exit;
        }

        $id = $_SESSION['usuario']['id'];
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'senha' => $_POST['senha'] ?? '',
            'telefone' => trim($_POST['telefone'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => trim($_POST['estado'] ?? ''),
            'bairro' => trim($_POST['bairro'] ?? ''),
            'rua' => trim($_POST['rua'] ?? ''),
            'numero' => trim($_POST['numero'] ?? ''),
            'tipo' => $_SESSION['usuario']['tipo'] ?? 'aluno'
        ];

        if (!$dados['nome'] || !$dados['email']) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Campos obrigatórios não preenchidos.']);
            exit;
        }

        $model = new UsuarioModel($this->conn);

        if ($model->update($id, $dados)) {
            // Atualiza sessão
            $_SESSION['usuario'] = array_merge($_SESSION['usuario'], $dados);
            echo json_encode([
                'status' => 'sucesso',
                'mensagem' => 'Perfil atualizado com sucesso!',
                'redirect' => '/ACADEMY/public/usuario/perfil'
            ]);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Falha ao atualizar o perfil.']);
        }
        exit;
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
public function abrirNotificacao($id)
{
    session_start();

    require_once __DIR__ . '/../Models/NotificacaoModel.php';
    $model = new NotificacaoModel($this->conn);

    $notificacao = $model->getPorId($id);

    if (!$notificacao || $notificacao['usuario_id'] != $_SESSION['usuario']['id']) {
        header("Location: /ACADEMY/public");
        exit;
    }

    $model->marcarComoLida($id);

    // Redireciona conforme tipo
    if ($notificacao['avaliacao_id']) {
        header("Location: /ACADEMY/public/usuario/avaliacao/" . $notificacao['avaliacao_id']);
    } elseif ($notificacao['treino_id']) {
        header("Location: /ACADEMY/public/usuario/treino/" . $notificacao['treino_id']);
    }

    exit;
}
public function avaliacaoVer($idRota = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // usuário precisa estar logado
    if (!isset($_SESSION['usuario']['id'])) {
        header("Location: /ACADEMY/public/login");
        exit;
    }

    $id = $idRota ?? ($_GET['id'] ?? null);

    if (!$id) {
        header("Location: /ACADEMY/public/notificacoes");
        exit;
    }

    require_once __DIR__ . '/../Models/AvaliacaoModel.php';
    $avaliacaoModel = new AvaliacaoModel($this->conn);

    $avaliacao = $avaliacaoModel->buscarPorId($id);

    // garante que a avaliação pertence ao usuário logado
    if (
        !$avaliacao ||
        $avaliacao['usuario_id'] != $_SESSION['usuario']['id']
    ) {
        header("Location: /ACADEMY/public/notificacoes");
        exit;
    }

    include __DIR__ . '/../Views/usuario/avaliacaoVer.php';
}

}