<?php
class NotificacaoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function listarPorUsuario($usuarioId)
    {
        $stmt = $this->conn->prepare("
        SELECT id, mensagem, treino_id, data_envio 
        FROM notificacoes 
        WHERE usuario_id = :uid 
        ORDER BY data_envio DESC
    ");
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarNaoLidas($usuarioId)
    {
        $stmt = $this->conn->prepare("
            SELECT id, mensagem, data_envio 
            FROM notificacoes 
            WHERE usuario_id = :uid AND lida = 0 
            ORDER BY data_envio DESC
        ");
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Marca uma notificação como lida
    public function marcarComoLida($id)
    {
        $stmt = $this->conn->prepare("UPDATE notificacoes SET lida = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    public function criar($usuarioId, $mensagem)
    {
        $sql = "INSERT INTO notificacoes (usuario_id, mensagem, data_envio, lida) VALUES (:usuario_id, :mensagem, NOW(), 0)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'mensagem' => $mensagem
        ]);
    }
    public function enviar($usuarioId, $mensagem, $treinoId = null)
    {
        $sql = "INSERT INTO notificacoes (usuario_id, mensagem, treino_id, data_envio) 
            VALUES (:usuario_id, :mensagem, :treino_id, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId);
        $stmt->bindValue(':mensagem', $mensagem);
        $stmt->bindValue(':treino_id', $treinoId);
        return $stmt->execute();
    }

    public function getPorId($id)
    {
        $sql = "SELECT * FROM notificacoes WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {
        $sql = "DELETE FROM notificacoes WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

}


