<?php
require_once 'db.php';

// Consulta todos os usuários
$sql = "SELECT id, nome, email, genero, telefone, endereco FROM Usuario ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários - Moda Top</title>
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
        .table thead {
            background-color: #343a40;
            color: white;
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
    <a href="configuracoes.php">⚙️ Configurações</a>
</div>

<!-- Conteúdo principal -->
<div class="main">
    <h2>Usuários Cadastrados</h2>
    <p class="text-muted">Lista de usuários do sistema</p>

    <?php if ($usuarios): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Gênero</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th style="width: 120px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['genero']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['endereco']); ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">Editar</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
