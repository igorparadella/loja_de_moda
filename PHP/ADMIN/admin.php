<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>


<?php
require_once 'db.php';

// Consulta os produtos com categoria
$sql = "SELECT p.id, p.nome, p.preco, p.imagem, c.nome AS categoria
        FROM Produto p
        INNER JOIN Categoria c ON p.categoria_id = c.id
        ORDER BY p.id DESC LIMIT 8";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Contadores rápidos
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM Produto")->fetchColumn();
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM Usuario")->fetchColumn();
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM Pedido")->fetchColumn();
$totalCategorias = $pdo->query("SELECT COUNT(*) FROM Categoria")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Moda Top</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: row;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            flex-shrink: 0;
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
        .card-img-top {
            max-height: 150px;
            object-fit: cover;
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
    <h2>Bem-vindo(a) ao Dashboard</h2>
    <p class="text-muted">Visão geral do sistema</p>

    <!-- Resumo rápido -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Produtos</h5>
                    <p class="card-text fs-4"><?php echo $totalProdutos; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Usuários</h5>
                    <p class="card-text fs-4"><?php echo $totalUsuarios; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Pedidos</h5>
                    <p class="card-text fs-4"><?php echo $totalPedidos; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Categorias</h5>
                    <p class="card-text fs-4"><?php echo $totalCategorias; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Produtos recentes -->
    <h4 class="mb-3">Últimos Produtos</h4>
    <div class="row">
        <?php foreach ($produtos as $produto): ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <?php if (!empty($produto['imagem'])): ?>
                        <img src="../../IMG/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" class="card-img-top h-20" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                        <?php else: ?>
                        <img src="https://via.placeholder.com/300x200?text=Sem+Imagem" class="card-img-top" alt="Sem imagem">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($produto['categoria']); ?></p>
                        <p class="card-text text-success fw-bold">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                        <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
