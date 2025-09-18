<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'ADMIN/db.php'; // Seu arquivo de conexão PDO ou MySQLi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!$email || empty($senha)) {
        $_SESSION['erro'] = "Por favor, preencha email e senha corretamente.";
        header('Location: login.php?msg=log_f');
        exit;
    }

    // Consulta no banco - exemplo usando PDO
    $stmt = $pdo->prepare('SELECT id, email, senha FROM Usuario WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['email'];
            $_SESSION['usuario_id'] = $usuario['id'];
            header('Location: perfil.php?msg=log_s');  // redireciona para perfil.php após login OK
            exit;
        }
    }

    $_SESSION['erro'] = "E-mail ou senha inválidos.";
    header('Location: login.php?msg=log_f');
    exit;
} else {
    header('Location: login.php?msg=log_f');
    exit;
}
