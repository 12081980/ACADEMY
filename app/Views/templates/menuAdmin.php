<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <base href="/ACADEMY/public">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
 <header>
    <div class="container">        
        <nav>
            <ul>
                <li><a href="/ACADEMY/public/admin/dashboard">Dashboard</a></li>
                <li><a href="/ACADEMY/public/admin/lista_usuario">Usuários</a></li>
          <li> <a href="/ACADEMY/public/admin/relatoriosAcesso" > Relatórios de Acesso</a></li>
            <li>
                    <div class="botoes">
             <form action="/ACADEMY/public/auth/logout" method="post">
                                <button type="submit">Sair</button>
                            </form>
                    </div>
                </li>
            </ul>
        </nav>
    </header>    
<div class="dashboard-container">