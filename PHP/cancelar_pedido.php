<?php
session_start();
require 'ADMIN/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?msg=login_obrigatorio");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $pedido_id = $_POST['pedido_id'];
    $motivo = trim($_POST['motivo'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Verifica se o pedido pertence ao usuário
    $stmt = $pdo->prepare("SELECT status FROM Pedido WHERE id = ? AND idUsuario = ?");
    $stmt->execute([$pedido_id, $usuario_id]);
    $pedido = $stmt->fetch();

    if (!$pedido) {
        header("Location: pedidos.php?msg=pedido_nao_encontrado");
        exit();
    }

    if ($pedido['status'] !== 'Em processamento') {
        header("Location: pedidos.php?msg=nao_pode_cancelar");
        exit();
    }

    // Atualiza o status do pedido
    $stmt = $pdo->prepare("UPDATE Pedido SET status = 'Cancelado' WHERE id = ?");
    $stmt->execute([$pedido_id]);

    // Insere motivo do cancelamento
    $stmt = $pdo->prepare("INSERT INTO cancelamento (pedido_id, usuario_id, motivo, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$pedido_id, $usuario_id, $motivo, $email]);

    header("Location: pedidos.php?msg=pedido_cancelado");
    exit();
}
?>
