<?php
include 'db.php';

// Pega categorias para preencher o select
$stmt = $pdo->query("SELECT id, nome FROM Categoria");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $imagem = $_POST['imagem'];
    $certificacao = isset($_POST['certificacaoSeguranca']) ? 1 : 0;
    $estoque = $_POST['estoque'];
    $categoria_id = $_POST['categoria_id'];

    $sql = "INSERT INTO Produto (nome, descricao, preco, imagem, certificacaoSeguranca, estoque, categoria_id) 
            VALUES (:nome, :descricao, :preco, :imagem, :certificacao, :estoque, :categoria_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':preco' => $preco,
        ':imagem' => $imagem,
        ':certificacao' => $certificacao,
        ':estoque' => $estoque,
        ':categoria_id' => $categoria_id
    ]);

    echo "<div class='alert alert-success'>Produto cadastrado com sucesso!</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white">
        <h4>Cadastro de Produto</h4>
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="number" step="0.01" name="preco" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Imagem (URL)</label>
            <input type="text" name="imagem" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Estoque</label>
            <input type="number" name="estoque" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="categoria_id" class="form-select" required>
              <option value="">Selecione</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-check mb-3">
            <input type="checkbox" name="certificacaoSeguranca" class="form-check-input" id="certificacao">
            <label class="form-check-label" for="certificacao">Certificação de Segurança</label>
          </div>
          <button type="submit" class="btn btn-success">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
