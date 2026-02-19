<?php
session_start();
require 'config.php';
require 'User.php';

$userObj = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'] ?? '';
    $senha     = $_POST['senha'] ?? '';

    $user = $userObj->login($matricula, $senha);

    if ($user) {
        $_SESSION['user'] = $user;

        if (password_verify("123456", $user['senha'])) {
            header("Location: nova_senha.php");
            exit;
        }

        if ($user['is_admin']) {
            header("Location: admin.php");
            exit;
        }

        header("Location: app.php");
        exit;
        
    } else {
        $erro = "Matrícula ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login - App Web Pastas Dinâmicas</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-box">
        <h1>Login</h1>
        <?php if (!empty($erro)) echo "<p class='error'>$erro</p>"; ?>
        <form method="POST">
            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Entrar</button>
        </form>
        <div class="link">
            <a href="cadastro.php">Cadastrar novo usuário</a>
        </div>
    </div>
</body>

</html>