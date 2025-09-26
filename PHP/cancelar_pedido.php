<?php
session_start();
require 'ADMIN/db.php';  // conexão com o banco

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?msg=login_obrigatorio");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $pedido_id = $_POST['pedido_id'];

    // Verifica se o pedido pertence ao usuário e está em processamento
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

    // Atualiza o status para Cancelado
    $stmt = $pdo->prepare("UPDATE Pedido SET status = 'Cancelado' WHERE id = ?");
    $stmt->execute([$pedido_id]);

    header("Location: pedidos.php?msg=pedido_cancelado");
    exit();
} else {
    header("Location: pedidos.php?msg=requisicao_invalida");
    exit();
}
