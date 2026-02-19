<?php
require 'config.php';
require 'User.php';

$userObj = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'] ?? '';
    $nome      = $_POST['nome'] ?? '';
    $senha     = $_POST['senha'] ?? '';

    $is_admin = !$userObj->hasUsers();

    if ($userObj->register($matricula, $nome, $senha, $is_admin)) {
        $msg = $is_admin ? "Primeiro usuário criado como ADMIN." : "Usuário cadastrado com sucesso!";
    } else {
        $erro = "Erro ao cadastrar usuário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-box">
        <h1>Cadastro</h1>
        <?php if (!empty($erro)) echo "<p class='error'>$erro</p>"; ?>
        <?php if (!empty($msg)) echo "<p class='success'>$msg <a href='login.php'>Fazer login</a></p>"; ?>
        <form method="POST">
            <label>Matrícula:</label>
            <input type="text" name="matricula" required>
            <label>Nome:</label>
            <input type="text" name="nome" required>
            <label>Senha:</label>
            <input type="password" name="senha" required>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>

</html>