<?php
require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';
require_once __DIR__ . '/../Models/AcessoModel.php'; // ✅ ESSENCIAL

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
    private function verificarAdmin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (
        !isset($_SESSION['usuario']) ||
        ($_SESSION['usuario']['tipo'] ?? '') !== 'admin'
    ) {
        header("Location: /ACADEMY/public/login");
        exit;
    }
}


    /**
     * Lista todos os alunos com seus últimos treinos (se houver)
     */
   
    public function excluirUsuario($id)
    {
         $this->verificarAdmin();
      $usuarioModel = new UsuarioModel($this->conn);

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
         $this->verificarAdmin();
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
     $this->verificarAdmin();
    try {
        // Total de usuários cadastrados (tipo aluno)
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM usuario WHERE tipo = 'usuario'");
        $totalUsuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $model = new UsuarioModel($this->conn);

        $dados = [
            'usuarios_total'     => $model->totalUsuarios(),
            'instrutores_total'  => $model->totalInstrutores()
        ];

        // Inclui a view corretamente
        require __DIR__ . '/../Views/admin/dashboard.php';

    } catch (PDOException $e) {
        echo "Erro ao carregar o dashboard: " . $e->getMessage();
    }
}

    public function editarUsuario($id)
    {
         $this->verificarAdmin();
        $usuarioModel = new UsuarioModel($this->conn);
        $usuario = $usuarioModel->getAll($id);

        if (!$usuario) {
            echo "<p>Usuário não encontrado.</p>";
            return;
        }

        include __DIR__ . '/../Views/admin/editar_usuario.php';
    }

    public function atualizarUsuario($id)
    {
         $this->verificarAdmin();
        $usuarioModel = new UsuarioModel($this->conn);

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


public function listaUsuarios()
{
     $this->verificarAdmin();
   $usuarioModel = new UsuarioModel($this->conn);


    // Paginação
    $paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    $limit = 10;
    $offset = ($paginaAtual - 1) * $limit;

    $usuarios = $usuarioModel->buscarUsuariosPaginados($limit, $offset);
    $totalUsuarios = $usuarioModel->contarUsuarios();
    $totalPaginas = ceil($totalUsuarios / $limit);

    include __DIR__ . '/../Views/admin/lista_usuario.php';
}
public function acesso()
{
     $this->verificarAdmin();
    $model = new AcessoModel($this->conn);
    $lista = $model->listar(); // retorna array
    require __DIR__ . '/../Views/admin/acesso.php';
}

 public function relatoriosAcesso()
    {
        $this->verificarAdmin();

        $model = new AcessoModel($this->conn);
        $lista = $model->listar();

        require __DIR__ . '/../Views/admin/acesso.php';
    }
}
