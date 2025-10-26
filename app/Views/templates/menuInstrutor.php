<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <base href="/ACADEMY/">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <div class="container">
    <nav>
        <ul >
            <li><a href="/ACADEMY/public/instrutor/dashboardInstrutor" style="color:white; text-decoration:none;"> 🏠 INÍCIO</a></li>
            <li><a href="/ACADEMY/public/instrutor/enviar" style="color:white; text-decoration:none;"> 📤 ENVIAR TREINO</a></li>
            <li><a href="/ACADEMY/public/instrutor/treinosRealizados" style="color:white; text-decoration:none;">  📋 TREINOS ENVIADOS</a></li>
           
            <li><a href="/ACADEMY/public/auth/logout" style="color:white; text-decoration:none;"> 🚪 SAIR</a></li>
            
   
        </ul>
    </nav>
</header>
<main>
<div class="containerBody">