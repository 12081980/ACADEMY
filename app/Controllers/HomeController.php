<?php
require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';
require_once __DIR__ . '/../Models/NotificacaoModel.php';

class HomeController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        global $conn;

        $usuarioModel = new UsuarioModel($conn);
        $usuarios = $usuarioModel->listarTodos();

        $notificacaoModel = new NotificacaoModel($conn);

        $usuario_id = $_SESSION['usuario']['id'] ?? 0;
        $notificacoes = [];

        // use the already-created $notificacaoModel (which was constructed with $conn)
        if ($usuario_id) {
            // get unread notifications for the logged-in user
            $notificacoes = $notificacaoModel->listarNaoLidas($usuario_id);
        }

        require_once __DIR__ . '/../Views/templates/header.php';
        require __DIR__ . '/../Views/index.php';
        require_once __DIR__ . '/../Views/templates/footer.php';
    }
}
