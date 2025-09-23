<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

// Verifica se o ID foi passado
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Opcional: Verifica se o produto existe antes de excluir
    $stmt = $pdo->prepare("SELECT * FROM Categoria WHERE id = ?");
    $stmt->execute([$id]);
    $Categoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($Categoria) {
        // Exclui o produto
        $stmt = $pdo->prepare("DELETE FROM Categoria WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Redireciona de volta para a lista de produtos
header("Location: categorias.php");
exit;