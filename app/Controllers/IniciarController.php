<?php
class IniciarController
{
    public function iniciar()
    {
        session_start();
        header('Content-Type: application/json');

        $treino = $_POST['treino'] ?? '';
        if (empty($treino)) {
            echo json_encode(['ok' => false, 'erro' => 'Treino não informado.']);
            exit;
        }

        // Simulação de dados do treino — você pode buscar do banco se quiser
        $dadosTreino = [
            'nome' => $treino,
            'descricao' => 'Treino personalizado',
            'inicio' => date('Y-m-d H:i:s'),
        ];

        // Salvar na sessão para a view usar
        $_SESSION['treino_em_andamento'] = $dadosTreino;

        echo json_encode([
            'ok' => true,
            'mensagem' => 'Treino iniciado',
            'redirect' => '/ACADEMY/views/treino/em_andamento.php' // Caminho direto da view
        ]);
    }
}
