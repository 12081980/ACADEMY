<?php
// config/conn.php

$host = '127.0.0.1'; // ou 'localhost'
$db = 'academy';   // Nome exato do banco no phpMyAdmin
$user = 'root';      // No XAMPP padrão é root
$pass = '';          // No XAMPP padrão não tem senha
$charset = 'utf8mb4';
$port = 3306;        // Porta padrão do MySQL no XAMPP

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

try {
    $GLOBALS['conn'] = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
