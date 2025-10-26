<?php
require_once __DIR__ . '/../Models/UsuarioModel.php';
require_once __DIR__ . '/../Models/ExercicioModel.php';

class InstrutorController
{
    private $conn;
    private $usuarioModel;
    private $exercicioModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->usuarioModel = new UsuarioModel($conn);
        $this->exercicioModel = new ExercicioModel($conn);
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header('Location: /ACADEMY/public');
            exit;
        }

        $stmt = $this->conn->prepare("SELECT id, nome, email FROM usuario WHERE tipo = '' ORDER BY nome ASC");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../Views/Instrutor/dashboardInstrutor.php';
    }

    public function enviarTreinoForm()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header('Location: /ACADEMY/public');
            exit;
        }

        $stmt = $this->conn->prepare("SELECT id, nome, email FROM usuario WHERE tipo = '' ORDER BY nome ASC");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../Views/Instrutor/enviar_treino.php';
    }

    public function enviarTreino()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Método não permitido.";
            exit;
        }

        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $usuario_id = $_POST['usuario_id'] ?? null;
        $treino_tipo = $_POST['treino_tipo'] ?? null;
        $exercicios = $_POST['exercicios'] ?? [];

        if (!$usuario_id || !$treino_tipo || empty($exercicios)) {
            $_SESSION['msg_erro'] = "Preencha todos os campos.";
            header("Location: /ACADEMY/public/instrutor/enviar");
            exit;
        }

        $stmt = $this->conn->prepare("INSERT INTO treino (usuario_id, tipo, status, data_inicio) VALUES (:uid, :tipo, 'pendente', NOW())");
        $stmt->execute([
            ':uid' => $usuario_id,
            ':tipo' => $treino_tipo
        ]);
        $treino_id = $this->conn->lastInsertId();

        $stmtEx = $this->conn->prepare("INSERT INTO treino_exercicio (treino_id, nome_exercicio, series, repeticoes, carga) VALUES (:tid, :nome, :series, :repeticoes, :carga)");
        foreach ($exercicios as $ex) {
            $stmtEx->execute([
                ':tid' => $treino_id,
                ':nome' => $ex['nome'],
                ':series' => $ex['series'],
                ':repeticoes' => $ex['repeticoes'],
                ':carga' => $ex['carga']
            ]);
        }

        // Cria notificação
        $stmtNotif = $this->conn->prepare("INSERT INTO notificacoes (usuario_id, mensagem) VALUES (:uid, :msg)");
        $mensagem = "📩 Seu instrutor enviou um novo treino do tipo {$treino_tipo}.";
        $stmtNotif->execute([':uid' => $usuario_id, ':msg' => $mensagem]);

        $_SESSION['msg_sucesso'] = "Treino enviado com sucesso!";
        header("Location: /ACADEMY/public/instrutor/dashboardInstrutor");
        exit;
    }
    // public function enviar_treino()
    // {
    //     session_start();

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $usuarioId = $_POST['usuario_id'];
    //         $treinoTipo = $_POST['treino_tipo'];
    //         $exercicios = $_POST['exercicios'];
    //         $instrutorId = $_SESSION['usuario_id'];

    //         $treinoModel = new TreinoModel();
    //         $treinoId = $treinoModel->salvarTreino($instrutorId, $usuarioId, $treinoTipo, $exercicios);

    //         // Envia notificação
    //         $notificacaoModel = new NotificacaoModel();
    //         $mensagem = "Seu instrutor enviou o Treino $treinoTipo. Clique para ver.";
    //         $notificacaoModel->enviar($usuarioId, $mensagem, $treinoId);

    //         // Redireciona para a nova página
    //         header("Location: /ACADEMY/public/instrutor/treinos_enviados");
    //         exit;
    //     }
    //}
    public function treinos_enviados()
    {
        session_start();
        $instrutorId = $_SESSION['usuario_id'] ?? null;

        if (!$instrutorId) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $treinoModel = new TreinoModel();
        $treinos = $treinoModel->getTreinosEnviadosPorInstrutor($instrutorId);

        include __DIR__ . '/../Views/instrutor/treinos_enviados';
    }
    // public function enviar_treino()
    // {
    //     session_start();

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $usuarioId = $_POST['usuario_id'];
    //         $treinoTipo = $_POST['treino_tipo'];
    //         $exercicios = $_POST['exercicios'];
    //         $instrutorId = $_SESSION['usuario_id'];

    //         $treinoModel = new TreinoModel();
    //         $treinoId = $treinoModel->salvarTreino($instrutorId, $usuarioId, $treinoTipo, $exercicios);

    //         // Envia notificação
    //         $notificacaoModel = new NotificacaoModel();
    //         $mensagem = "Seu instrutor enviou o Treino $treinoTipo. Clique para ver.";
    //         $notificacaoModel->enviar($usuarioId, $mensagem, $treinoId);

    //         // Redireciona para a nova página
    //         header("Location: /ACADEMY/public/instrutor/treinos_enviados");
    //         exit;
    //     }
    // }
    public function editar_treino()
    {
        session_start();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /ACADEMY/public/instrutor/treinos_enviados");
            exit;
        }

        $treinoModel = new TreinoModel();
        $treino = $treinoModel->getTreinoPorId($id);

        include __DIR__ . '/../Views/instrutor/editar_treino.php';
    }

    public function atualizar_treino()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $tipo = $_POST['tipo'];

            $treinoModel = new TreinoModel();
            $treinoModel->atualizarTreino($id, $tipo);

            header("Location: /ACADEMY/public/instrutor/treinos_enviados");
            exit;
        }
    }

    public function excluir_treino()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $treinoModel = new TreinoModel();
            $treinoModel->excluirTreino($id);
        }
        header("Location: /ACADEMY/public/instrutor/treinos_enviados");
        exit;
    }
    public function enviar_treino()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'];
            $treinoTipo = $_POST['treino_tipo'];
            $exercicios = $_POST['exercicios'];
            $instrutorId = $_SESSION['usuario_id'];

            $treinoModel = new TreinoModel();
            $treinoId = $treinoModel->salvarTreino($instrutorId, $usuarioId, $treinoTipo, $exercicios);

            if ($treinoId) {
                // Enviar notificação para o aluno
                $notificacaoModel = new NotificacaoModel();
                $mensagem = "Seu instrutor enviou o Treino {$treinoTipo}. Clique para ver os detalhes.";
                $notificacaoModel->enviar($usuarioId, $mensagem, $treinoId);

                // Redireciona o instrutor para os treinos enviados
                header("Location: /ACADEMY/public/instrutor/treinos_enviados");
                exit;
            } else {
                $_SESSION['msg_erro'] = "Erro ao enviar o treino.";
                header("Location: /ACADEMY/public/instrutor/enviar");
                exit;
            }
        }
    }

}
