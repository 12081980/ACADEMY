<?php

class AcessoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
   
public function listar()
{
    $sql = "SELECT 
                l.ip,
                l.navegador,
                DATE(l.data_hora) AS data_acesso,
                TIME(l.data_hora) AS hora_acesso,
                u.nome AS usuario,
                u.tipo
            FROM log_acesso l
            LEFT JOIN usuario u ON u.id = l.usuario_id
            ORDER BY l.data_hora DESC";

    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function registrar($dados)
{
    $sql = "INSERT INTO log_acesso 
        (usuario_id, nome_usuario, tipo_usuario, ip, navegador, data_acesso, hora_acesso)
        VALUES (:usuario_id, :nome_usuario, :tipo_usuario, :ip, :navegador, :data, :hora)";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $dados['usuario_id'],
        ':nome_usuario' => $dados['nome_usuario'],
        ':tipo_usuario' => $dados['tipo_usuario'],
        ':ip' => $dados['ip'],
        ':navegador' => $dados['navegador'],
        ':data' => $dados['data_acesso'],
        ':hora' => $dados['hora_acesso']
    ]);
}


}
