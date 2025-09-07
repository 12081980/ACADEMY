<?php
class UsuarioModel
{
    private $conn;

    public function __construct()
    {
        require __DIR__ . '/../../config/conn.php';
        $this->conn = $conn;
    }



    public function getUsuarioPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTreinosUsuario($id)
    {
        $sql = "SELECT * FROM treinos WHERE usuario_id = :id ORDER BY data DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
