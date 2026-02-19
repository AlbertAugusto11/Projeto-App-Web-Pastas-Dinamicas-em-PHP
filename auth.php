<?php
session_start();

// Impede cache do navegador
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Função para verificar se o usuário está logado
function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}

// Função para verificar se o usuário é admin
function requireAdmin() {
    if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
        header("Location: login.php");
        exit;
    }
}
?>
