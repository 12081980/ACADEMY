<?php
require_once __DIR__ . '/../../core/conn.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';
require_once __DIR__ . '/../Models/AcessoModel.php';


class AuthController
{
    private $conn;

   public function __construct(PDO $conn)
{
    $this->conn = $conn;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}



    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       

            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                $_SESSION['erro_login'] = "Preencha todos os campos!";
                header("Location: /ACADEMY/public");
                exit;
            }

            $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

           if ($usuario && (password_verify($senha, $usuario['senha']) || $senha === $usuario['senha'])) {

    if ($senha === $usuario['senha']) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $up = $this->conn->prepare("UPDATE usuario SET senha = :hash WHERE id = :id");
        $up->execute([':hash' => $hash, ':id' => $usuario['id']]);
    }

    $tipo = $usuario['tipo'] ?? 'usuario';

    $_SESSION['usuario'] = [
        'id' => $usuario['id'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
        'tipo' => $tipo
    ];

    // 🔥 Registrar acesso
    require_once __DIR__ . '/../Models/AcessoModel.php';
    $model = new AcessoModel($this->conn);
    $model->registrar([
        'usuario_id'   => $usuario['id'],
        'nome_usuario' => $usuario['nome'],
        'tipo_usuario' => $tipo,
        'ip'           => $_SERVER['REMOTE_ADDR'],
        'navegador'    => $_SERVER['HTTP_USER_AGENT'],
        'data_acesso'  => date('Y-m-d'),
        'hora_acesso'  => date('H:i:s')
    ]);

    switch ($tipo) {
        case 'admin':
            header("Location: /ACADEMY/public/admin/dashboard");
            break;
        case 'instrutor':
            header("Location: /ACADEMY/public/instrutor/dashboardInstrutor");
            break;
        default:
            header("Location: /ACADEMY/public");
            break;
    }
    exit;
}


        // Se for GET, redireciona para página inicial
        header("Location: /ACADEMY/public");
        exit;
    }

    }

   public function register()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /ACADEMY/public/home");
        exit;
    }

    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $senha    = $_POST['senha'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $cidade   = trim($_POST['cidade'] ?? '');
    $estado   = trim($_POST['estado'] ?? '');
    $bairro   = trim($_POST['bairro'] ?? '');
    $rua      = trim($_POST['rua'] ?? '');
    $numero   = trim($_POST['numero'] ?? '');
    $tipo     = $_POST['tipo'] ?? 'usuario';

    // 🔒 Validação básica
    if (!$nome || !$email || !$senha) {
        $_SESSION['erro_cadastro'] = "Preencha todos os campos obrigatórios!";
        header("Location: /ACADEMY/public/home");
        exit;
    }

    // 🔍 VERIFICAR SE EMAIL JÁ EXISTE
    $check = $this->conn->prepare(
        "SELECT id FROM usuario WHERE email = :email"
    );
    $check->execute([':email' => $email]);

    if ($check->fetch()) {
        $_SESSION['erro_cadastro'] = "Este email já está cadastrado.";
        header("Location: /ACADEMY/public/home");
        exit;
    }

    $hash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $this->conn->prepare("
            INSERT INTO usuario 
            (nome, email, senha, telefone, cidade, estado, bairro, rua, numero, tipo)
            VALUES
            (:nome, :email, :senha, :telefone, :cidade, :estado, :bairro, :rua, :numero, :tipo)
        ");

        $stmt->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':senha'    => $hash,
            ':telefone' => $telefone,
            ':cidade'   => $cidade,
            ':estado'   => $estado,
            ':bairro'   => $bairro,
            ':rua'      => $rua,
            ':numero'   => $numero,
            ':tipo'     => $tipo
        ]);

        $_SESSION['usuario'] = [
            'id' => $this->conn->lastInsertId(),
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'cidade' => $cidade,
            'estado' => $estado,
            'bairro' => $bairro,
            'rua' => $rua,
            'numero' => $numero,
            'tipo' => $tipo
        ];

        $_SESSION['sucesso_cadastro'] = "Cadastro realizado com sucesso!";
        header("Location: /ACADEMY/public/home");
        exit;

    } catch (PDOException $e) {

        // 🧯 Garantia extra (UNIQUE email)
        if ($e->getCode() == 23000) {
            $_SESSION['erro_cadastro'] = "Este email já está cadastrado.";
            header("Location: /ACADEMY/public/home");
            exit;
        }

        throw $e; // outros erros reais
    }
}



    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /ACADEMY/public/home");
        exit;
    }
}

