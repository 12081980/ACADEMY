class LoginController
{
private $conn;

public function __construct($conn)
{
$this->conn = $conn;
}

public function autenticar()
{
header('Content-Type: application/json; charset=utf-8');

try {
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($senha, $usuario['senha'])) {
session_start();
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['usuario_tipo'] = $usuario['tipo'];

// ✅ Se for admin, já retorna para página de gerenciamento
$redirect = ($usuario['tipo'] === 'admin')
? '/admin/lista_usuario'
: '/home';

echo json_encode([
'status' => 'sucesso',
'redirect' => $redirect
]);
} else {
echo json_encode([
'status' => 'erro',
'mensagem' => 'E-mail ou senha inválidos'
]);
}
} catch (Exception $e) {
echo json_encode([
'status' => 'erro',
'mensagem' => 'Erro inesperado ao tentar fazer login'
]);
}
exit;
}
}