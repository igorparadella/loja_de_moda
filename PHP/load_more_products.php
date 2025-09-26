<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'ADMIN/db.php';

// Recebe parâmetros GET
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 30;

$sql = "SELECT p.id, p.nome, p.preco, p.imagem, c.nome AS categoria
        FROM Produto p
        INNER JOIN Categoria c ON p.categoria_id = c.id
        WHERE 1=1";

$params = [];

// Mesmos filtros que você já usa:
if (!empty($_GET['nome'])) {
    $sql .= " AND p.nome LIKE :nome";
    $params[':nome'] = '%' . $_GET['nome'] . '%';
}

if (!empty($_GET['categoria'])) {
  $sql .= " AND c.id = :categoria";
  $params[':categoria'] = $_GET['categoria'];
}

if (!empty($_GET['tamanho'])) {
    $sql .= " AND p.tamanho = :tamanho";
    $params[':tamanho'] = $_GET['tamanho'];
}

if (!empty($_GET['preco'])) {
    $sql .= " AND p.preco <= :preco";
    $params[':preco'] = $_GET['preco'];
}

$sql .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

// Bind dos parâmetros dinâmicos
foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}

// Bind dos limit e offset
$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

$stmt->execute();

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($produtos);
