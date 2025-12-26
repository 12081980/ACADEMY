<?php
// core/conn.php
$host = 'localhost';
$db = 'academy';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}


// core/conn.php site

// $host = 'sqlXXX.infinityfree.com'; // coloque o host EXATO do painel
// $db = 'if0_40534800_academy';      // seu banco criado no InfinityFree
// $user = 'if0_40534800';            // seu usuário MySQL
// $pass = 'SENHA_QUE_VC_CADASTROU';  // sua senha MySQL
// $charset = 'utf8mb4';

// try {
//     $conn = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Erro na conexão: " . $e->getMessage());
// }
