<?php
// Configuração PDO para Postgres
$host = "localhost";
$db   = "postgres";
$user = "postgres";
$pass = "1234567";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erro na conexão: " . htmlspecialchars($e->getMessage()));
}
