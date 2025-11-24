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
   
     public function realizados()
    {
        session_start();
        if (!isset($_SESSION['usuario']['id'])) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $usuarioId = $_SESSION['usuario']['id'];

        // Parâmetros de paginação
        $porPagina = 5;
        $paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
        $offset = ($paginaAtual - 1) * $porPagina;

        // Buscar treinos com paginação
        $treinos = $this->treinoModel->listarTreinosRealizadosPaginado($usuarioId, $porPagina, $offset);
        $totalTreinos = $this->treinoModel->contarTreinosRealizados($usuarioId);
        $totalPaginas = ceil($totalTreinos / $porPagina);

        require_once __DIR__ . '/../Views/treinos/realizados.php';
    }

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
    if (session_status() === PHP_SESSION_NONE) session_start();
    header('Content-Type: application/json');

    // log request para depuração
    file_put_contents(__DIR__ . "/../Controllers/log_iniciar.txt", date('Y-m-d H:i:s') . " REQUEST:\n" . print_r($_POST, true) . "\nRAW:\n" . file_get_contents("php://input") . "\n\n", FILE_APPEND);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido.']);
        return;
    }

    $usuario_id = $_SESSION['usuario']['id'] ?? null;
    if (!$usuario_id) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
        return;
    }

    // Aceita tanto FormData quanto json
    $raw = file_get_contents("php://input");
    $json = json_decode($raw, true);
    if ($json && is_array($json)) {
        $_POST = array_merge($_POST, $json);
    }

    // fluxo instrutor (inicio de treino já existente)
    $treino_id = $_POST['id'] ?? null;
    if ($treino_id) {
        try {
            // evita iniciar se já existe em andamento
            $treinoExistente = $this->treinoModel->buscarTreinoEmAndamento($usuario_id, $treino_id);
            if ($treinoExistente) {
                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Treino já em andamento.', 'redirect' => '/ACADEMY/public/treinos/em_andamento']);
                return;
            }
            if ($this->treinoModel->iniciarTreino($treino_id, $usuario_id)) {
                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Treino iniciado!', 'redirect' => '/ACADEMY/public/treinos/em_andamento']);
                return;
            } else {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao iniciar treino existente.']);
                return;
            }
        } catch (Exception $e) {
            error_log("Erro iniciar(instrutor): " . $e->getMessage());
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao iniciar treino existente.']);
            return;
        }
    }

    // fluxo aluno — criar treino do zero
    // Normalmente o FormData virá com: nome, descricao, e exercicios[0][nome] / series / repeticoes / peso (ou carga)
    if (!isset($_POST['nome'])) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Dados insuficientes (nome).']);
        return;
    }

    // Normaliza array de exercícios vindo do form
    $exercicios = $_POST['exercicios'] ?? [];
    // se os inputs vieram como exercicio[] e series[] etc (formato mais simples), converte para o formato esperado
    if (empty($exercicios) && isset($_POST['exercicio']) && is_array($_POST['exercicio'])) {
        $exercicios = [];
        $listaEx = $_POST['exercicio'];
        $listaSeries = $_POST['series'] ?? [];
        $listaReps = $_POST['repeticoes'] ?? [];
        $listaPeso = $_POST['peso'] ?? ($_POST['carga'] ?? []);
        for ($i = 0; $i < count($listaEx); $i++) {
            $exercicios[] = [
                'nome' => $listaEx[$i] ?? '',
                'series' => $listaSeries[$i] ?? 1,
                'repeticoes' => $listaReps[$i] ?? 1,
                'peso' => $listaPeso[$i] ?? 0
            ];
        }
    }

    if (empty($exercicios)) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Dados insuficientes (exercícios).']);
        return;
    }

    $dadosTreino = [
        'usuario_id' => $usuario_id,
        'nome' => $_POST['nome'],
        'tipo' => $_POST['tipo'] ?? $_POST['nome'],
        'descricao' => $_POST['descricao'] ?? '',
        'data_inicio' => date('Y-m-d H:i:s'),
        'status' => 'em_andamento',
        'exercicios' => $exercicios
    ];

    try {
        $novo_id = $this->treinoModel->criarTreino($dadosTreino);
        if (!$novo_id) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao criar treino.']);
            return;
        }
        // marca como iniciado (redundante, mas garante data_inicio/status)
        $this->treinoModel->iniciarTreino($novo_id, $usuario_id);

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Treino criado e iniciado!', 'redirect' => '/ACADEMY/public/treinos/em_andamento']);
    } catch (Exception $e) {
        // log detalhado para debugar
        file_put_contents(__DIR__ . "/../Controllers/log_iniciar_error.txt", date('Y-m-d H:i:s') . " EXCEPTION:\n" . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n", FILE_APPEND);
        error_log("Erro ao criar treino (controller): " . $e->getMessage());
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao criar treino.']);
    }
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
    public function finalizar()
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    header('Content-Type: application/json');

    if (!isset($_SESSION['usuario']['id'])) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
        return;
    }

    $usuario_id = $_SESSION['usuario']['id'];

    // Pegar treino em andamento
    $treino = $this->treinoModel->getTreinoEmAndamento($usuario_id);

    if (!$treino) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Nenhum treino em andamento para finalizar.']);
        return;
    }

    // Finalizar no banco
    $resultado = $this->treinoModel->finalizarTreino($treino['id']);

    if ($resultado) {
        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Treino finalizado com sucesso!',
            'redirect' => '/ACADEMY/public/treinos/realizados'
        ]);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao finalizar o treino.']);
    }
}
public function treinosEnviados() // ou o nome que você usa
{
    session_start();
    if (!isset($_SESSION['usuario']['id'])) {
        header("Location: /ACADEMY/public/login");
        exit;
    }
    $usuarioId = $_SESSION['usuario']['id'];

    // leitura do GET (nome 'pagina' igual ao view)
    $paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    $porPagina = 5;
    $offset = ($paginaAtual - 1) * $porPagina;

    // buscar dados no model (ajuste nomes conforme seu modelo)
    $treinos = $this->treinoModel->getTreinosEnviadosPaginado($usuarioId, $porPagina, $offset);
    $total = $this->treinoModel->contarTreinosEnviados($usuarioId);
    $totalPaginas = $total > 0 ? (int) ceil($total / $porPagina) : 1;

    // passar para a view (dependendo do seu sistema; aqui atribui variáveis)
    $this->viewData['treinos'] = $treinos;
    $this->viewData['paginaAtual'] = $paginaAtual;
    $this->viewData['totalPaginas'] = $totalPaginas;

    // DEBUG opcional: grava o REQUEST_URI e $_GET para inspecionar
    // file_put_contents(__DIR__ . "/../logs/pag_debug.txt", date('Y-m-d H:i:s') . " URI: " . $_SERVER['REQUEST_URI'] . " GET: " . print_r($_GET, true) . PHP_EOL, FILE_APPEND);

    // renderiza view (ajuste conforme seu framework)
    require_once __DIR__ . '/../Views/instrutor/treinos_enviados.php';
}

}