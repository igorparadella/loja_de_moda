<?php
require_once 'db.php';

// Verifica se o ID foi passado
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Opcional: Verifica se o produto existe antes de excluir
    $stmt = $pdo->prepare("SELECT * FROM Produto WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        // Exclui o produto
        $stmt = $pdo->prepare("DELETE FROM Produto WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Redireciona de volta para a lista de produtos
header("Location: produtos.php");
exit;
