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
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM treino WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        return (int) $stmt->fetchColumn();
    }

    public function adicionarExercicioAoTreino($treinoId, $ex)
    {
        // Busca ou cria o exercício
        $stmt = $this->conn->prepare("SELECT id FROM exercicio WHERE nome = ?");
        $stmt->execute([$ex['nome']]);
        $exercicio = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$exercicio) {
            $stmtIns = $this->conn->prepare("INSERT INTO exercicio (nome, grupo_muscular) VALUES (?, ?)");
            $stmtIns->execute([$ex['nome'], $ex['grupo_muscular'] ?? null]);
            $exercicioId = $this->conn->lastInsertId();
        } else {
            $exercicioId = $exercicio['id'];
        }

        // Insere na tabela treino_exercicio
        $stmtInsert = $this->conn->prepare("
            INSERT INTO treino_exercicio (treino_id, nome_exercicio, series, repeticoes, carga)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmtInsert->execute([
            $treinoId,
            $ex['nome'],
            $ex['series'],
            $ex['repeticoes'],
            $ex['carga']
        ]);
    }

    public function getExerciciosDoTreino($treinoId)
    {
        $sql = "SELECT 
                nome_exercicio,
                series,
                repeticoes,
                carga
            FROM treino_exercicio
            WHERE treino_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $treinoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTreinosPorUsuario($usuario_id)
    {
        $sql = "
            SELECT 
                t.id AS treino_id,
                DATE_FORMAT(t.data_fim, '%d/%m/%Y') AS data_treino,
                t.tipo AS tipo_treino,
                COUNT(te.id) AS total_exercicios,
                COALESCE(SUM(te.carga), 0) AS peso_total
            FROM treino t
            LEFT JOIN treino_exercicio te ON te.treino_id = t.id
            WHERE t.usuario_id = :usuario_id AND t.status = 'finalizado'
            GROUP BY t.id, t.data_fim, t.tipo
            ORDER BY t.data_fim DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($treinos as &$treino) {
            $treino['exercicios'] = $this->getExerciciosDoTreino($treino['treino_id']);
        }

        return $treinos;
    }
    public function listarTreinosComExercicios($usuario_id)
    {
        $sql = "SELECT 
                t.id AS treino_id,
                DATE_FORMAT(t.data_treino, '%d/%m/%Y') AS data_treino,
                t.tipo,
                COALESCE(e.nome_exercicio, 'Nenhum exercício registrado') AS nome_exercicio,
                COALESCE(e.series, '-') AS series,
                COALESCE(e.repeticoes, '-') AS repeticoes,
                COALESCE(e.carga, '-') AS carga,
                COUNT(e.id) AS total_exercicios,
                COALESCE(SUM(e.carga), 0) AS peso_total
            FROM treino t
            LEFT JOIN treino_exercicio e ON e.treino_id = t.id
            WHERE t.usuario_id = :usuario_id
            GROUP BY t.id, e.id
            ORDER BY t.data_treino DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTreinosEnviadosPorInstrutor($instrutorId)
    {
        // 1️⃣ Buscar os treinos enviados pelo instrutor
        $sql = "SELECT 
                t.id AS treino_id,
                t.tipo,
                t.status,
                t.data_inicio,
                t.criado_em,
                u.nome AS aluno_nome,
                u.email AS aluno_email
            FROM treino t
            INNER JOIN usuario u ON t.usuario_id = u.id
            WHERE t.instrutor_id = :iid
            ORDER BY t.criado_em DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':iid' => $instrutorId]);
        $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2️⃣ Para cada treino, buscar os exercícios relacionados
        $sqlEx = "SELECT nome_exercicio, series, repeticoes, carga 
              FROM treino_exercicio 
              WHERE treino_id = :tid";
        $stmtEx = $this->conn->prepare($sqlEx);

        foreach ($treinos as &$t) {
            $stmtEx->execute([':tid' => $t['treino_id']]);
            $t['exercicios'] = $stmtEx->fetchAll(PDO::FETCH_ASSOC);
        }

        return $treinos;
    }
    public function getPorId($treinoId)
    {
        $sql = "SELECT 
                t.id,
                t.tipo,
                t.status,
                t.data_inicio,
                t.criado_em,
                u.nome AS aluno_nome
            FROM treino t
            INNER JOIN usuario u ON t.usuario_id = u.id
            WHERE t.id = :id
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $treinoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // public function getExerciciosDoTreino($treinoId)
// {
//     $sql = "SELECT 
//                 nome_exercicio,
//                 series,
//                 repeticoes,
//                 carga
//             FROM treino_exercicio
//             WHERE treino_id = :id";
//     $stmt = $this->conn->prepare($sql);
//     $stmt->execute([':id' => $treinoId]);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

    public function iniciarTreino($treino_id, $usuario_id)
    {
        try {
            // Verifica se o treino pertence ao usuário logado
            $stmt = $this->conn->prepare("
            SELECT id FROM treino 
            WHERE id = :id AND usuario_id = :uid
        ");
            $stmt->execute([
                ':id' => $treino_id,
                ':uid' => $usuario_id
            ]);
            $treino = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$treino) {
                // Nenhum treino encontrado ou não pertence ao usuário
                return false;
            }

            // Atualiza o status do treino e define a data de início
            $stmt = $this->conn->prepare("
            UPDATE treino 
            SET status = 'em_andamento', data_inicio = NOW() 
            WHERE id = :id
        ");
            $ok = $stmt->execute([':id' => $treino_id]);

            return $ok; // true se atualizou, false se falhou

        } catch (PDOException $e) {
            error_log("Erro ao iniciar treino: " . $e->getMessage());
            return false;
        }
    }
    public function getTreinosEmAndamentoPorUsuario($usuario_id)
    {
        $stmt = $this->conn->prepare("
        SELECT * FROM treino
        WHERE usuario_id = :uid AND status = 'em_andamento'
        ORDER BY data_inicio DESC
    ");
        $stmt->execute([':uid' => $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}