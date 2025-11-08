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
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $porPagina = 5;
        $paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
        $offset = ($paginaAtual - 1) * $porPagina;

        // ===============================
        // ✅ Buscar treinos finalizados
        // ===============================
        $sql = "SELECT 
            t.id,
            t.tipo,
            t.nome,
            COALESCE(t.data_fim, t.data_treino) AS data_treino,
            COALESCE(t.qtd_exercicios, 0) AS qtd_exercicios,
            COALESCE(t.peso_total, 0) AS peso_total
        FROM treino t
        WHERE t.usuario_id = :usuario_id
          AND t.status = 'finalizado'
        ORDER BY COALESCE(t.data_fim, t.data_treino) DESC
        LIMIT :limit OFFSET :offset";


        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ===============================
        // ✅ Calcular dados por treino
        // ===============================
        foreach ($treinos as &$treino) {
            // Buscar exercícios de cada treino
            $sqlEx = "SELECT 
                    nome_exercicio,
                    series,
                    repeticoes,
                    carga
                  FROM treino_exercicio
                  WHERE treino_id = :treino_id";

            $stmtEx = $this->conn->prepare($sqlEx);
            $stmtEx->bindValue(':treino_id', $treino['id'], PDO::PARAM_INT);
            $stmtEx->execute();
            $exercicios = $stmtEx->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($exercicios)) {
                $treino['exercicios'] = $exercicios;
                $treino['qtd_exercicios'] = count($exercicios);
                $treino['peso_total'] = array_sum(array_column($exercicios, 'carga'));
            } else {
                $treino['exercicios'] = [];
                $treino['qtd_exercicios'] = 0;
                $treino['peso_total'] = 0;
            }
        }

        // ===============================
        // ✅ Contar total de treinos (para paginação)
        // ===============================
        $sqlCount = "SELECT COUNT(*) FROM treino WHERE usuario_id = :usuario_id AND status = 'finalizado'";
        $stmtCount = $this->conn->prepare($sqlCount);
        $stmtCount->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmtCount->execute();
        $totalTreinos = $stmtCount->fetchColumn();
        $totalPaginas = ceil($totalTreinos / $porPagina);

        // ===============================
        // ✅ Renderizar a View
        // ===============================
        require_once __DIR__ . '/../Views/treinos/realizados.php';
    }

    /**
     * Página do treino em andamento
     */
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

        // 🔹 Lê o JSON corretamente (pois $_POST vem vazio)
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos.']);
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $nome = $input['nome'] ?? 'Treino Personalizado';
        $descricao = $input['descricao'] ?? '';
        $exercicios = $input['exercicios'] ?? [];

        $dataInicio = date('Y-m-d H:i:s');
        $status = 'em_andamento';

        // 🔹 Cria treino
        $treinoId = $this->treinoModel->criarTreino([
            'usuario_id' => $usuarioId,
            'nome' => $nome,
            'descricao' => $descricao,
            'tipo' => strtoupper(substr($nome, -1)), // A, B, C, D
            'data_inicio' => $dataInicio,
            'status' => $status
        ]);

        if (!$treinoId) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao iniciar treino.']);
            exit;
        }

        // 🔹 Salva exercícios corretamente
        foreach ($exercicios as $ex) {
            $this->treinoModel->adicionarExercicioAoTreino($treinoId, [
                'nome' => $ex['nome'],
                'series' => (int) $ex['series'],
                'repeticoes' => $ex['repeticoes'],
                'carga' => (float) $ex['carga']
            ]);
        }

        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Treino iniciado com sucesso!',
            'redirect' => '/ACADEMY/public/treinos/em_andamento'
        ]);
    }

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
    //     $nome = $_POST['nome'] ?? 'Treino Personalizado';
    //     $descricao = $_POST['descricao'] ?? null;
    //     $dataInicio = date('Y-m-d H:i:s');
    //     $status = 'em_andamento';

    //     // Cria treino
    //     $treinoId = $this->treinoModel->criarTreino([
    //         'usuario_id' => $usuarioId,
    //         'nome' => $nome,
    //         'descricao' => $descricao,
    //         'tipo' => strtoupper($nome), // tipo A/B/C/D
    //         'data_inicio' => $dataInicio,
    //         'status' => $status
    //     ]);

    //     if (!$treinoId) {
    //         echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao iniciar treino.']);
    //         exit;
    //     }

    //     // Processa os exercícios enviados
    //     if (isset($_POST['exercicios'])) {
    //         $exercicios = json_decode($_POST['exercicios'], true);

    //         foreach ($exercicios as $ex) {
    //             $this->treinoModel->adicionarExercicioAoTreino($treinoId, [
    //                 'nome' => $ex['nome'],
    //                 'series' => (int) $ex['series'],
    //                 'repeticoes' => $ex['repeticoes'],
    //                 'carga' => (float) $ex['carga']
    //             ]);
    //         }
    //     }

    //     echo json_encode([
    //         'status' => 'sucesso',
    //         'mensagem' => 'Treino iniciado com sucesso!',
    //         'redirect' => '/ACADEMY/public/treinos/em_andamento'
    //     ]);
    // }

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

        $usuarioId = $_SESSION['usuario']['id'];

        // ===============================
        // 🔹 Buscar dados consolidados por treino finalizado
        // ===============================
        $sql = "SELECT 
                DATE(t.data_fim) AS data_treino,
                COUNT(te.id) AS qtd_exercicios,
                COALESCE(SUM(te.carga), 0) AS peso_total
            FROM treino t
            LEFT JOIN treino_exercicio te ON te.treino_id = t.id
            WHERE t.usuario_id = :usuario_id
              AND t.status = 'finalizado'
            GROUP BY DATE(t.data_fim)
            ORDER BY DATE(t.data_fim) ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ===============================
        // 🔹 Passar dados para a View
        // ===============================
        require_once __DIR__ . '/../Views/treinos/graficos.php';
    }
}