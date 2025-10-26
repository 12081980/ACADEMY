<?php
class TreinoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function criarTreino($dados)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO treino (usuario_id, nome, tipo, descricao, data_inicio, status)
            VALUES (:usuario_id, :nome, :tipo, :descricao, :data_inicio, :status)
        ");
        $stmt->execute([
            ':usuario_id' => $dados['usuario_id'],
            ':nome' => $dados['nome'],
            ':tipo' => $dados['tipo'],
            ':descricao' => $dados['descricao'],
            ':data_inicio' => $dados['data_inicio'],
            ':status' => $dados['status']
        ]);
        return $this->conn->lastInsertId();
    }



    public function getTreinoEmAndamento($usuarioId)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM treino
            WHERE usuario_id = :usuario_id AND status = 'em_andamento'
            ORDER BY data_inicio DESC LIMIT 1
        ");
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function finalizarTreino($treinoId)
    {
        $stmt = $this->conn->prepare("
            UPDATE treino 
            SET status = 'finalizado', data_fim = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $treinoId]);
    }

    public function getTreinosFinalizadosPaginados($usuarioId, $limite, $offset)
    {
        // Pega os treinos finalizados com limite e offset
        $sql = "
        SELECT 
            t.id,
            t.nome,
            t.tipo,
            t.data_treino,
            t.data_inicio,
            t.data_fim
        FROM treino t
        WHERE t.usuario_id = ? AND t.status = 'finalizado'
        ORDER BY t.data_fim DESC
        LIMIT ? OFFSET ?
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();

        $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Busca os exercícios de cada treino
        foreach ($treinos as &$treino) {
            $sqlEx = "
            SELECT 
                e.nome AS exercicio_nome,
                te.series,
                te.repeticoes,
                te.carga
            FROM treino_exercicio te
            INNER JOIN exercicio e ON e.id = te.exercicio_id
            WHERE te.treino_id = ?
        ";
            $stmtEx = $this->conn->prepare($sqlEx);
            $stmtEx->execute([$treino['id']]);
            $treino['exercicios'] = $stmtEx->fetchAll(PDO::FETCH_ASSOC);
        }

        return $treinos;
    }


    public function getTreinosFinalizados($usuarioId, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM treino WHERE usuario_id = ? AND status = 'finalizado' ORDER BY data_inicio DESC";
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT $offset, $limit";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function contarTreinosFinalizados($usuarioId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM treino WHERE usuario_id = ? AND status = 'finalizado'");
        $stmt->execute([$usuarioId]);
        return (int) $stmt->fetchColumn();
    }
    public function adicionarExercicioAoTreino($treinoId, $ex)
    {
        // Primeiro, tenta buscar o id do exercício pelo nome
        $stmt = $this->conn->prepare("SELECT id FROM exercicio WHERE nome = ?");
        $stmt->execute([$ex['nome']]);
        $exercicio = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$exercicio) {
            // Se não existir, opcional: criar exercício novo
            $stmtIns = $this->conn->prepare("INSERT INTO exercicio (nome) VALUES (?)");
            $stmtIns->execute([$ex['nome']]);
            $exercicioId = $this->conn->lastInsertId();
        } else {
            $exercicioId = $exercicio['id'];
        }

        // Inserir na tabela treino_exercicio
        $stmtInsert = $this->conn->prepare("
        INSERT INTO treino_exercicio (treino_id, exercicio_id, series, repeticoes, carga)
        VALUES (?, ?, ?, ?, ?)
    ");
        $stmtInsert->execute([
            $treinoId,
            $exercicioId,
            $ex['series'],
            $ex['repeticoes'],
            $ex['carga']
        ]);
    }
    public function listarPorUsuario($usuario_id)
    {
        $sql = "SELECT titulo, descricao, data_criacao 
            FROM treinos 
            WHERE usuario_id = ? 
            ORDER BY data_criacao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function salvarTreino($usuario_id, $instrutor_id, $exercicios)
    {
        try {
            $this->conn->beginTransaction();

            // Insere o treino principal
            $sql = "INSERT INTO treinos (usuario_id, instrutor_id, data_envio) VALUES (?, ?, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$usuario_id, $instrutor_id]);

            $treino_id = $this->conn->lastInsertId();

            // Insere cada exercício
            $sqlEx = "INSERT INTO exercicios_treino (treino_id, nome, series, repeticoes, carga) VALUES (?, ?, ?, ?, ?)";
            $stmtEx = $this->conn->prepare($sqlEx);

            foreach ($exercicios as $ex) {
                $stmtEx->execute([
                    $treino_id,
                    $ex['nome'],
                    $ex['series'],
                    $ex['repeticoes'],
                    $ex['carga']
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Erro ao salvar treino: " . $e->getMessage());
            return false;
        }
    }
    public function getTreinosEnviadosPorInstrutor($instrutorId)
    {
        $sql = "SELECT t.id, t.tipo, t.data_envio, u.nome AS aluno_nome, u.email
            FROM treinos t
            JOIN usuarios u ON u.id = t.usuario_id
            WHERE t.instrutor_id = :instrutor_id
            ORDER BY t.data_envio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':instrutor_id', $instrutorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getTreinoPorId($id)
    {
        $sql = "SELECT * FROM treinos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarTreino($id, $tipo)
    {
        $sql = "UPDATE treinos SET tipo = :tipo WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function excluirTreino($id)
    {
        $sql = "DELETE FROM treinos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }


    public function getPorId($treinoId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM treinos WHERE id = :id");
        $stmt->execute([':id' => $treinoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getExerciciosDoTreino($treinoId)
    {
        $stmt = $this->conn->prepare("
        SELECT e.nome, te.series, te.repeticoes, te.carga
        FROM treino_exercicios te
        JOIN exercicios e ON e.id = te.exercicio_id
        WHERE te.treino_id = :tid
    ");
        $stmt->execute([':tid' => $treinoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}





