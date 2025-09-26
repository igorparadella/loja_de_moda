<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

// Captura os filtros
$filtro_nome = $_GET['nome'] ?? '';
$filtro_categoria = $_GET['categoria'] ?? '';

// Monta a SQL com filtros
$sql = "SELECT p.id, p.nome, p.preco, p.imagem, c.nome AS categoria
        FROM Produto p
        INNER JOIN Categoria c ON p.categoria_id = c.id
        WHERE 1=1";

$params = [];

if ($filtro_nome !== '') {
    $sql .= " AND p.nome LIKE :nome";
    $params['nome'] = '%' . $filtro_nome . '%';
}

if ($filtro_categoria !== '') {
    $sql .= " AND c.id = :categoria";
    $params['categoria'] = $filtro_categoria;
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pega categorias para o select
$stmt = $pdo->query("SELECT id, nome FROM Categoria ORDER BY nome ASC");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Moda Top</title>
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
    <h2>Todos os Produtos</h2>
    <p class="text-muted">Gerencie seus produtos cadastrados</p>

    <form method="GET" class="d-flex gap-2 mb-4 flex-wrap align-items-end">
    <div style="flex: 1 1 200px;">
        <input 
            type="text" 
            name="nome" 
            class="form-control form-control-sm" 
            placeholder="Buscar por nome..." 
            value="<?= htmlspecialchars($filtro_nome) ?>"
        >
    </div>

    <div style="flex: 1 1 200px;">
        <select name="categoria" class="form-select form-select-sm">
            <option value="">Todas as Categorias</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $filtro_categoria == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="flex: 0 0 auto;" class="d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn btn-outline-secondary btn-sm">Limpar filtro</a>
    </div>
</form>



    <div class="mb-3">
        <a href="adicionar_produto.php" class="btn btn-primary">➕ Adicionar Novo Produto</a>
    </div>

    <div class="row">
        <?php if (count($produtos) > 0): ?>
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
                            <a href="excluir_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto cadastrado.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
