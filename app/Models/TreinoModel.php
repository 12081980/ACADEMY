<?php
class TreinoModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Buscar todos os treinos realizados por um usuário (sem paginação)
     */
    public function buscarTreinosRealizados($usuario)
    {
        $stmt = $this->conn->prepare("
            SELECT id, nome, data_realizacao, inicio, fim, duracao, exercicios
            FROM treinos_realizados
            WHERE id_usuario = :id_usuario
            ORDER BY data_realizacao DESC
        ");
        $stmt->bindParam(':id_usuario', $usuario, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Salvar treino como finalizado
     */
    public function finalizarTreino($treino)
    {
        if (!isset($treino['id_usuario']) || !isset($treino['exercicios'])) {
            die("Dados incompletos para finalizar o treino.");
        }

        $idUsuario = $treino['id_usuario'];
        $nome = $treino['nome'] ?? 'Desconhecido';
        $inicio = $treino['inicio'] ?? date('Y-m-d H:i:s');
        $fim = date('Y-m-d H:i:s');
        $data = date('Y-m-d');
        $exercicios = json_encode($treino['exercicios']);
        $duracao = (strtotime($fim) - strtotime($inicio)) / 60;

        $sql = "INSERT INTO treinos_realizados 
                (id_usuario, nome, data_realizacao, inicio, fim, duracao, exercicios)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idUsuario, $nome, $data, $inicio, $fim, $duracao, $exercicios]);
    }

    /**
     * Buscar treinos em andamento (na tabela treinos principal)
     */
    public function buscarTreinosEmAndamento($id_usuario)
    {
        $sql = "SELECT * FROM treinos 
                WHERE id_usuario = :id_usuario 
                  AND fim IS NULL 
                ORDER BY inicio DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar evolução do usuário (gráfico)
     */
    public function buscarTreinosPorUsuario($id_usuario)
    {
        $sql = "SELECT data_realizacao, duracao
                FROM treinos_realizados
                WHERE id_usuario = :id_usuario
                ORDER BY data_realizacao";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar todos os treinos cadastrados (admin)
     */
    public function buscarTodosTreinos()
    {
        $sql = "SELECT t.*, u.nome AS aluno_nome 
                FROM treinos t
                JOIN usuarios u ON u.id = t.id_usuario
                ORDER BY t.data DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Listar todos (sem join)
     */
    public function listarTodos()
    {
        $stmt = $this->conn->query("SELECT * FROM treinos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar treinos realizados por usuário (sem paginação)
     */
    public function buscarPorUsuario($id_usuario)
    {
        $stmt = $this->conn->prepare("
            SELECT * 
            FROM treinos_realizados 
            WHERE id_usuario = ? 
            ORDER BY data_realizacao DESC
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Excluir treino realizado
     */
    public function excluir($idTreino, $id_usuario)
    {
        $stmt = $this->conn->prepare("DELETE FROM treinos_realizados WHERE id = ? AND id_usuario = ?");
        return $stmt->execute([$idTreino, $id_usuario]);
    }

    /**
     * Buscar treinos realizados (com paginação)
     */
    public function getTreinosRealizados($usuarioId, $pagina = 1, $limite = 5)
    {
        $offset = ($pagina - 1) * $limite;
        $limite = (int) $limite;
        $offset = (int) $offset;

        $sql = "SELECT id, nome, data_realizacao, inicio, fim, duracao
                FROM treinos_realizados
                WHERE id_usuario = :usuario_id
                ORDER BY data_realizacao DESC
                LIMIT $limite OFFSET $offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar total de treinos realizados
     */
    public function contarTreinosRealizados($usuarioId)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM treinos_realizados 
                WHERE id_usuario = :usuario_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }
}
