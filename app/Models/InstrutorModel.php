<?php

class InstrutorModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getTreinosEnviadosPorInstrutorPaginado($instrutorId, $limit, $offset)
{
    // 1️⃣ Buscar treinos com LIMIT e paginação
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
            ORDER BY t.criado_em DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':iid', $instrutorId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2️⃣ Buscar exercícios de cada treino
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
}
