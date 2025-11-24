<?php

class TreinoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
public function criarTreino(array $dados)
{
    // log entrada
    file_put_contents(__DIR__ . "/LOG_MODEL_criar_input.txt", date('Y-m-d H:i:s') . " " . print_r($dados, true) . "\n\n", FILE_APPEND);

    try {
        $this->conn->beginTransaction();

        // Insere treino principal (instrutor_id NULL por padrão)
        $stmt = $this->conn->prepare("
            INSERT INTO treino (usuario_id, instrutor_id, nome, tipo, descricao, status, data_inicio)
            VALUES (:usuario_id, NULL, :nome, :tipo, :descricao, :status, :data_inicio)
        ");

        $stmt->execute([
            ':usuario_id' => $dados['usuario_id'],
            ':nome' => $dados['nome'] ?? 'Treino',
            ':tipo' => $dados['tipo'] ?? ($dados['nome'] ?? 'personalizado'),
            ':descricao' => $dados['descricao'] ?? '',
            ':status' => $dados['status'] ?? 'em_andamento',
            ':data_inicio' => $dados['data_inicio'] ?? date('Y-m-d H:i:s'),
        ]);

        $treino_id = $this->conn->lastInsertId();

        // Inserir exercícios — adaptar diferentes chaves que possam vir do front
        if (!empty($dados['exercicios']) && is_array($dados['exercicios'])) {

            $stmtEx = $this->conn->prepare("
                INSERT INTO treino_exercicio (treino_id, nome_exercicio, series, repeticoes, carga)
                VALUES (:treino_id, :nome_exercicio, :series, :repeticoes, :carga)
            ");

            foreach ($dados['exercicios'] as $ex) {
                // aceita formatos variados
                $nome = $ex['nome'] ?? $ex['exercicio'] ?? $ex['nome_exercicio'] ?? '';
                $series = $ex['series'] ?? $ex['serie'] ?? $ex['series'] ?? 1;
                $repeticoes = $ex['repeticoes'] ?? $ex['reps'] ?? $ex['repeticao'] ?? 1;
                // aceita carga ou peso
                $carga = $ex['carga'] ?? $ex['peso'] ?? $ex['load'] ?? 0;

                // segurança mínima
                $nome = trim($nome);
                $series = intval($series);
                $repeticoes = intval($repeticoes);
                $carga = is_numeric($carga) ? floatval($carga) : 0;

                $stmtEx->execute([
                    ':treino_id' => $treino_id,
                    ':nome_exercicio' => $nome ?: 'Exercício',
                    ':series' => $series,
                    ':repeticoes' => $repeticoes,
                    ':carga' => $carga
                ]);
            }
        }

        $this->conn->commit();

        file_put_contents(__DIR__ . "/LOG_MODEL_criar_success.txt", date('Y-m-d H:i:s') . " criado treino_id={$treino_id}\n", FILE_APPEND);
        return $treino_id;

    } catch (Exception $e) {
        $this->conn->rollBack();
        // log completo para debugar
        file_put_contents(__DIR__ . "/LOG_MODEL_criar_error.txt", date('Y-m-d H:i:s') . " Erro: " . $e->getMessage() . "\nDados: " . print_r($dados, true) . "\nTrace:\n" . $e->getTraceAsString() . "\n\n", FILE_APPEND);
        error_log("Erro criarTreino: " . $e->getMessage());
        return false;
    }
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


    public function iniciarTreino($treino_id, $usuario_id)
    {
        try {
            // Permitir iniciar mesmo que status seja "aguardando", "personalizado", "novo" ou NULL
            $stmt = $this->conn->prepare("
            UPDATE treino
            SET status = 'em_andamento', data_inicio = NOW()
            WHERE id = :id 
              AND usuario_id = :uid
              AND (status IN ('aguardando', 'novo', 'personalizado', 'enviado') 
                   OR status IS NULL 
                   OR status != 'finalizado')
        ");

            $stmt->execute([
                ':id' => $treino_id,
                ':usuario_id' => $usuario_id
            ]);

            // retorna true se alguma linha foi afetada
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro no iniciarTreino: " . $e->getMessage());
            return false;
        }
    }
    public function salvarExerciciosDoTreino($treino_id, $exercicios)
    {
        if (empty($exercicios)) {
            return;
        }

        foreach ($exercicios as $ex) {
            $stmt = $this->conn->prepare("
            INSERT INTO treino_exercicio (treino_id, nome_exercicio, series, repeticoes, carga, grupo_muscular)
            VALUES (:treino_id, :nome, :series, :repeticoes, :carga, :grupo)
        ");

            $stmt->execute([
                ':treino_id' => $treino_id,
               ':nome' => $ex['nome'] ?? $ex['nome_exercicio'] ?? null,

                ':series' => $ex['series'],
                ':repeticoes' => $ex['repeticoes'],
                ':carga' => $ex['carga'],
                ':grupo' => $ex['grupo_muscular'],
            ]);
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
    public function buscarTreinoEmAndamento($usuario_id, $treino_id)
    {
        $sql = "SELECT * FROM treino WHERE usuario_id = :uid AND id = :tid AND status = 'em_andamento'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid' => $usuario_id, ':tid' => $treino_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function listarTreinosRealizados($usuarioId)
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT 
                t.id,
                UPPER(t.tipo) AS tipo,
                COALESCE(DATE_FORMAT(t.data_fim, '%Y-%m-%d'), NULL) AS data_treino
            FROM treino t
            WHERE t.usuario_id = :usuario_id
              AND t.status = 'finalizado'
            ORDER BY t.data_fim DESC
        ");
            $stmt->execute([':usuario_id' => $usuarioId]);
            $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($treinos as &$treino) {
                $stmtEx = $this->conn->prepare("
                SELECT nome_exercicio, series, repeticoes, carga
                FROM treino_exercicio
                WHERE treino_id = :treino_id
            ");
                $stmtEx->execute([':treino_id' => $treino['id']]);
                $exercicios = $stmtEx->fetchAll(PDO::FETCH_ASSOC);

                $treino['exercicios'] = $exercicios;
                $treino['qtd_exercicios'] = count($exercicios);
                $treino['peso_total'] = array_sum(array_column($exercicios, 'carga'));
            }

            return $treinos;

        } catch (PDOException $e) {
            error_log("Erro ao listar treinos realizados: " . $e->getMessage());
            return [];
        }
    }
    // 📄 Buscar treinos finalizados com paginação
    public function listarTreinosRealizadosPaginado($usuarioId, $limit, $offset)
    {
        $stmt = $this->conn->prepare("
        SELECT 
            t.id,
            UPPER(t.tipo) AS tipo,
            DATE_FORMAT(t.data_fim, '%Y-%m-%d') AS data_treino
        FROM treino t
        WHERE t.usuario_id = :usuario_id
          AND t.status = 'finalizado'
        ORDER BY t.data_fim DESC
        LIMIT :limit OFFSET :offset
    ");
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($treinos as &$treino) {
            $stmtEx = $this->conn->prepare("
            SELECT nome_exercicio, series, repeticoes, carga
            FROM treino_exercicio
            WHERE treino_id = :treino_id
        ");
            $stmtEx->execute([':treino_id' => $treino['id']]);
            $exercicios = $stmtEx->fetchAll(PDO::FETCH_ASSOC);

            $treino['exercicios'] = $exercicios;
            $treino['qtd_exercicios'] = count($exercicios);
            $treino['peso_total'] = array_sum(array_column($exercicios, 'carga'));
        }

        return $treinos;
    }

    // 📊 Contar total de treinos finalizados
    public function contarTreinosRealizados($usuarioId)
    {
        $stmt = $this->conn->prepare("
        SELECT COUNT(*) 
        FROM treino 
        WHERE usuario_id = :usuario_id AND status = 'finalizado'
    ");
        $stmt->execute([':usuario_id' => $usuarioId]);
        return (int) $stmt->fetchColumn();
    }
   public function listarTreinosEnviadosPaginado($instrutorId, $limit, $offset)
{
    $stmt = $this->conn->prepare("
        SELECT 
            t.id AS treino_id,
            t.tipo,
            t.criado_em,
            u.nome AS aluno_nome,
            u.email AS aluno_email
        FROM treino t
        INNER JOIN usuario u ON u.id = t.usuario_id
        WHERE t.instrutor_id = :instrutor_id
        ORDER BY t.criado_em DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':instrutor_id', $instrutorId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Buscar exercícios de cada treino
    $stmtEx = $this->conn->prepare("
        SELECT nome_exercicio, series, repeticoes, carga
        FROM treino_exercicio
        WHERE treino_id = :treino_id
    ");

    foreach ($treinos as &$t) {
        $stmtEx->execute([':treino_id' => $t['treino_id']]);
        $t['exercicios'] = $stmtEx->fetchAll(PDO::FETCH_ASSOC);
    }

    return $treinos;
}

 public function contarTreinosEnviados($idInstrutor)
{
    $sql = "SELECT COUNT(*) AS total 
            FROM treino t 
            WHERE t.instrutor_id = :idInstrutor";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':idInstrutor', $idInstrutor, PDO::PARAM_INT);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$resultado['total'];
}


public function getTreinoEmAndamento($usuarioId)
{
    $query = "SELECT * FROM treino 
              WHERE usuario_id = :usuario_id 
              AND status = 'em_andamento'
              ORDER BY data_inicio DESC 
              LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function paginar($usuario_id, $status, $paginaAtual, $limite = 10)
{
    $offset = ($paginaAtual - 1) * $limite;

    // Contar total de registros
    $sqlTotal = "SELECT COUNT(*) AS total FROM treino
                 WHERE usuario_id = :usuario_id AND status = :status";
    $stmtTotal = $this->conn->prepare($sqlTotal);
    $stmtTotal->execute([
        ':usuario_id' => $usuario_id,
        ':status' => $status
    ]);
    $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Buscar registros paginados
    $sql = "SELECT * FROM treinos 
            WHERE usuario_id = :usuario_id AND status = :status
            ORDER BY data_inicio DESC
            LIMIT :offset, :limite";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();

    return [
        'treinos' => $stmt->fetchAll(PDO::FETCH_ASSOC),
        'total' => $total,
        'paginas' => ceil($total / $limite),
        'paginaAtual' => $paginaAtual
    ];
}
// public function contarTreinosEnviados($instrutorId)
// {
//     $stmt = $this->conn->prepare("
//         SELECT COUNT(*) FROM treino WHERE instrutor_id = :instrutor_id
//     ");
//     $stmt->execute([':instrutor_id' => $instrutorId]);
//     return (int) $stmt->fetchColumn();
// }

}