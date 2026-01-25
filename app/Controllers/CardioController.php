<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



require_once __DIR__ . '/../Models/CardioModel.php';

class CardioController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new CardioModel($conn);
    }

 public function iniciar()
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Método não permitido'
        ]);
        return;
    }

    $usuarioId = $_SESSION['usuario']['id'];

    // 🔒 BLOQUEIA DUPLICAÇÃO
    if ($this->model->emAndamento($usuarioId)) {
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Já existe um cardio em andamento'
        ]);
        return;
    }

    $dados = json_decode(file_get_contents('php://input'), true);

    $this->model->criar([
        'usuario_id'  => $usuarioId,
        'tipo'        => $dados['tipo'],
        'tempo_min'   => $dados['tempo'],
        'ritmo'       => $dados['ritmo'] ?? null,
        'data_inicio' => date('Y-m-d H:i:s')
    ]);

    echo json_encode([
        'status'   => 'ok',
        'redirect' => '/ACADEMY/public/cardio/andamento'
    ]);
}


    public function andamento()
    {
        $cardio = $this->model->atual($_SESSION['usuario']['id']);
        require __DIR__ . '/../Views/cardio/andamento.php';
    }

   public function finalizar()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $usuarioId = $_SESSION['usuario']['id'];

    // Finaliza o cardio em andamento
    $this->model->finalizar($usuarioId);

    // Redireciona para o histórico
    header('Location: /ACADEMY/public/cardio/historico');
    exit;
}


    public function historico()
{
    $usuarioId = $_SESSION['usuario']['id'];

    $pagina = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limite = 5;
    $offset = ($pagina - 1) * $limite;

    $lista = $this->model->listarPaginado($usuarioId, $limite, $offset);
    $total = $this->model->total($usuarioId);

    $totalPaginas = ceil($total / $limite);

    require __DIR__ . '/../Views/cardio/historico.php';
}

public function indexCardio()
{
    $usuarioId = $_SESSION['usuario']['id'];

    // verifica se existe cardio em andamento
    $cardioEmAndamento = $this->model->emAndamento($usuarioId);

    require __DIR__ . '/../Views/cardio/indexCardio.php';
}


}
