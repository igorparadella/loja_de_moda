<?php
require_once 'db.php';

// Verifica se o ID do produto foi passado
if (!isset($_GET['id'])) {
    echo "ID do produto não especificado.";
    exit;
}

$id = (int) $_GET['id'];
$mensagem = '';

// Buscar dados do produto
$stmt = $pdo->prepare("SELECT * FROM Produto WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

// Buscar categorias
$stmt = $pdo->query("SELECT id, nome FROM Categoria");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Atualização via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $categoria_id = $_POST['categoria_id'] ?? null;
    $imagemAtual = $produto['imagem'];

    // Upload da nova imagem (se enviada)
    if (!empty($_FILES['imagem']['name'])) {
        $nomeImagem = uniqid() . '_' . $_FILES['imagem']['name'];
        $caminho = 'uploads/' . $nomeImagem;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
        $imagem = $caminho;
    } else {
        $imagem = $imagemAtual;
    }

    // Atualiza no banco
    $stmt = $pdo->prepare("UPDATE Produto SET nome = ?, preco = ?, imagem = ?, categoria_id = ? WHERE id = ?");
    $stmt->execute([$nome, $preco, $imagem, $categoria_id, $id]);

    $mensagem = "Produto atualizado com sucesso!";
    // Atualiza os dados do produto para o novo valor exibido
    $produto = [
        'nome' => $nome,
        'preco' => $preco,
        'imagem' => $imagem,
        'categoria_id' => $categoria_id
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Editar Produto</h2>
    <a href="admin.php" class="btn btn-secondary btn-sm mb-3">← Voltar para Dashboard</a>

    <?php if ($mensagem): ?>
        <div class="alert alert-success"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm bg-white">
        <div class="mb-3">
            <label class="form-label">Nome do Produto</label>
            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="number" step="0.01" name="preco" class="form-control" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="categoria_id" class="form-select" required>
                <option value="">Selecione</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php if ($produto['categoria_id'] == $cat['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($cat['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagem Atual</label><br>
            <?php if (!empty($produto['imagem'])): ?>
                <img src="../../IMG/uploads/<?php echo $produto['imagem']; ?>" alt="Imagem do Produto" style="max-height: 150px;">
            <?php else: ?>
                <p class="text-muted">Sem imagem</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Nova Imagem (opcional)</label>
            <input type="file" name="imagem" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

</body>
</html>
