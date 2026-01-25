<?php
class CardioModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function emAndamento($usuarioId)
    {
        $sql = "SELECT id FROM cardio_treinos
                WHERE usuario_id = ? AND status = 'em_andamento'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetch();
    }

    public function criar($dados)
    {
        $sql = "INSERT INTO cardio_treinos
                (usuario_id, tipo, tempo_min, ritmo, data_inicio)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $dados['usuario_id'],
            $dados['tipo'],
            $dados['tempo_min'],
            $dados['ritmo'],
            $dados['data_inicio']
        ]);
    }

    public function atual($usuarioId)
    {
        $sql = "SELECT * FROM cardio_treinos
                WHERE usuario_id = ? AND status = 'em_andamento'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetch();
    }

    public function finalizar($usuarioId)
    {
        $sql = "UPDATE cardio_treinos
                SET status = 'finalizado', data_fim = NOW()
                WHERE usuario_id = ? AND status = 'em_andamento'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuarioId]);
    }

    public function listar($usuarioId)
    {
        $sql = "SELECT * FROM cardio_treinos
                WHERE usuario_id = ?
                ORDER BY data_inicio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }
    public function listarPaginado($usuarioId, $limit, $offset)
{
    $sql = "SELECT * FROM cardio_treinos
            WHERE usuario_id = ?
            ORDER BY data_inicio DESC
            LIMIT ? OFFSET ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(1, $usuarioId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}
public function total($usuarioId)
{
    $stmt = $this->conn->prepare(
        "SELECT COUNT(*) FROM cardio_treinos WHERE usuario_id = ?"
    );
    $stmt->execute([$usuarioId]);
    return $stmt->fetchColumn();
}

}
