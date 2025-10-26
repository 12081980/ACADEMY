<?php
class ExercicioModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // 🔹 Busca por nome ou cria o exercício
    public function obterOuCriar($nome)
    {
        $stmt = $this->conn->prepare("SELECT id FROM exercicio WHERE nome = :nome");
        $stmt->execute([':nome' => $nome]);
        $exercicio = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exercicio) {
            return $exercicio['id'];
        }

        $stmt = $this->conn->prepare("INSERT INTO exercicio (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
        return $this->conn->lastInsertId();
    }

    // 🔹 Lista os exercícios de um treino
    public function listarPorTreino($treinoId)
    {
        $stmt = $this->conn->prepare("
            SELECT te.*, e.nome AS nome_exercicio
            FROM treino_exercicio te
            JOIN exercicio e ON e.id = te.exercicio_id
            WHERE te.treino_id = :treino_id
        ");
        $stmt->execute([':treino_id' => $treinoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
