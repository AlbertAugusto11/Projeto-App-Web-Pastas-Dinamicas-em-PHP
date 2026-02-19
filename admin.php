<?php
require 'config.php';
require 'User.php';
require 'auth.php'; 
requireAdmin();

$userObj = new User($pdo);
$usuarios = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['listar'])) {
        $usuarios = $userObj->listUsers();
    } elseif (isset($_POST['reset'])) {
        $userObj->resetPassword($_POST['matricula']);
        $usuarios = $userObj->listUsers();
    } elseif (isset($_POST['delete'])) {
        $userObj->deleteUser($_POST['matricula']);
        $usuarios = $userObj->listUsers();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Administração - App Web Pastas Dinâmicas</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-box">
        <h1>Área do Administrador</h1>
        <form method="POST">
            <button type="submit" name="listar">Listar Usuários</button>
        </form>
        <div class="link">
            <a href="app.php">Ir para o App</a>
        </div>

        <?php if (!empty($usuarios)): ?>
            <h2>Usuários Cadastrados</h2>
            <table class="user-table">
                <tr>
                    <th>Matrícula</th>
                    <th>Nome</th>
                    <th>Admin</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <form method="POST">
                            <td><?php echo htmlspecialchars($u['matricula']); ?></td>
                            <td><?php echo htmlspecialchars($u['nome']); ?></td>
                            <td><?php echo $u['is_admin'] ? "Sim" : "Não"; ?></td>
                            <td>
                                <input type="hidden" name="matricula" value="<?php echo htmlspecialchars($u['matricula']); ?>">
                                <button type="submit" name="reset">Resetar Senha</button>
                                <button type="submit" name="delete">Excluir</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>