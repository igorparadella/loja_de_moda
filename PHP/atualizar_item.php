<?php
session_start();
require 'ADMIN/db.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['item_id']) || !isset($_POST['quantidade'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos']);
    exit;
}

$item_id = (int) $_POST['item_id'];
$quantidade = (int) $_POST['quantidade'];

if ($quantidade < 1) $quantidade = 1;

// Atualiza no banco
$stmt = $pdo->prepare("UPDATE Carrinho_Item SET quantidade = ? WHERE id = ?");
$stmt->execute([$quantidade, $item_id]);

// Busca o novo subtotal do item
$stmt = $pdo->prepare("
    SELECT p.preco, (p.preco * ci.quantidade) AS subtotal 
    FROM Carrinho_Item ci
    JOIN Produto p ON ci.produto_id = p.id
    WHERE ci.id = ?
");
$stmt->execute([$item_id]);
$result = $stmt->fetch();

echo json_encode([
    'subtotal' => number_format($result['subtotal'], 2, ',', '.'),
]);
