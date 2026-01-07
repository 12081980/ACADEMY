<?php

require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/NotificacaoModel.php';
require_once __DIR__ . '/../Models/TreinoModel.php';
require_once __DIR__ . '/../Models/AvaliacaoModel.php';

class NotificacoesController
{
    private $conn;
    private $notificacaoModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->notificacaoModel = new NotificacaoModel($this->conn);
    }

    // =============================
    // 📬 LISTAR NOTIFICAÇÕES
    // =============================
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario']['id'])) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $notificacoes = $this->notificacaoModel->listarPorUsuario($usuarioId);

        require __DIR__ . '/../Views/notificacoes/notificacoes.php';
    }

    // =============================
    // 👁️ VER NOTIFICAÇÃO
    // =============================
   public function ver($id = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 🔹 Se o Router não passou ID, pega via GET
    if ($id === null && isset($_GET['id'])) {
        $id = (int) $_GET['id'];
    }

    if (!$id) {
        header('Location: /ACADEMY/public/notificacoes');
        exit;
    }

    // 🔹 Busca notificação
    $notificacao = $this->notificacaoModel->getPorId($id);

    if (!$notificacao) {
        header('Location: /ACADEMY/public/notificacoes');
        exit;
    }

    // 🔹 Marca como lida
    $this->notificacaoModel->marcarComoLida($id);

    // =============================
    // 🔁 NOTIFICAÇÃO DE TREINO
    // =============================
    if (!empty($notificacao['treino_id'])) {

        $treinoModel = new TreinoModel($this->conn);
        $treino = $treinoModel->getPorId($notificacao['treino_id']);
        $exercicios = $treinoModel->getExerciciosDoTreino(
            $notificacao['treino_id']
        );

        require __DIR__ . '/../Views/notificacoes/ver_treino.php';
        return;
    }

    // =============================
    // 📊 NOTIFICAÇÃO DE AVALIAÇÃO
    // =============================
    if (!empty($notificacao['avaliacao_id'])) {

        $avaliacaoModel = new AvaliacaoModel($this->conn);
        $avaliacao = $avaliacaoModel->buscarPorId($notificacao['avaliacao_id']);

        if (!$avaliacao) {
            header('Location: /ACADEMY/public/notificacoes');
            exit;
        }

        require __DIR__ . '/../Views/notificacoes/ver_avaliacao.php';
        return;
    }

    header('Location: /ACADEMY/public/notificacoes');
    exit;
}
}