<?php
require_once __DIR__ . '/../Models/UsuarioModel.php';
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

    /**
     * Lista todos os alunos com seus últimos treinos (se houver)
     */
    public function listaUsuarios()
    {
        // Busca todos os usuários do banco
        $stmt = $this->conn->prepare("
        SELECT id, nome, email
        FROM usuario
        ORDER BY nome ASC
    ");
        $stmt->execute();
        $usuariosDb = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];

        foreach ($usuariosDb as $user) {
            $uid = $user['id'];

            // Inicializa o array do usuário
            $usuarios[$uid] = [
                'nome' => $user['nome'],
                'email' => $user['email'],
                'treinos' => []
            ];

            // Busca treinos do usuário
            $stmtTreinos = $this->conn->prepare("
            SELECT 
                t.id AS treino_id,
                t.nome AS treino_nome,
                t.data_inicio,
                t.data_fim,
                e.nome AS exercicio_nome,
                te.series,
                te.repeticoes,
                te.carga
            FROM treino t
            LEFT JOIN treino_exercicio te ON t.id = te.treino_id
            LEFT JOIN exercicio e ON e.nome = te.nome_exercicio
 = e.id
            WHERE t.usuario_id = :uid
            ORDER BY t.data_inicio DESC
        ");
            $stmtTreinos->execute([':uid' => $uid]);
            $treinosDb = $stmtTreinos->fetchAll(PDO::FETCH_ASSOC);

            // Agrupa treinos e exercícios
            foreach ($treinosDb as $row) {
                $tid = $row['treino_id'];

                if (!isset($usuarios[$uid]['treinos'][$tid])) {
                    $usuarios[$uid]['treinos'][$tid] = [
                        'nome' => $row['treino_nome'],
                        'data_inicio' => $row['data_inicio'],
                        'data_fim' => $row['data_fim'],
                        'exercicios' => []
                    ];
                }

                if ($row['exercicio_nome']) {
                    $usuarios[$uid]['treinos'][$tid]['exercicios'][] = [
                        'nome' => $row['exercicio_nome'],
                        'series' => $row['series'],
                        'repeticoes' => $row['repeticoes'],
                        'carga' => $row['carga']
                    ];
                }
            }
        }

        include __DIR__ . '/../views/admin/lista_usuario.php';
    }

    /**
     * Exclui um usuário (apenas se for aluno)
     *
     * (Removed: use the model-based excluirUsuario method defined later in this class
     * to avoid duplicate method declarations.)
     */
    public function excluirUsuario($id)
    {
        $usuarioModel = new UsuarioModel();
        $usuarioModel->excluirUsuario($id);
    }
    /**
     * Mostra detalhes de um usuário e seus treinos realizados
     */
    public function verUsuario($id)
    {
        $id = (int) $id;

        // Busca dados do usuário
        $stmt = $this->conn->prepare("SELECT id, nome, email FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo "Usuário não encontrado!";
            exit;
        }

        // Busca treinos realizados
        $stmt = $this->conn->prepare("
            SELECT nome_treino, data_realizacao, tipo_treino 
            FROM treinos_realizados 
            WHERE id_usuario = :id 
            ORDER BY data_realizacao DESC
        ");
        $stmt->execute([':id' => $id]);
        $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/admin/ver_usuario.php';
    }

    /**
     * Busca usuários e agrupa seus treinos realizados
     */
    public function buscarUsuariosComTreinosAgrupados()
    {
        $sql = "SELECT id, nome, email FROM usuario WHERE tipo = 'aluno' ORDER BY nome ASC";
        $stmt = $this->conn->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($usuarios as &$usuario) {
            $sqlTreinos = "
                SELECT 
                    nome_treino, 
                    data_fim AS data_treino, 
                    tipo_treino
                FROM treinos_realizados
                WHERE usuario_id = :usuario_id
                ORDER BY data_fim DESC
            ";
            $stmtTreinos = $this->conn->prepare($sqlTreinos);
            $stmtTreinos->bindParam(':usuario_id', $usuario['id'], PDO::PARAM_INT);
            $stmtTreinos->execute();
            $usuario['treinos'] = $stmtTreinos->fetchAll(PDO::FETCH_ASSOC);
        }

        return $usuarios;
    }
    public function dashboard()
    {
        try {
            // Total de usuários cadastrados (tipo aluno)
            $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM usuario WHERE tipo = ''");
            $totalUsuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Total de treinos realizados (finalizados)
            $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM treino WHERE status = 'finalizado'");
            $totalTreinosFinalizados = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Total de treinos em andamento
            $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM treino WHERE status = 'em_andamento'");
            $totalTreinosEmAndamento = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Inclui a view corretamente
            include __DIR__ . '/../views/admin/dashboard.php';
        } catch (PDOException $e) {
            echo "Erro ao carregar o dashboard: " . $e->getMessage();
        }
    }
    public function editarUsuario($id)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->buscarPorId($id);

        if (!$usuario) {
            echo "<p>Usuário não encontrado.</p>";
            return;
        }

        include __DIR__ . '/../Views/admin/editar_usuario.php';
    }

    public function atualizarUsuario($id)
    {
        $usuarioModel = new UsuarioModel();

        $dados = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'tipo' => $_POST['tipo'] ?? 'Usuário'
        ];

        $usuarioModel->atualizar($id, $dados);

        // Redireciona de volta para a lista de usuários
        header("Location: /ACADEMY/public/admin/lista_usuarios");
        exit;
    }


}