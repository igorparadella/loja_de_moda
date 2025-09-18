<?php
session_start();
require 'ADMIN/db.php';


if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para login.php com mensagem
    header("Location: login.php?msg=login_obrigatorio");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$produto_id = $_POST['produto_id'];
$quantidade = max(1, (int)$_POST['quantidade']);

// 1. Verifica se o usuário já tem um carrinho
$stmt = $pdo->prepare("SELECT id FROM Carrinho WHERE usuario_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$usuario_id]);
$carrinho = $stmt->fetch();

if ($carrinho) {
    $carrinho_id = $carrinho['id'];
} else {
    // Cria novo carrinho
    $stmt = $pdo->prepare("INSERT INTO Carrinho (usuario_id) VALUES (?)");
    $stmt->execute([$usuario_id]);
    $carrinho_id = $pdo->lastInsertId();
}

// 2. Verifica se o produto já está no carrinho
$stmt = $pdo->prepare("SELECT id, quantidade FROM Carrinho_Item WHERE carrinho_id = ? AND produto_id = ?");
$stmt->execute([$carrinho_id, $produto_id]);
$item = $stmt->fetch();

if ($item) {
    // Atualiza a quantidade
    $nova_qtd = $item['quantidade'] + $quantidade;
    $stmt = $pdo->prepare("UPDATE Carrinho_Item SET quantidade = ? WHERE id = ?");
    $stmt->execute([$nova_qtd, $item['id']]);
} else {
    // Insere novo item
    $stmt = $pdo->prepare("INSERT INTO Carrinho_Item (carrinho_id, produto_id, quantidade) VALUES (?, ?, ?)");
    $stmt->execute([$carrinho_id, $produto_id, $quantidade]);
}

header("Location: r1.php?id=$produto_id&msg=car_s");
exit;
