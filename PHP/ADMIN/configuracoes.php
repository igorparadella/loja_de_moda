<?php
require_once 'db.php';

// Atualiza configurações se enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campos = ['nome_loja', 'email_admin', 'modo_manutencao'];
    foreach ($campos as $chave) {
        $valor = $_POST[$chave] ?? '';
        $stmt = $pdo->prepare("REPLACE INTO Configuracao (chave, valor) VALUES (:chave, :valor)");
        $stmt->execute([
            ':chave' => $chave,
            ':valor' => $valor
        ]);
    }
    $mensagem = "Configurações atualizadas com sucesso!";
}

// Pega configurações existentes
$stmt = $pdo->query("SELECT chave, valor FROM Configuracao");
$configs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Função auxiliar
function valor($configs, $chave, $default = '') {
    return htmlspecialchars($configs[$chave] ?? $default);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Configurações - Moda Top</title>
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
    <a href="configuracoes.php">⚙️ Configurações</a>
</div>

<!-- Conteúdo principal -->
<div class="main">
    <h2>Configurações</h2>
    <p class="text-muted">Altere as configurações gerais do sistema</p>

    <?php if (!empty($mensagem)): ?>
        <div class="alert alert-success"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nome_loja" class="form-label">Nome da Loja</label>
            <input type="text" class="form-control" id="nome_loja" name="nome_loja" value="<?php echo valor($configs, 'nome_loja'); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email_admin" class="form-label">Email Administrativo</label>
            <input type="email" class="form-control" id="email_admin" name="email_admin" value="<?php echo valor($configs, 'email_admin'); ?>" required>
        </div>
        <div class="mb-3">
            <label for="modo_manutencao" class="form-label">Modo Manutenção</label>
            <select class="form-select" id="modo_manutencao" name="modo_manutencao">
                <option value="0" <?php echo (valor($configs, 'modo_manutencao') == '0') ? 'selected' : ''; ?>>Desativado</option>
                <option value="1" <?php echo (valor($configs, 'modo_manutencao') == '1') ? 'selected' : ''; ?>>Ativado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Configurações</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
