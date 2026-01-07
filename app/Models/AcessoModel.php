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
        $stmt = $this->conn->query("
            SELECT 
                la.ip,
                la.navegador,
                la.data_acesso,
                la.hora_acesso,
                u.nome AS nome_usuario,
                u.tipo AS tipo_usuario
            FROM acessos la
            JOIN usuario u ON u.id = la.usuario_id
            ORDER BY la.data_acesso DESC
            LIMIT 200
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   public function registrar(array $dados)
{
    $stmt = $this->conn->prepare("
        INSERT INTO acessos 
        (usuario_id, ip, navegador, data_acesso, hora_acesso)
        VALUES 
        (:usuario_id, :ip, :navegador, CURDATE(), CURTIME())
    ");

    $stmt->execute([
        ':usuario_id' => $dados['usuario_id'],
        ':ip'         => $dados['ip'],
        ':navegador'  => $dados['navegador']
    ]);
}
}