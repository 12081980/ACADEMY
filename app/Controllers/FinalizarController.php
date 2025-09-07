<?php
class FinalizarController
{
    public function finalizar()
    {
        session_start();
        $sessaoId = $_POST['sessaoId'] ?? null;

        if (!$sessaoId) {
            echo json_encode(['ok' => false, 'erro' => 'Sessão inválida']);
            return;
        }

        include __DIR__ . '/../Models/Treino.php';
        $treinoModel = new Treino();
        $treinoModel->finalizarTreino($sessaoId);

        echo json_encode(['ok' => true, 'mensagem' => 'Treino finalizado!']);
    }
}
