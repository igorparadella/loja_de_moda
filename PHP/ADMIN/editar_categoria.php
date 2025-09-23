<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

// Verifica se o ID da categoria foi passado via GET
if (!isset($_GET['id'])) {
    echo "ID da categoria não especificado.";
    exit;
}

$id = (int) $_GET['id'];
$mensagem = '';

// Busca a categoria pelo ID
$stmt = $pdo->prepare("SELECT * FROM Categoria WHERE id = ?");
$stmt->execute([$id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    echo "Categoria não encontrada.";
    exit;
}

// Se o formulário for enviado via POST, atualiza a categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);

    if (empty($nome)) {
        $mensagem = "O nome da categoria não pode estar vazio.";
    } else {
        $stmt = $pdo->prepare("UPDATE Categoria SET nome = ? WHERE id = ?");
        $stmt->execute([$nome, $id]);
        $mensagem = "Categoria atualizada com sucesso!";
        $categoria['nome'] = $nome; // Atualiza o nome para exibir no formulário
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Editar Categoria</h2>
    <a href="admin.php" class="btn btn-secondary btn-sm mb-3">← Voltar para Dashboard</a>

    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensagem); ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm bg-white">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Categoria</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($categoria['nome']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

</body>
</html>
