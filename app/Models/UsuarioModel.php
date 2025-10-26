<?php
class UsuarioModel
{
    private $conn;
    private $table = 'usuario'; // nome da tabela no seu banco

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Buscar todos os usuários
     */
    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar um usuário pelo ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Criar novo usuário
     */
    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} 
            (nome, email, senha, telefone, data_nascimento, genero, plano, objetivo, endereco, tipo)
            VALUES 
            (:nome, :email, :senha, :telefone, :data_nascimento, :genero, :plano, :objetivo, :endereco, :tipo)
        ");

        $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);

        return $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':senha' => $senhaHash,
            ':telefone' => $data['telefone'] ?? null,
            ':data_nascimento' => $data['data_nascimento'] ?? null,
            ':genero' => $data['genero'] ?? null,
            ':plano' => $data['plano'] ?? null,
            ':objetivo' => $data['objetivo'] ?? null,
            ':endereco' => $data['endereco'] ?? null,
            ':tipo' => $data['tipo'] ?? 'usuario'
        ]);
    }

    /**
     * Atualizar dados de um usuário
     */
    public function update($id, $data)
    {
        $sql = "
            UPDATE {$this->table}
            SET nome = :nome,
                email = :email,
                telefone = :telefone,
                data_nascimento = :data_nascimento,
                genero = :genero,
                plano = :plano,
                objetivo = :objetivo,
                endereco = :endereco,
                tipo = :tipo
        ";

        if (!empty($data['senha'])) {
            $sql .= ", senha = :senha";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $params = [
            ':id' => $id,
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':telefone' => $data['telefone'] ?? null,
            ':data_nascimento' => $data['data_nascimento'] ?? null,
            ':genero' => $data['genero'] ?? null,
            ':plano' => $data['plano'] ?? null,
            ':objetivo' => $data['objetivo'] ?? null,
            ':endereco' => $data['endereco'] ?? null,
            ':tipo' => $data['tipo'] ?? 'usuario'
        ];

        if (!empty($data['senha'])) {
            $params[':senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        return $stmt->execute($params);
    }

    /**
     * Excluir usuário pelo ID
     */
    public function excluirUsuario($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Autenticar usuário (login)
     */
    public function autenticar($email, $senha)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }

        return false;
    }

    /**
     * Verificar se o e-mail já está cadastrado
     */
    public function emailExiste($email)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    public function listarUsuarios()
    {
        $sql = "SELECT id, nome, email FROM usuario WHERE tipo = 'usuario'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarTodosUsuarios()
    {
        $sql = "SELECT id, nome, email FROM usuario WHERE tipo = ''";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarTodos()
    {
        $stmt = $this->conn->prepare("SELECT id, nome, email, tipo FROM usuario ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


