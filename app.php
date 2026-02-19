<?php

require "auth.php";
requireLogin();

// Usuário logado acessa o App normalmente
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>App Web Pastas Dinâmicas</title>
    <link rel="stylesheet" href="app.css">
</head>

<body>
    <div class="login-box">
        <h1>Bem-vindo ao App</h1>
        <p>Olá, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>!</p>
        <p><a href="appWeb.html">Acessar App Web Paginas Dinamicas</a></p>
        <div class="link">
            <a href="logout.php">Sair</a>
        </div>
    </div>
</body>

</html>