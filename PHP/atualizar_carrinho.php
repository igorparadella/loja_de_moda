<?php
session_start();
require 'ADMIN/db.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

if (isset($_POST['quantidades'])) {
    foreach ($_POST['quantidades'] as $item_id => $quantidade) {
        $quantidade = max(1, (int)$quantidade);
        $stmt = $pdo->prepare("UPDATE Carrinho_Item SET quantidade = ? WHERE id = ?");
        $stmt->execute([$quantidade, $item_id]);
    }
}

header("Location: carrinho.php");
exit;
