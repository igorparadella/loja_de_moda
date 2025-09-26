<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

// Filtros vindos do formulário
$status = $_GET['status'] ?? '';
$pagamento = $_GET['pagamento'] ?? '';

// Query base
$sql = "
    SELECT 
        p.id AS pedido_id,
        u.nome AS usuario_nome,
        p.total,
        p.status,
        p.data,
        p.formaPagamento,
        p.enderecoEntrega
    FROM Pedido p
    JOIN Usuario u ON p.idUsuario = u.id
    WHERE 1=1
";

// Aplicar filtros dinamicamente
$params = [];

if ($status !== '') {
    $sql .= " AND p.status = :status";
    $params['status'] = $status;
}

if ($pagamento !== '') {
    $sql .= " AND p.formaPagamento = :pagamento";
    $params['pagamento'] = $pagamento;
}

$sql .= " ORDER BY p.data DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Função para buscar os produtos de um pedido
function getProdutosPedido($pdo, $pedido_id) {
    $sql = "
        SELECT pr.nome, pr.preco, pp.quantidade
        FROM Pedido_Produto pp
        JOIN Produto pr ON pp.produto_id = pr.id
        WHERE pp.pedido_id = :pedido_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pedido_id' => $pedido_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Moda Top</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main {
            flex-grow: 1;
            padding: 30px;
            background-color: #f8f9fa;
        }
        .pedido-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center mb-4">Moda Top Admin</h4>
    <a href="admin.php">📊 Dashboard</a>
    <a href="produtos.php">🛍️ Produtos</a>
    <a href="categorias.php">📁 Categorias</a>
    <a href="usuarios.php">👥 Usuários</a>
    <a href="pedidos.php">📦 Pedidos</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
</div>

<!-- Conteúdo principal -->
<div class="main">
    <h2>Pedidos</h2>
    <p class="text-muted">Lista de pedidos realizados</p>

    <form method="GET" class="d-flex gap-3 mb-4 flex-wrap">
    <div style="flex: 1 1 200px;">
        <label for="status" class="form-label mb-1">Status do Pedido</label>
        <select name="status" id="status" class="form-select form-select-sm">
            <option value="">Todos</option>
            <option value="Em processamento" <?= $status === 'Em processamento' ? 'selected' : '' ?>>Em processamento</option>
            <option value="A Caminho" <?= $status === 'A Caminho' ? 'selected' : '' ?>>A Caminho</option>
            <option value="Entregue" <?= $status === 'Entregue' ? 'selected' : '' ?>>Entregue</option>
            <option value="Cancelado" <?= $status === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
        </select>
    </div>

    <div style="flex: 1 1 200px;">
        <label for="pagamento" class="form-label mb-1">Forma de Pagamento</label>
        <select name="pagamento" id="pagamento" class="form-select form-select-sm">
            <option value="">Todas</option>
            <option value="Cartão de Crédito" <?= $pagamento === 'Cartão de Crédito' ? 'selected' : '' ?>>Cartão de Crédito</option>
            <option value="Boleto" <?= $pagamento === 'Boleto' ? 'selected' : '' ?>>Boleto</option>
            <option value="Pix" <?= $pagamento === 'Pix' ? 'selected' : '' ?>>Pix</option>
        </select>
    </div>

    <div style="flex: 0 0 120px; align-self: flex-end;">
        <button type="submit" class="btn btn-primary w-100 btn-sm">Filtrar</button>
    </div>
    
    <div style="flex: 0 0 120px; align-self: flex-end;">
        <a href="pedidos.php" class="btn btn-secondary w-100 btn-sm">Limpar Filtros</a>
    </div>
</form>



    <?php if ($pedidos): ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div class="card pedido-card">
                <div class="card-header bg-dark text-white">
                    Pedido #<?php echo $pedido['pedido_id']; ?> — <?php echo date('d/m/Y', strtotime($pedido['data'])); ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Cliente: <?php echo htmlspecialchars($pedido['usuario_nome']); ?></h5>
                    <p class="card-text">
                        <strong>Status:</strong> <?php echo htmlspecialchars($pedido['status']); ?><br>
                        <strong>Total:</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?><br>
                        <strong>Pagamento:</strong> <?php echo htmlspecialchars($pedido['formaPagamento']); ?><br>
                        <strong>Entrega:</strong> <?php echo htmlspecialchars($pedido['enderecoEntrega']); ?>
                    </p>

                    <!-- Produtos do pedido -->
                    <h6>Itens do Pedido:</h6>
                    <ul class="list-group">
                        <?php 
                        $produtos = getProdutosPedido($pdo, $pedido['pedido_id']);
                        foreach ($produtos as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($item['nome']); ?> (<?php echo $item['quantidade']; ?>x)
                                <span class="badge bg-primary rounded-pill">
                                    R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum pedido encontrado.</p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
