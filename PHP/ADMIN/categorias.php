<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>


<?php
require_once 'db.php';

// Consulta todas as categorias
$sql = "SELECT * FROM Categoria ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Categorias - Moda Top</title>
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
    <h2>Gerenciar Categorias</h2>
    <p class="text-muted">Adicione, edite ou exclua categorias</p>

    <div class="mb-3">
        <a href="adicionar_categoria.php" class="btn btn-primary">➕ Adicionar Nova Categoria</a>
    </div>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-light">
            <tr>
                <th>#ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($categorias) > 0): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo $categoria['id']; ?></td>
                        <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                        <td>
                            <a href="editar_categoria.php?id=<?php echo $categoria['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                            <a href="excluir_categoria.php?id=<?php echo $categoria['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">Nenhuma categoria cadastrada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
