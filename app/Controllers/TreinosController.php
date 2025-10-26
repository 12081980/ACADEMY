<?php
require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/TreinoModel.php';

class TreinosController
{
    private $conn;
    private $treinoModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->treinoModel = new TreinoModel($conn);
    }

    /**
     * Página de treinos realizados
     */
    public function realizados()
    {
        session_start();
        if (!isset($_SESSION['usuario']['id'])) {
            header("Location: /login");
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];

        // Paginação
        $porPagina = 5;
        $paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
        $offset = ($paginaAtual - 1) * $porPagina;

        // Busca treinos finalizados com limite e offset
        $treinos = $this->treinoModel->getTreinosFinalizados($usuarioId, $porPagina, $offset);

        // Total de treinos para paginação
        $totalTreinos = $this->treinoModel->contarTreinosFinalizados($usuarioId);
        $totalPaginas = ceil($totalTreinos / $porPagina);

        // Para cada treino, buscar os exercícios e calcular total de séries e peso
        foreach ($treinos as &$treino) {
            $exercicios = $this->treinoModel->getExerciciosDoTreino($treino['id']);

            $treino['exercicios'] = $exercicios;
            $treino['qtd_exercicios'] = count($exercicios);
            $treino['peso_total'] = array_sum(array_column($exercicios, 'carga')); // total de carga
        }

        require_once __DIR__ . '/../Views/treinos/realizados.php';
    }


    /**
     * Página de treino em andamento
     */
    // public function em_andamento()
    // {
    //     session_start();

    //     if (!isset($_SESSION['usuario']['id'])) {
    //         header('Location: /ACADEMY/public/login');
    //         exit;
    //     }

    //     $usuarioId = $_SESSION['usuario']['id'];
    //     $treino = $this->treinoModel->getTreinoEmAndamento($usuarioId);

    //     // Se não há treino em andamento, redireciona para página principal de treinos
    //     if (!$treino) {
    //         header('Location: /ACADEMY/public/treinos');
    //         exit;
    //     }

    //     // Envia o treino para a view
    //     require_once __DIR__ . '/../Views/treinos/em_andamento.php';
    // }
    public function em_andamento()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        // Garante que o usuário está logado
        if (!isset($_SESSION['usuario'])) {
            header('Location: /ACADEMY/public');
            exit;
        }

        $usuario_id = $_SESSION['usuario']['id'];

        // Se for requisição POST → responder JSON
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $treino = $this->treinoModel->getTreinoEmAndamento($usuario_id);

            header('Content-Type: application/json');
            if ($treino) {
                echo json_encode([
                    'status' => 'sucesso',
                    'treino' => $treino
                ]);
            } else {
                echo json_encode([
                    'status' => 'erro',
                    'mensagem' => 'Nenhum treino em andamento.'
                ]);
            }
            exit;
        }

        // Se for GET → carregar página normalmente
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $treino = $this->treinoModel->getTreinoEmAndamento($usuario_id);
            require_once __DIR__ . '/../Views/treinos/em_andamento.php';
            exit;
        }

        // Caso venha outro método (PUT, DELETE, etc.)
        header("HTTP/1.1 405 Método não permitido");
        echo "Método não permitido.";
        exit;
    }

    /**
     * Iniciar um novo treino
     */
    // public function iniciar()
    // {
    //     session_start();
    //     header('Content-Type: application/json');

    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido.']);
    //         exit;
    //     }

    //     if (!isset($_SESSION['usuario']['id'])) {
    //         echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
    //         exit;
    //     }

    //     $usuarioId = $_SESSION['usuario']['id'];
    //     $dados = [
    //         'usuario_id' => $usuarioId,
    //         'nome' => $_POST['nome'] ?? 'Treino Personalizado',
    //         'tipo' => $_POST['tipo'] ?? 'geral',
    //         'descricao' => $_POST['descricao'] ?? null,
    //         'data_inicio' => date('Y-m-d H:i:s'),
    //         'status' => 'em_andamento'
    //     ];

    //     $treinoId = $this->treinoModel->criarTreino($dados);

    //     if ($treinoId) {
    //         echo json_encode([
    //             'status' => 'sucesso',
    //             'mensagem' => 'Treino iniciado com sucesso!',
    //             'redirect' => '/ACADEMY/public/treinos/em_andamento'
    //         ]);
    //     } else {
    //         echo json_encode([
    //             'status' => 'erro',
    //             'mensagem' => 'Erro ao iniciar treino.'
    //         ]);
    //     }
    // }
    public function iniciar()
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
        $nome = $_POST['nome'] ?? 'Treino Personalizado';
        $descricao = $_POST['descricao'] ?? null;
        $dataInicio = date('Y-m-d H:i:s');
        $status = 'em_andamento';

        // Cria treino
        $treinoId = $this->treinoModel->criarTreino([
            'usuario_id' => $usuarioId,
            'nome' => $nome,
            'descricao' => $descricao,
            'tipo' => strtoupper($nome), // tipo A/B/C/D
            'data_inicio' => $dataInicio,
            'status' => $status
        ]);

        if (!$treinoId) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao iniciar treino.']);
            exit;
        }

        // Processa os exercícios enviados
        if (isset($_POST['exercicios'])) {
            $exercicios = json_decode($_POST['exercicios'], true);

            foreach ($exercicios as $ex) {
                $this->treinoModel->adicionarExercicioAoTreino($treinoId, [
                    'nome' => $ex['nome'],
                    'series' => (int) $ex['series'],
                    'repeticoes' => $ex['repeticoes'],
                    'carga' => (float) $ex['carga']
                ]);
            }
        }

        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Treino iniciado com sucesso!',
            'redirect' => '/ACADEMY/public/treinos/em_andamento'
        ]);
    }

    /**
     * Finalizar treino em andamento
     */
    public function finalizar()
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
        $treino = $this->treinoModel->getTreinoEmAndamento($usuarioId);

        if (!$treino) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Nenhum treino em andamento.']);
            exit;
        }

        $this->treinoModel->finalizarTreino($treino['id']);

        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Treino finalizado com sucesso!',
            'redirect' => '/ACADEMY/public/treinos/realizados'
        ]);
    }

    /**
     * Página de gráficos (estatísticas do usuário)
     */
    public function graficos()
    {
        session_start();
        if (!isset($_SESSION['usuario']['id'])) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        require_once __DIR__ . '/../Views/treinos/graficos.php';
    }
    public function recebidos()
    {
        session_start();

        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'usuario') {
            header('Location: /ACADEMY/public/login');
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $treinos = $this->treinoModel->listarPorUsuario($usuarioId);

        require 'app/Views/treinos/recebidos.php';
    }
}

