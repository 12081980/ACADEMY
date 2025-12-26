<?php
class UsuarioModel
{
    private $conn;
    private $table = 'usuario'; // nome da tabela no banco

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
    // public function getById($id)
    // {
    //     $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
    //     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    /**
     * Criar novo usuário
     */
    public function create($data)
    {
        $stmt = $this->conn->prepare("
        INSERT INTO {$this->table} 
        (nome, email, senha, telefone, cidade, estado, bairro, rua, numero, tipo)
        VALUES 
        (:nome, :email, :senha, :telefone, :cidade, :estado, :bairro, :rua, :numero, :tipo)
    ");

        $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);

        return $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':senha' => $senhaHash,
            ':telefone' => $data['telefone'] ?? null,
            ':cidade' => $data['cidade'] ?? null,
            ':estado' => $data['estado'] ?? null,
            ':bairro' => $data['bairro'] ?? null,
            ':rua' => $data['rua'] ?? null,
            ':numero' => $data['numero'] ?? null,
            ':tipo' => $data['tipo'] ?? 'aluno'
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
            cidade = :cidade,
            estado = :estado,
            bairro = :bairro,
            rua = :rua,
            numero = :numero,
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
            ':cidade' => $data['cidade'] ?? null,
            ':estado' => $data['estado'] ?? null,
            ':bairro' => $data['bairro'] ?? null,
            ':rua' => $data['rua'] ?? null,
            ':numero' => $data['numero'] ?? null,
            ':tipo' => $data['tipo'] ?? 'aluno'
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
        $sql = "SELECT id, nome, email FROM {$this->table} WHERE tipo = 'usuario'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarTodosUsuarios()
    {
        $sql = "SELECT id, nome, email FROM {$this->table} WHERE tipo = ''";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodos()
    {
        $stmt = $this->conn->prepare("SELECT id, nome, email, tipo FROM {$this->table} ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // busca um usuário por id
    public function buscarUsuarioPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // busca todos os alunos (tipo = 'aluno')
 
    public function buscarUsuarios()
    {
        $stmt = $this->conn->prepare("
        SELECT id, nome, email 
        FROM usuario 
        WHERE tipo = '' 
        ORDER BY nome ASC
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public function buscaPorId($id)
{
    $sql = "SELECT * FROM usuario WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function buscarUsuariosPaginados($limit, $offset)
{
    $sql = "SELECT * FROM usuario ORDER BY nome LIMIT :limit OFFSET :offset";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function contarUsuarios()
{
    $sql = "SELECT COUNT(*) FROM usuario";
    return $this->conn->query($sql)->fetchColumn();
}
public function totalUsuarios() {
    $sql = $this->conn->query("SELECT COUNT(*) AS total FROM usuario");
    return $sql->fetch(PDO::FETCH_ASSOC)['total'];
}

public function totalInstrutores() {
    $sql = $this->conn->query("SELECT COUNT(*) AS total FROM usuario WHERE tipo = 'instrutor'");
    return $sql->fetch(PDO::FETCH_ASSOC)['total'];
}
public function buscarPaginado($limite, $offset)
{
    $sql = "SELECT * FROM usuario ORDER BY id DESC LIMIT :limite OFFSET :offset";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function contarUsuario()
{
    return $this->conn->query("SELECT COUNT(*) FROM usuario")->fetchColumn();
}
   
    public function atualizar(int $id, array $dados): bool
    {
        // ajuste o nome da tabela/colunas conforme o seu esquema
        $sql = 'UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id';
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome'  => $dados['nome'] ?? null,
            ':email' => $dados['email'] ?? null,
            ':tipo'  => $dados['tipo'] ?? null,
            ':id'    => $id,
        ]);
    }

    // ...existing code...
}



