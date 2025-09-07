<?php
class AdminController
{
    private $conn;

    public function __construct($conn = null)
    {
        if ($conn) {
            $this->conn = $conn;
        } else {
            global $conn;
            $this->conn = $conn;
        }
    }


    public function listaUsuarios()
    {
        // Traz dados dos alunos e treinos
        $stmt = $this->conn->prepare("
            SELECT u.id, u.nome, u.email, t.data_treino, t.tipo_treino
            FROM usuarios u
            LEFT JOIN treinos t ON u.id = t.usuario_id
            WHERE u.tipo = 'aluno'
            ORDER BY u.nome,  DESC
        ");
        $stmt->execute();
        $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . "/../views/admin/lista_usuario.php";
    }
    public function excluirUsuario($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = :id AND tipo = 'aluno'");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Usuário excluído com sucesso']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao excluir usuário']);
        }
    }
    public function listaUsuario()
    {
        $stmt = $this->conn->query("
        SELECT u.id, u.nome, u.email, 
               data_treino, descricao AS treino, t.status
        FROM usuarios u
        LEFT JOIN treinos t ON u.id = usuario_id
        ORDER BY u.nome, data_treino DESC
    ");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../Views/admin/lista_usuario.php';
    }
    public function lista_Usuario()
    {
        $usuarios = $this->listaUsuario()->buscarUsuariosComTreinos();

        include __DIR__ . "/../views/admin/lista_usuario.php";
    }
}
class UsuarioModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function buscarUsuariosComTreinosAgrupados()
    {
        // Busca todos os usuários
        $sql = "SELECT id, nome, email FROM usuarios WHERE tipo = 'usuario' ORDER BY nome ASC";
        $stmt = $this->conn->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Para cada usuário, busca os treinos realizados
        foreach ($usuarios as &$usuario) {
            $sqlTreinos = "SELECT nome AS treino_nome, data_fim AS data_treino, tipo_treino
                       FROM treinos_realizados
                       WHERE usuario_id = :usuario_id
                       ORDER BY data_fim ASC";
            $stmtTreinos = $this->conn->prepare($sqlTreinos);
            $stmtTreinos->bindParam(':usuario_id', $usuario['id'], PDO::PARAM_INT);
            $stmtTreinos->execute();
            $usuario['treinos'] = $stmtTreinos->fetchAll(PDO::FETCH_ASSOC);
        }

        return $usuarios;
    }

}


