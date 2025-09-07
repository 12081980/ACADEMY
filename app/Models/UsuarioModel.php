<?php
class UsuarioModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function buscarTodosUsuarios()
    {
        $sql = "SELECT id, nome, email, data_cadastro FROM usuarios WHERE tipo = 'usuario'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT id, nome, email, tipo FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function excluirPorId($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = :id AND tipo = 'usuario'");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function buscarUsuariosPorTipo($tipo)
    {
        $sql = "SELECT id, nome, email FROM usuarios WHERE tipo = :tipo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function criar($nome, $email, $senha, $tipo = 'usuario')
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':tipo' => $tipo
        ]);
    }
    public function cadastrarUsuario($nome, $email, $senha)
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo, data_cadastro) VALUES (:nome, :email, :senha, 'usuario', NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        return $stmt->execute();
    }
    // Buscar todos os usuários com seus treinos
    public function buscarUsuariosComTreinos()
    {
        $sql = "SELECT u.id, u.nome, u.email,
                   t.data_treino AS data_treino,
                   t.tipo_treino AS tipo_treino
            FROM usuarios u
            LEFT JOIN treinos t ON u.id = t.usuario_id
            ORDER BY u.nome ASC, t.data_treino DESC";

        $stmt = $this->conn->query($sql); // usando $this->conn, não $this->conn
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarUsuariosComTreinosRealizados()
    {
        $sql = "SELECT u.id, u.nome, u.email,
                   t.nome AS treino_nome,
                   t.data_fim AS data_treino,
                   t.tipo_treino
            FROM usuarios u
            LEFT JOIN treinos_realizados t 
                   ON u.id = t.usuario_id
            ORDER BY u.nome ASC, t.data_fim DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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


    public function atualizar($id, $nome, $email, $senha = null)
    {
        if ($senha) {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id";
        } else {
            $sql = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($senha) {
            $stmt->bindParam(':senha', $hash);
        }
        return $stmt->execute();
    }
    public function atualizarSenha($id, $senha)
    {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET senha = :senha WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':senha', $hash);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function atualizarCampo($id, $campo, $valor)
    {
        $permitidos = ["nome", "email", "idade", "peso", "cidade", "estado", "rua", "numero", "telefone", "senha"];
        if (!in_array($campo, $permitidos)) {
            return false;
        }

        $sql = "UPDATE usuarios SET $campo = :valor WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":valor", $valor);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function excluirUsuario($id)
    {
        // apaga treinos vinculados antes
        $this->conn->prepare("DELETE FROM treinos WHERE usuario_id = :id")->execute([":id" => $id]);
        // apaga o usuario
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute([":id" => $id]);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update($id, $nome, $email)
    {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}




