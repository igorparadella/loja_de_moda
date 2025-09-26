<?php
session_start();
require 'ADMIN/db.php';
$produtoId = (int) $_POST['produto_id'];


if (!isset($_SESSION['usuario_id'])) {
    header("Location: r1.php?id=" . $produtoId . "&msg=login_obrigatorio");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioId = (int) $_SESSION['usuario_id']; // <-- pega direto da sessão
    $nota = (int) $_POST['nota'];
    $comentario = trim($_POST['comentario']);

    if ($nota < 1 || $nota > 5) {
        die("Nota inválida.");
    }

    $stmt = $pdo->prepare("
        INSERT INTO avaliacao (produtoId, usuarioId, nota, comentario, data_hora)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$produtoId, $usuarioId, $nota, $comentario]);

    header("Location: r1.php?id=" . $produtoId . "&msg=avaliacao_sucesso");
    exit;
}
