<?php
require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/TreinoModel.php';
require_once __DIR__ . '/../Models/ExercicioModel.php';

class TreinosController
{
    private $conn;
    private $treinoModel;
    private $exercicioModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->treinoModel = new TreinoModel($conn);
        $this->exercicioModel = new ExercicioModel($conn);
    }

    // ✅ Listar treinos realizados
    public function realizados()
    {
        session_start();
        $usuarioId = $_SESSION['usuario']['id'] ?? null;
        if (!$usuarioId) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $treinos = $this->treinoModel->listarRealizados($usuarioId);
        include __DIR__ . '/../views/treinos/realizados.php';
    }

    // ✅ Mostrar treino em andamento
    public function em_andamento()
    {
        session_start();
        $usuarioId = $_SESSION['usuario']['id'] ?? null;
        if (!$usuarioId) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $treino = $this->treinoModel->treinoEmAndamento($usuarioId);
        $exercicios = [];

        if ($treino) {
            $exercicios = $this->exercicioModel->listarPorTreino($treino['id']);
        }

        include __DIR__ . '/../views/treinos/em_andamento.php';
    }

    // ✅ Iniciar treino (salva em `treino` e `treino_exercicio`)
    public function iniciar()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido.']);
            return;
        }

        try {
            $usuarioId = $_SESSION['usuario']['id'] ?? null;
            if (!$usuarioId) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
                return;
            }

            $nome = $_POST['nome'] ?? 'Treino sem nome';
            $descricao = $_POST['descricao'] ?? '';
            $exercicios = json_decode($_POST['exercicios'] ?? '[]', true);

            if (empty($exercicios)) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Nenhum exercício informado.']);
                return;
            }

            $this->conn->beginTransaction();

            // 🔹 Insere o treino principal
            $stmt = $this->conn->prepare("
                INSERT INTO treino (usuario_id, nome, tipo, descricao, status, data_inicio)
                VALUES (:usuario_id, :nome, :tipo, :descricao, 'em_andamento', NOW())
            ");
            $stmt->execute([
                ':usuario_id' => $usuarioId,
                ':nome' => $nome,
                ':tipo' => 'Personalizado',
                ':descricao' => $descricao
            ]);

            $treinoId = $this->conn->lastInsertId();

            // 🔹 Insere os exercícios do treino
            $stmtExercicio = $this->conn->prepare("
                INSERT INTO treino_exercicio (treino_id, exercicio_id, series, repeticoes, carga)
                VALUES (:treino_id, :exercicio_id, :series, :repeticoes, :carga)
            ");

            foreach ($exercicios as $ex) {
                // Caso o exercício ainda não exista, cria-o
                $exercicioNome = trim($ex['nome']);
                if (empty($exercicioNome))
                    continue;

                $exercicioId = $this->exercicioModel->obterOuCriar($exercicioNome);

                $stmtExercicio->execute([
                    ':treino_id' => $treinoId,
                    ':exercicio_id' => $exercicioId,
                    ':series' => $ex['series'] ?? 0,
                    ':repeticoes' => $ex['repeticoes'] ?? 0,
                    ':carga' => $ex['peso'] ?? 0
                ]);
            }

            $this->conn->commit();

            echo json_encode([
                'status' => 'sucesso',
                'mensagem' => 'Treino iniciado com sucesso!',
                'redirect' => '/ACADEMY/public/treinos/em_andamento'
            ]);
        } catch (Exception $e) {
            $this->conn->rollBack();
            echo json_encode([
                'status' => 'erro',
                'mensagem' => 'Erro ao salvar treino: ' . $e->getMessage()
            ]);
        }
    }

    // ✅ Finalizar treino
    public function finalizar()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['treino_id'])) {
            header("Location: /ACADEMY/public/treinos/em_andamento");
            exit;
        }

        $treinoId = (int) $_POST['treino_id'];

        $stmt = $this->conn->prepare("
            UPDATE treino 
            SET status = 'finalizado', data_fim = NOW() 
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $treinoId);
        $stmt->execute();

        header("Location: /ACADEMY/public/treinos/realizados");
        exit;
    }

    // ✅ Gráficos / histórico
    public function graficos()
    {
        session_start();
        $usuarioId = $_SESSION['usuario']['id'] ?? null;
        if (!$usuarioId) {
            header("Location: /ACADEMY/public/login");
            exit;
        }

        $treinos = $this->treinoModel->listarTodosPorUsuario($usuarioId);
        include __DIR__ . '/../views/treinos/graficos.php';
    }
}
