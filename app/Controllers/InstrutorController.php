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

   
    public function editar_treino()
    {
        session_start();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /ACADEMY/public/instrutor/treinos_enviados");
            exit;
        }

        $treinoModel = new TreinoModel();
        $treino = $treinoModel->getTreinoPorId($id);

        include __DIR__ . '/../Views/instrutor/editar_treino.php';
    }

    public function atualizar_treino()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $tipo = $_POST['tipo'];

            $treinoModel = new TreinoModel();
            $treinoModel->atualizarTreino($id, $tipo);

            header("Location: /ACADEMY/public/instrutor/treinos_enviados");
            exit;
        }
    }

    public function excluir_treino()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $treinoModel = new TreinoModel();
            $treinoModel->excluirTreino($id);
        }
        header("Location: /ACADEMY/public/instrutor/treinos_enviados");
        exit;
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
        ini_set('display_errors', 1);
        error_reporting(E_ALL);


        // garante que a sessão esteja iniciada antes de acessar $_SESSION
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        // usa a conexão injetada no controller
        $avaliacaoModel = new AvaliacaoModel($this->conn);

        // garantir que instrutor_id venha da sessão
        $_POST['instrutor_id'] = $_SESSION['usuario']['id'];

        $avaliacaoModel->salvar($_POST);

        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Avaliação salva com sucesso!',
            'redirect' => '/ACADEMY/public/instrutor/avaliacoesSalvas'
        ]);
        exit;
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
    public function avaliacaoVer($id)
    {
        $model = new AvaliacaoModel($this->conn);
        $avaliacao = $model->buscarPorId($id);

        if (!$avaliacao) {
            echo "Avaliação não encontrada!";
            return;
        }

        // Ajuste: o nome sempre ficará em $avaliacao['nome']
        if (!isset($avaliacao['nome']) && isset($avaliacao['nome_usuario'])) {
            $avaliacao['nome'] = $avaliacao['nome_usuario'];
        }

        include __DIR__ . '/../Views/Instrutor/avaliacaoVer.php';
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
    public function avaliacaoEditarSalvar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Método não permitido.";
            return;
        }

        if (session_status() === PHP_SESSION_NONE)
            session_start();

        require_once __DIR__ . '/../Models/AvaliacaoModel.php';
        $avaliacaoModel = new AvaliacaoModel($this->conn);

        // campos esperados
        $campos = [
            'estatura',
            'peso',
            'imc',
            'subescapular',
            'triceps',
            'axilar_media',
            'toracica',
            'supra_iliaca',
            'abdominal',
            'coxa',
            'percentual_gordura',
            'massa_magra',
            'massa_gorda',
            'torax',
            'cintura',
            'abdomen',
            'quadril',
            'coxa_direita',
            'coxa_esquerda',
            'perna_direita',
            'perna_esquerda',
            'braco_direito',
            'braco_esquerdo',
            'antebraco_direito',
            'antebraco_esquerdo',
            'rcdq',
            'nivel_atividade',
            'tmb',
            'necessidade_energetica',
            'cirurgia',
            'patologia',
            'medicamento',
            'fatores_risco',
            'atividade_atual',
            'rotina',
            'objetivo',
            'observacoes'
        ];

        // Monte o array somente com valores vindos do formulário e normalize
        $dados = [];
        foreach ($campos as $campo) {
            $valor = $_POST[$campo] ?? null;
            // opcional: normalizar números vazios para NULL
            if ($valor === '' || $valor === null) {
                $dados[$campo] = null;
            } else {
                // se for número com vírgula, padroniza para ponto (ex: 1,75 -> 1.75)
                if (is_string($valor) && preg_match('/^\d+,\d+$/', $valor)) {
                    $valor = str_replace(',', '.', $valor);
                }
                $dados[$campo] = $valor;
            }
        }

        try {
            $ok = $avaliacaoModel->atualizar($id, $dados);
        } catch (\Exception $e) {
            // debug temporário: grave em session e redirecione ou exiba
            $_SESSION['msg_erro'] = "Erro ao atualizar avaliação: " . $e->getMessage();
            header("Location: /ACADEMY/public/instrutor/avaliacaoVer/$id");
            exit;
        }

        if ($ok) {
            $_SESSION['msg_sucesso'] = "Avaliação atualizada com sucesso!";
            header("Location: /ACADEMY/public/instrutor/avaliacaoVer/$id");
            exit;
        } else {
            // tentar obter mensagem de erro do model (se implementar)
            $err = method_exists($avaliacaoModel, 'getError') ? $avaliacaoModel->getError() : 'Erro desconhecido';
            $_SESSION['msg_erro'] = "Erro ao atualizar avaliação! $err";
            header("Location: /ACADEMY/public/instrutor/avaliacaoVer/$id");
            exit;
        }
    }



    public function avaliacaoEditar($id)
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        require_once __DIR__ . '/../Models/AvaliacaoModel.php';
        $model = new AvaliacaoModel($this->conn);

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