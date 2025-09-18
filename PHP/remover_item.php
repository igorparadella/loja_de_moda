<?php
session_start();
require 'ADMIN/db.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

$item_id = $_GET['id'] ?? null;

if ($item_id) {
    $stmt = $pdo->prepare("DELETE FROM Carrinho_Item WHERE id = ?");
    $stmt->execute([$item_id]);
}

header("Location: carrinho.php");
exit;
