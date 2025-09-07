<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

header('Content-Type: text/html; charset=UTF-8');

include __DIR__ . '/../conexao/conn.php';

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (!$nome || !$email || !$senha) {
    exit('Todos os campos são obrigatórios.');
}

// Verifica se e-mail já existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    exit('E-mail já cadastrado. <meta http-equiv="refresh" content="1;url=../index.php">');
}
$stmt->close();

// Cadastra usuário
$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senhaCriptografada);

if ($stmt->execute()) {
    $_SESSION['usuario_id'] = $stmt->insert_id;
    $_SESSION['usuario_nome'] = $nome;

    echo '<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="1;url=../index.php">
    <title>Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <h2>✅ Cadastrado com sucesso!</h2>
    <p>Redirecionando para a página inicial...</p>
</body>
</html>';
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_id'] = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    exit;
} else {
    $stmt->close();
    $conn->close();
    exit('Erro ao cadastrar usuário. Tente novamente mais tarde.');
}
