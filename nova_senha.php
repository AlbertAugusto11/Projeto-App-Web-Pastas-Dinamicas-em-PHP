<?php
session_start();
require 'config.php';
require 'User.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userObj = new User($pdo);
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = $_POST['novaSenha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($novaSenha === $confirmar && strlen($novaSenha) >= 6) {
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET senha = :senha WHERE matricula = :matricula");
        $stmt->execute([
            ':senha'     => $senhaHash,
            ':matricula' => $user['matricula']
        ]);

        $_SESSION['user']['senha'] = $senhaHash;

        $msg = "Senha atualizada com sucesso! <a href='app.php'>Ir para o App</a>";
    } else {
        $erro = "As senhas não conferem ou são muito curtas (mínimo 6 caracteres).";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Nova Senha - App Web Pastas Dinâmicas</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-box">
        <h1>Nova Senha</h1>
        <?php if (!empty($erro)) echo "<p class='error'>$erro</p>"; ?>
        <?php if (!empty($msg)) echo "<p class='success'>$msg</p>"; ?>
        <form method="POST">
            <label for="novaSenha">Nova senha:</label>
            <input type="password" id="novaSenha" name="novaSenha" required>

            <label for="confirmar">Confirmar senha:</label>
            <input type="password" id="confirmar" name="confirmar" required>

            <button type="submit">Atualizar Senha</button>
        </form>
        <div class="link">
            <a href="login.php">Voltar para Login</a>
        </div>
    </div>
</body>

</html>