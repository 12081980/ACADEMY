<?php
require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/NotificacaoModel.php';
require_once __DIR__ . '/../Models/TreinoModel.php';

class NotificacoesController
{
    private $model;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->model = new NotificacaoModel($this->conn);
    }

    public function index()
    {
        session_start();

        if (!isset($_SESSION['usuario']['id'])) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $notificacoes = $this->model->listarPorUsuario($usuarioId);

        include __DIR__ . '/../Views/notificacoes/notificacoes.php';
    }

    public function ver()
    {
        session_start();

        $notificacaoId = $_GET['id'] ?? null;
        if (!$notificacaoId) {
            header("Location: /ACADEMY/public/notificacoes");
            exit;
        }

        $notificacao = $this->model->getPorId($notificacaoId);
        if (!$notificacao) {
            header("Location: /ACADEMY/public/notificacoes");
            exit;
        }

        $this->model->marcarComoLida($notificacaoId);

        // Caso a notificação esteja vinculada a um treino
        if (!empty($notificacao['treino_id'])) {
            $treinoModel = new TreinoModel($this->conn);
            $treino = $treinoModel->getPorId($notificacao['treino_id']);
            $exercicios = $treinoModel->getExerciciosDoTreino($notificacao['treino_id']);

            include __DIR__ . '/../Views/notificacoes/ver_treino.php';
            exit;
        }

        header("Location: /ACADEMY/public/notificacoes");
        exit;
    }
}
