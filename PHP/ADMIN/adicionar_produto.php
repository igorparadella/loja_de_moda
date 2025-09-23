<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>


<?php
require_once 'db.php';

// Buscar categorias para o select
$stmt = $pdo->prepare("SELECT id, nome FROM Categoria ORDER BY nome ASC");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mensagem = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $estoque = $_POST['estoque'] ?? 0;
    $categoria_id = $_POST['categoria_id'] ?? '';
    $certificacao = isset($_POST['certificacao']) ? 1 : 0;

    // Pasta para salvar uploads
    $pasta_upload = '../../IMG/uploads/';
    // Criar pasta se não existir
    if (!is_dir($pasta_upload)) {
        mkdir($pasta_upload, 0755, true);
    }

    $nome_arquivo = null;

    // Verifica se um arquivo foi enviado e se não houve erro no upload
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $arquivo_tmp = $_FILES['imagem']['tmp_name'];
        $nome_original = $_FILES['imagem']['name'];

        // Obter extensão
        $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $extensoes_permitidas)) {
            // Gerar nome único para evitar sobrescrever arquivos
            $nome_arquivo = uniqid() . '.' . $extensao;

            // Caminho final
            $destino = $pasta_upload . $nome_arquivo;

            if (!move_uploaded_file($arquivo_tmp, $destino)) {
                $mensagem = "❌ Falha ao mover o arquivo enviado.";
            }
        } else {
            $mensagem = "❌ Tipo de arquivo não permitido. Use jpg, jpeg, png ou gif.";
        }
    }

    // Se não teve mensagem de erro até aqui, continuar inserção
    if (!$mensagem) {
        if ($nome && $preco && $estoque && $categoria_id) {
            $sql = "INSERT INTO Produto (nome, descricao, preco, imagem, certificacaoSeguranca, estoque, categoria_id)
                    VALUES (:nome, :descricao, :preco, :imagem, :certificacao, :estoque, :categoria_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nome' => $nome,
                'descricao' => $descricao,
                'preco' => $preco,
                'imagem' => $nome_arquivo, // salvar o nome do arquivo no banco
                'certificacao' => $certificacao,
                'estoque' => $estoque,
                'categoria_id' => $categoria_id
            ]);
            $mensagem = "✅ Produto cadastrado com sucesso!";
        } else {
            $mensagem = "❌ Preencha todos os campos obrigatórios.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto - Moda Top</title>
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
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
</div>

<!-- Conteúdo principal -->
<div class="main">
    <h2>Adicionar Novo Produto</h2>
    <p class="text-muted">Preencha os dados abaixo para cadastrar um novo produto.</p>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo str_starts_with($mensagem, '✅') ? 'success' : 'danger'; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="adicionar_produto.php" enctype="multipart/form-data">
    <div class="mb-3">
            <label class="form-label">Nome do Produto *</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço (R$) *</label>
            <input type="number" step="0.01" name="preco" class="form-control" required>
        </div>

        <div class="mb-3">
    <label class="form-label">Imagem (Upload)</label>
    <input type="file" name="imagem" class="form-control" accept=".jpg,.jpeg,.png,.gif">
</div>


        <div class="mb-3">
            <label class="form-label">Estoque *</label>
            <input type="number" name="estoque" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoria *</label>
            <select name="categoria_id" class="form-select" required>
                <option value="">Selecione</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nome']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="certificacao" id="certificacao">
            <label class="form-check-label" for="certificacao">
                Possui certificação de segurança
            </label>
        </div>

        <button type="submit" class="btn btn-success">Cadastrar Produto</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
