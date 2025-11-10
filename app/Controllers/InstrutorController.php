<?php
require_once __DIR__ . '/../Models/UsuarioModel.php';
require_once __DIR__ . '/../Models/ExercicioModel.php';

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

    // public function enviar_treino()
    // {
    //     session_start();

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $usuarioId = $_POST['usuario_id'];
    //         $treinoTipo = $_POST['treino_tipo'];
    //         $exercicios = $_POST['exercicios'];
    //         $instrutorId = $_SESSION['usuario_id'];

    //         $treinoModel = new TreinoModel();
    //         $treinoModel->salvarTreino($instrutorId, $usuarioId, $treinoTipo, $exercicios);

    //         // Envia notificação
    //         $notificacaoModel = new NotificacaoModel();
    //         $mensagem = "Seu instrutor enviou o Treino $treinoTipo. Clique para ver.";
    //         $notificacaoModel->enviar($usuarioId, $mensagem, null);

    //         // Redireciona para a nova página
    //         header("Location: /ACADEMY/public/instrutor/treinos_enviados");
    //         exit;
    //     }
    //}
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

        // Use a conexão já injetada no controller ($this->conn)
        $conn = $this->conn;

        // Passa a conexão para o model
        require_once __DIR__ . '/../Models/TreinoModel.php';
        $treinoModel = new TreinoModel($conn);

        $treinos = $treinoModel->getTreinosEnviadosPorInstrutor($instrutorId);

        require_once __DIR__ . '/../Views/instrutor/treinos_enviados.php';
    }

    // public function enviar_treino()
    // {
    //     session_start();

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $usuarioId = $_POST['usuario_id'];
    //         $treinoTipo = $_POST['treino_tipo'];
    //         $exercicios = $_POST['exercicios'];
    //         $instrutorId = $_SESSION['usuario_id'];

    //         $treinoModel = new TreinoModel();
    //         $treinoId = $treinoModel->salvarTreino($instrutorId, $usuarioId, $treinoTipo, $exercicios);

    //         // Envia notificação
    //         $notificacaoModel = new NotificacaoModel();
    //         $mensagem = "Seu instrutor enviou o Treino $treinoTipo. Clique para ver.";
    //         $notificacaoModel->enviar($usuarioId, $mensagem, $treinoId);

    //         // Redireciona para a nova página
    //         header("Location: /ACADEMY/public/instrutor/treinos_enviados");
    //         exit;
    //     }
    // }
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

    public function listarUsuariosParaAvaliacao()
    {
        session_start();

        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header("Location: /ACADEMY/public/auth/login");
            exit;
        }

        $usuarioModel = new UsuarioModel($this->conn);
        $usuarios = $usuarioModel->buscarTodosUsuarios();

        require_once __DIR__ . '/../Views/instrutor/avaliacoes.php';
    }


    public function avaliacaoEscolher()
    {
        session_start();

        if (!isset($_SESSION['usuario']['id']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header('Location: /ACADEMY/public/auth/login');
            exit;
        }

        // Buscar alunos (usuários do tipo "aluno")
        $stmt = $this->conn->prepare("SELECT id, nome, email FROM usuario WHERE tipo = 'aluno'");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/instrutor/avaliacoes.php';
    }


    public function avaliacaoFicha()
    {
        session_start();

        if (!isset($_SESSION['usuario']['id']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
            header('Location: /ACADEMY/public/auth/login');
            exit;
        }

        if (!isset($_GET['usuario_id'])) {
            header('Location: /ACADEMY/public/instrutor/avaliacaoEscolher');
            exit;
        }

        $usuarioId = (int) $_GET['usuario_id'];

        // Buscar dados do aluno
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $usuarioId]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo "Aluno não encontrado.";
            exit;
        }

        require __DIR__ . '/../Views/instrutor/fichaAvaliacao.php';
    }
    public function salvarAvaliacao()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido']);
            exit;
        }

        $data = $_POST;

        if (empty($data['usuario_id']) || empty($data['data_avaliacao'])) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Campos obrigatórios não preenchidos']);
            exit;
        }

        try {
            $stmt = $this->conn->prepare("
            INSERT INTO avaliacoes_fisicas (
                usuario_id, data_avaliacao, estatura, peso, imc,
                subescapular, triceps, axilar_media, toracica, supra_iliaca, abdominal, coxa,
                percentual_gordura, massa_magra, massa_gorda,
                torax, cintura, abdomen, quadril,
                coxa_direita, coxa_esquerda, perna_direita, perna_esquerda,
                braco_direito, braco_esquerdo, antebraco_direito, antebraco_esquerdo,
                rcq, nivel_atividade, tmb, necessidade_energetica,
                cirurgia, patologia, medicamento, fatores_risco, atividade_atual, rotina, objetivo, observacoes,
                avaliador
            ) VALUES (
                :usuario_id, :data_avaliacao, :estatura, :peso, :imc,
                :subescapular, :triceps, :axilar_media, :toracica, :supra_iliaca, :abdominal, :coxa,
                :percentual_gordura, :massa_magra, :massa_gorda,
                :torax, :cintura, :abdomen, :quadril,
                :coxa_direita, :coxa_esquerda, :perna_direita, :perna_esquerda,
                :braco_direito, :braco_esquerdo, :antebraco_direito, :antebraco_esquerdo,
                :rcq, :nivel_atividade, :tmb, :necessidade_energetica,
                :cirurgia, :patologia, :medicamento, :fatores_risco, :atividade_atual, :rotina, :objetivo, :observacoes,
                :avaliador
            )
        ");

            $stmt->execute([
                ':usuario_id' => $data['usuario_id'],
                ':data_avaliacao' => $data['data_avaliacao'],
                ':estatura' => $data['estatura'] ?? null,
                ':peso' => $data['peso'] ?? null,
                ':imc' => $data['imc'] ?? null,
                ':subescapular' => $data['subescapular'] ?? null,
                ':triceps' => $data['triceps'] ?? null,
                ':axilar_media' => $data['axilar_media'] ?? null,
                ':toracica' => $data['toracica'] ?? null,
                ':supra_iliaca' => $data['supra_iliaca'] ?? null,
                ':abdominal' => $data['abdominal'] ?? null,
                ':coxa' => $data['coxa'] ?? null,
                ':percentual_gordura' => $data['percentual_gordura'] ?? null,
                ':massa_magra' => $data['massa_magra'] ?? null,
                ':massa_gorda' => $data['massa_gorda'] ?? null,
                ':torax' => $data['torax'] ?? null,
                ':cintura' => $data['cintura'] ?? null,
                ':abdomen' => $data['abdomen'] ?? null,
                ':quadril' => $data['quadril'] ?? null,
                ':coxa_direita' => $data['coxa_direita'] ?? null,
                ':coxa_esquerda' => $data['coxa_esquerda'] ?? null,
                ':perna_direita' => $data['perna_direita'] ?? null,
                ':perna_esquerda' => $data['perna_esquerda'] ?? null,
                ':braco_direito' => $data['braco_direito'] ?? null,
                ':braco_esquerdo' => $data['braco_esquerdo'] ?? null,
                ':antebraco_direito' => $data['antebraco_direito'] ?? null,
                ':antebraco_esquerdo' => $data['antebraco_esquerdo'] ?? null,
                ':rcq' => $data['rcq'] ?? null,
                ':nivel_atividade' => $data['nivel_atividade'] ?? null,
                ':tmb' => $data['tmb'] ?? null,
                ':necessidade_energetica' => $data['necessidade_energetica'] ?? null,
                ':cirurgia' => $data['cirurgia'] ?? null,
                ':patologia' => $data['patologia'] ?? null,
                ':medicamento' => $data['medicamento'] ?? null,
                ':fatores_risco' => $data['fatores_risco'] ?? null,
                ':atividade_atual' => $data['atividade_atual'] ?? null,
                ':rotina' => $data['rotina'] ?? null,
                ':objetivo' => $data['objetivo'] ?? null,
                ':observacoes' => $data['observacoes'] ?? null,
                ':avaliador' => $data['avaliador'] ?? null
            ]);

            echo json_encode([
                'status' => 'sucesso',
                'mensagem' => 'Avaliação salva com sucesso!',
                'redirect' => '/ACADEMY/public/instrutor/avaliacaoEscolher'
            ]);

        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'erro',
                'mensagem' => 'Erro ao salvar avaliação: ' . $e->getMessage()
            ]);
        }
    }
}