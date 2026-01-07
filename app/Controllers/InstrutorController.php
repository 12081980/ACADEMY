<?php
require_once __DIR__ . '/../Models/UsuarioModel.php';
require_once __DIR__ . '/../Models/ExercicioModel.php';
require_once __DIR__ . '/../Models/AvaliacaoModel.php';


class InstrutorController
{
    private $conn;
    private $usuarioModel;
    private $exercicioModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->usuarioModel = new UsuarioModel($conn);
        $this->exercicioModel = new ExercicioModel($conn);
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header('Location: /ACADEMY/public');
            exit;
        }

        $stmt = $this->conn->prepare("SELECT id, nome, email FROM usuario WHERE tipo = '' ORDER BY nome ASC");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../Views/Instrutor/dashboardInstrutor.php';
    }

    public function enviarTreinoForm()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header('Location: /ACADEMY/public');
            exit;
        }

        $stmt = $this->conn->prepare("SELECT id, nome, email FROM usuario WHERE tipo = '' ORDER BY nome ASC");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../Views/Instrutor/enviar_treino.php';
    }

    public function enviarTreino()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Método não permitido.";
            exit;
        }

        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $usuario_id = $_POST['usuario_id'] ?? null;
        $treino_tipo = $_POST['treino_tipo'] ?? null;
        $exercicios = $_POST['exercicios'] ?? [];

        if (!$usuario_id || !$treino_tipo || empty($exercicios)) {
            $_SESSION['msg_erro'] = "Preencha todos os campos.";
            header("Location: /ACADEMY/public/instrutor/enviar");
            exit;
        }

        // ✅ Pega o ID do instrutor logado
        $instrutor_id = $_SESSION['usuario']['id'] ?? null;

        // ===============================
        // 1️⃣ Inserir o treino com instrutor_id
        // ===============================
        $stmt = $this->conn->prepare("
        INSERT INTO treino (usuario_id, instrutor_id, tipo, status, data_inicio, criado_em)
        VALUES (:uid, :iid, :tipo, 'pendente', NOW(), NOW())
    ");
        $stmt->execute([
            ':uid' => $usuario_id,
            ':iid' => $instrutor_id,
            ':tipo' => $treino_tipo
        ]);
        $treino_id = $this->conn->lastInsertId();

        // ===============================
        // 2️⃣ Inserir exercícios
        // ===============================
        $stmtEx = $this->conn->prepare("
        INSERT INTO treino_exercicio (treino_id, nome_exercicio, series, repeticoes, carga)
        VALUES (:tid, :nome, :series, :repeticoes, :carga)
    ");
        foreach ($exercicios as $ex) {
            $stmtEx->execute([
                ':tid' => $treino_id,
                ':nome' => $ex['nome'],
                ':series' => $ex['series'],
                ':repeticoes' => $ex['repeticoes'],
                ':carga' => $ex['carga']
            ]);
        }

        // ===============================
        // 3️⃣ Criar notificação para o aluno
        // ===============================
        $stmtNotif = $this->conn->prepare("
        INSERT INTO notificacoes (usuario_id, treino_id, mensagem)
        VALUES (:uid, :tid, :msg)
    ");
        $mensagem = "📩 Seu instrutor enviou um novo treino do tipo {$treino_tipo}.";
        $stmtNotif->execute([
            ':uid' => $usuario_id,
            ':tid' => $treino_id,
            ':msg' => $mensagem
        ]);

        // ===============================
        // 4️⃣ Mensagem de sucesso e redirecionamento
        // ===============================
        $_SESSION['msg_sucesso'] = "Treino enviado com sucesso!";
        header("Location: /ACADEMY/public/instrutor/treinos_enviados");
        exit;
    }


    public function treinos_enviados()
{
    session_start();

    if (!isset($_SESSION['usuario']['id'])) {
        header("Location: /ACADEMY/public/login");
        exit;
    }

    if ($_SESSION['usuario']['tipo'] !== 'instrutor') {
        header("Location: /ACADEMY/public/home");
        exit;
    }

    $instrutorId = $_SESSION['usuario']['id'];

    // Pegando pagina atual
    $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($paginaAtual < 1) $paginaAtual = 1;

    // Número de itens por página
    $limite = 5;
    $offset = ($paginaAtual - 1) * $limite;

    require_once __DIR__ . '/../Models/TreinoModel.php';
    $treinoModel = new TreinoModel($this->conn);

    // Busca os treinos paginados
    $treinos = $treinoModel->listarTreinosEnviadosPaginado($instrutorId, $limite, $offset);

    // Conta total para calcular número de páginas
    $totalRegistros = $treinoModel->contarTreinosEnviados($instrutorId);
    $totalPaginas = ceil($totalRegistros / $limite);

    // Envia para a view
    require_once __DIR__ . '/../Views/instrutor/treinos_enviados.php';
}

  
    public function enviar_treino()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'];
            $treinoTipo = $_POST['treino_tipo'];
            $exercicios = $_POST['exercicios'];
            $instrutorId = $_SESSION['usuario_id'];

            $treinoModel = new TreinoModel();
            $treinoId = $treinoModel->salvarTreino($instrutorId, $usuarioId, $treinoTipo, $exercicios);

            if ($treinoId) {
                // Enviar notificação para o aluno
                $notificacaoModel = new NotificacaoModel();
                $mensagem = "Seu instrutor enviou o Treino {$treinoTipo}. Clique para ver os detalhes.";
                $notificacaoModel->enviar($usuarioId, $mensagem, $treinoId);

                // Redireciona o instrutor para os treinos enviados
                header("Location: /ACADEMY/public/instrutor/treinos_enviados");
                exit;
            } else {
                $_SESSION['msg_erro'] = "Erro ao enviar o treino.";
                header("Location: /ACADEMY/public/instrutor/enviar");
                exit;
            }
        }
    }
    public function avaliacaoEscolher()
    {
        $usuarioModel = new UsuarioModel($this->conn);

        $usuarios = $usuarioModel->buscarTodosUsuarios();

        include __DIR__ . '/../Views/instrutor/avaliacaoEscolher.php';
    }

 public function salvarAvaliacao()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json; charset=utf-8');

    try {
        if (!isset($_SESSION['usuario']['id'])) {
            throw new Exception('Sessão expirada');
        }

        require_once __DIR__ . '/../Models/AvaliacaoModel.php';
        require_once __DIR__ . '/../Models/NotificacaoModel.php';

        $avaliacaoModel = new AvaliacaoModel($this->conn);
        $notificacaoModel = new NotificacaoModel($this->conn);

        $_POST['instrutor_id'] = $_SESSION['usuario']['id'];

        // 1️⃣ Salva avaliação
        $avaliacaoModel->salvar($_POST);

        // 2️⃣ Pega ID da avaliação salva
        $avaliacaoId = $avaliacaoModel->getLastId();

        // 3️⃣ Cria notificação para o aluno
        $mensagem = "📊 Uma nova avaliação física foi registrada.";
        $notificacaoModel->enviarAvaliacao(
            $_POST['usuario_id'],
            $avaliacaoId,
            $mensagem
        );

        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Avaliação salva com sucesso!',
            'redirect' => '/ACADEMY/public/instrutor/avaliacoesSalvas'
        ]);
        exit;

    } catch (Throwable $e) {
        echo json_encode([
            'status' => 'erro',
            'mensagem' => $e->getMessage()
        ]);
        exit;
    }
}

    public function avaliacoesSalvas()
    {
        // garante sessão
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // valida login e tipo
        if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        // id do instrutor (corrigido)
        $instrutorId = $_SESSION['usuario']['id'];

        // garante que o model foi carregado
        require_once __DIR__ . '/../Models/AvaliacaoModel.php';
        $avaliacaoModel = new AvaliacaoModel($this->conn);

        // busca avaliações feitas por este instrutor
        $avaliacoes = $avaliacaoModel->listarPorInstrutor($instrutorId);

        include __DIR__ . '/../Views/instrutor/avaliacoesSalvas.php';
    }



    public function listarUsuariosParaAvaliacao()
    {
        $usuarioModel = new UsuarioModel($this->conn);
        $usuarios = $usuarioModel->buscarUsuarios();
        include __DIR__ . '/../Views/instrutor/avaliacaoEscolher.php';
    }
    public function avaliacaoNova($id = null)
    {
        if (!$id) {
            echo "ID inválido!";
            return;
        }

        $usuarioModel = new UsuarioModel($this->conn);
        $usuario = $usuarioModel->buscarUsuarioPorId($id);

        if (!$usuario) {
            echo "Usuário não encontrado!";
            return;
        }

        require __DIR__ . '/../Views/instrutor/avaliacaoNova.php';
    }
    // public function avaliacaoVer($id)
    // {
    //     $model = new AvaliacaoModel($this->conn);
    //     $avaliacao = $model->buscarPorId($id);

    //     if (!$avaliacao) {
    //         echo "Avaliação não encontrada!";
    //         return;
    //     }

    //     // Ajuste: o nome sempre ficará em $avaliacao['nome']
    //     if (!isset($avaliacao['nome']) && isset($avaliacao['nome_usuario'])) {
    //         $avaliacao['nome'] = $avaliacao['nome_usuario'];
    //     }

    //     include __DIR__ . '/../Views/Instrutor/avaliacaoVer.php';
    // }
public function avaliacaoVer($idRota = null)
{
    // Aceita id tanto por rota quanto por ?id=
    $id = $idRota ?? ($_GET['id'] ?? null);

    if (!$id) {
        header("Location: /ACADEMY/public/instrutor/avaliacoes");
        exit;
    }

    require_once __DIR__ . '/../Models/AvaliacaoModel.php';
    $avaliacaoModel = new AvaliacaoModel($this->conn);

    $avaliacao = $avaliacaoModel->buscarPorId($id);

    if (!$avaliacao) {
        header("Location: /ACADEMY/public/instrutor/avaliacoes");
        exit;
    }

    include __DIR__ . '/../Views/instrutor/avaliacaoVer.php';
}


    public function avaliacaoExcluir($id)
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        // somente instrutor pode excluir
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $id = (int) $id;
        if ($id <= 0) {
            $_SESSION['msg_erro'] = "ID inválido.";
            header("Location: /ACADEMY/public/instrutor/avaliacoesSalvas");
            exit;
        }

        require_once __DIR__ . '/../Models/AvaliacaoModel.php';
        $model = new AvaliacaoModel($this->conn);

        $ok = $model->excluir($id);

        if ($ok) {
            $_SESSION['msg_sucesso'] = "Avaliação excluída com sucesso.";
        } else {
            $_SESSION['msg_erro'] = "Erro ao excluir avaliação. Verifique logs.";
        }

        header("Location: /ACADEMY/public/instrutor/avaliacoesSalvas");
        exit;
    }

    public function avaliacaoPdf($id)
    {
        $model = new AvaliacaoModel($this->conn);
        $avaliacao = $model->buscarPorId($id);

        if (!$avaliacao) {
            echo "Avaliação não encontrada!";
            return;
        }

        include __DIR__ . '/../Views/Instrutor/avaliacaoPdf.php';
    }
   

    public function avaliacaoEditar($id)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../Models/AvaliacaoModel.php';
    $model = new AvaliacaoModel($this->conn);

    // 🔹 SE FOR POST → SALVA
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $dados = $_POST;

        try {
            $ok = $model->atualizar($id, $dados);

            if ($ok) {
                $_SESSION['msg_sucesso'] = "Avaliação atualizada com sucesso!";
            } else {
                $_SESSION['msg_erro'] = "Erro ao atualizar avaliação!";
            }

            header("Location: /ACADEMY/public/instrutor/avaliacaoVer/$id");
            exit;

        } catch (Throwable $e) {
            $_SESSION['msg_erro'] = "Erro: " . $e->getMessage();
            header("Location: /ACADEMY/public/instrutor/avaliacaoVer/$id");
            exit;
        }
    }

    // 🔹 SE FOR GET → MOSTRA FORMULÁRIO
    $avaliacao = $model->buscarPorId($id);

    if (!$avaliacao) {
        echo "Avaliação não encontrada!";
        return;
    }

    require __DIR__ . '/../Views/Instrutor/avaliacaoEditar.php';
}

    //     public function avaliacaoAtualizar($id)
//     {
//         if (session_status() === PHP_SESSION_NONE)
//             session_start();

    //         require_once __DIR__ . '/../Models/AvaliacaoModel.php';
//         $model = new AvaliacaoModel($this->conn);

    //         $dados = $_POST;

    //         $ok = $model->atualizar($id, $dados);

    //         if ($ok) {
//             $_SESSION['msg_sucesso'] = "Avaliação atualizada com sucesso!";
//         } else {
//             $_SESSION['msg_erro'] = "Erro ao atualizar!";
//         }

    //         header("Location: /ACADEMY/public/instrutor/avaliacaoVer/$id");
//         exit;
//     }

    //   // ... outros métodos ...
}