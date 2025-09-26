<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

// Captura filtros
$filtro_nome = $_GET['nome'] ?? '';
$filtro_email = $_GET['email'] ?? '';
$filtro_telefone = $_GET['telefone'] ?? '';
$filtro_genero = $_GET['genero'] ?? '';

// Monta query com filtros
$sql = "SELECT id, nome, email, genero, telefone, endereco FROM Usuario WHERE 1=1";
$params = [];

if ($filtro_nome !== '') {
    $sql .= " AND nome LIKE :nome";
    $params['nome'] = '%' . $filtro_nome . '%';
}

if ($filtro_email !== '') {
    $sql .= " AND email LIKE :email";
    $params['email'] = '%' . $filtro_email . '%';
}

if ($filtro_telefone !== '') {
    $sql .= " AND telefone LIKE :telefone";
    $params['telefone'] = '%' . $filtro_telefone . '%';
}

if ($filtro_genero !== '') {
    $sql .= " AND genero = :genero";
    $params['genero'] = $filtro_genero;
}

$sql .= " ORDER BY nome ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários - Moda Top</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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
    <a href="logout.php">Sair</a>
</div>

<!-- Conteúdo principal -->
<div class="main">
    <h2>Usuários Cadastrados</h2>
    <p class="text-muted">Lista de usuários do sistema</p>

    <form method="GET" class="d-flex align-items-center gap-2 w-100 mb-4" style="flex-wrap: nowrap;">
    <input type="text" name="nome" class="form-control form-control-sm" placeholder="Nome" style="width: 20%;" value="<?= htmlspecialchars($filtro_nome) ?>">
    <input type="text" name="email" class="form-control form-control-sm" placeholder="Email" style="width: 25%;" value="<?= htmlspecialchars($filtro_email) ?>">
    <input type="text" name="telefone" class="form-control form-control-sm" placeholder="Telefone" style="width: 15%;" value="<?= htmlspecialchars($filtro_telefone) ?>">
    <select name="genero" class="form-select form-select-sm" style="width: 15%;">
        <option value="">Gênero</option>
        <option value="Masculino" <?= $filtro_genero === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
        <option value="Feminino" <?= $filtro_genero === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
        <option value="Outro" <?= $filtro_genero === 'Outro' ? 'selected' : '' ?>>Outro</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm" style="width: 10%;">Filtrar</button>
    <a href="usuarios.php" class="btn btn-secondary btn-sm" style="width: 10%;">Limpar Filtros</a>
</form>



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
