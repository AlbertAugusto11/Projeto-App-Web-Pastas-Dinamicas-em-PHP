<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function hasUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM users");
        $row = $stmt->fetch();
        return $row['total'] > 0;
    }

    public function register($matricula, $nome, $senha, $is_admin = false) {
        $matricula = htmlspecialchars(trim($matricula));
        $nome      = htmlspecialchars(trim($nome));
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO users (matricula, nome, senha, is_admin) 
            VALUES (:matricula, :nome, :senha, :is_admin)
        ");
        $stmt->bindValue(':matricula', $matricula, PDO::PARAM_STR);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindValue(':senha', $senhaHash, PDO::PARAM_STR);
        $stmt->bindValue(':is_admin', $is_admin, PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    public function login($matricula, $senha) {
        $matricula = htmlspecialchars(trim($matricula));
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE matricula = :matricula");
        $stmt->execute([':matricula' => $matricula]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            return $user;
        }
        return false;
    }

    public function listUsers() {
        $stmt = $this->pdo->query("SELECT matricula, nome, is_admin FROM users ORDER BY matricula");
        return $stmt->fetchAll();
    }

    public function deleteUser($matricula) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE matricula = :matricula");
        return $stmt->execute([':matricula' => $matricula]);
    }

    public function resetPassword($matricula, $novaSenha = "123456") {
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET senha = :senha WHERE matricula = :matricula");
        return $stmt->execute([
            ':matricula' => $matricula,
            ':senha'     => $senhaHash
        ]);
    }
}
?>
