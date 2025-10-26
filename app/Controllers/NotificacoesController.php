<?php
require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/NotificacaoModel.php';

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
            header("Location: /ACADEMY/public");
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $notificacoes = $this->model->listarPorUsuario($usuarioId);

        // Exibe a view
        require __DIR__ . '/../Views/notificacoes/notificacoes.php';
    }

    public function ver()
    {
        session_start();

        $notificacaoId = $_GET['id'] ?? null;
        if (!$notificacaoId) {
            header("Location: /ACADEMY/public/notificacoes");
            exit;
        }

        // Obtém dados da notificação
        $notificacao = $this->model->getPorId($notificacaoId);
        if (!$notificacao) {
            header("Location: /ACADEMY/public/notificacoes");
            exit;
        }

        // Marca como lida
        $this->model->marcarComoLida($notificacaoId);

        // Se houver treino vinculado, carrega os dados
        if (!empty($notificacao['treino_id'])) {
            require_once __DIR__ . '/../Models/TreinoModel.php';
            $treinoModel = new TreinoModel($this->conn);

            $treino = $treinoModel->getPorId($notificacao['treino_id']);
            $exercicios = $treinoModel->getExerciciosDoTreino($notificacao['treino_id']);

            // Renderiza a view com as informações do treino
            include __DIR__ . '/../Views/notificacoes/ver_treino.php';
            exit;
        }

        // Caso contrário, volta para lista de notificações
        header("Location: /ACADEMY/public/notificacoes");
        exit;
    }
}