<?php
session_start();
require 'ADMIN/db.php';  // conexão PDO


$usuario_id = $_SESSION['usuario_id'];

// Busca dados do usuário
$stmt = $pdo->prepare('SELECT nome, email, genero, telefone, endereco FROM Usuario WHERE id = ?');
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se foi passado o produto
if (!isset($_GET['id'])) {
    echo "Produto não especificado.";
    exit;
}

$produto_id = (int) $_GET['id'];

// Busca os dados do produto
$stmt = $pdo->prepare("
    SELECT p.*, c.nome AS categoria_nome
    FROM Produto p
    INNER JOIN Categoria c ON c.id = p.categoria_id
    WHERE p.id = ?
");
$stmt->execute([$produto_id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar produtos relacionados (mesma categoria, exceto o atual)
$stmt = $pdo->prepare("
    SELECT id, nome, preco, imagem 
    FROM Produto 
    WHERE categoria_id = ? AND id != ? 
    ORDER BY RAND() 
    LIMIT 4
");
$stmt->execute([$produto['categoria_id'], $produto_id]);
$relacionados = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (!$produto) {
  header('Location: ERRO/404.php');
  exit;
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($produto['nome']) ?> - ModaTop</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    .product-img {
      max-height: 500px;
      object-fit: cover;
      width: 100%;
    }
    .size-option input[type="radio"] {
      display: none;
    }
    .size-option label {
      border: 1px solid #ccc;
      padding: 10px 15px;
      margin-right: 5px;
      border-radius: 5px;
      cursor: pointer;
    }
    .size-option input[type="radio"]:checked + label {
      background-color: #0d6efd;
      color: white;
      border-color: #0d6efd;
    }
    .card-img-top {
      height: 250px;
      object-fit: cover;
    }
    .card {
      height: 100%;
    }
    .btn-sm {
      width: 100%;
    }
    .favorite-checkbox {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
      background: white;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }
    .favorite-checkbox input[type="checkbox"] {
      display: none;
    }
    .favorite-checkbox label {
      font-size: 20px;
      color: #777777;
    }
    .favorite-checkbox input[type="checkbox"]:checked + label {
      color: red;
    }
    .card-wrapper {
      position: relative;
    }
  </style>

  <link href="../CSS/logo.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <div class="logo">
        <div class="logo-icon">M</div>
        <div class="logo-text">Moda<span class="highlight">Top</span></div>
      </div>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <button id="searchToggle" class="btn btn-outline-light me-2 ms-auto" type="button">
        <i class="bi bi-search"></i>
      </button>

      <form id="searchForm" class="d-flex d-none" role="search" method="GET" action="produtos.php">
        <input class="form-control me-2" type="search" name="nome" placeholder="Buscar produtos..." aria-label="Buscar"
               value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>">
        <button class="btn btn-outline-light" type="submit">
          <i class="bi bi-arrow-right"></i>
        </button>
      </form>

      <ul class="navbar-nav ms-3">
        <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
        <li class="nav-item"><a class="nav-link" href="produtos.php">Produtos</a></li>
        <li class="nav-item"><a class="nav-link" href="carrinho.php">Carrinho</a></li>
        <li class="nav-item"><a class="nav-link" href="sobre.php">Sobre</a></li>
        <li class="nav-item"><a class="nav-link" href="contato.php">Contato</a></li>

        <!-- Dropdown de Login -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Login
          </a>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="loginDropdown">
            <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
            <li><a class="dropdown-item" href="login.php">Logar</a></li>
            <li><a class="dropdown-item" href="cadastro.php">Cadastrar</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  const toggleBtn = document.getElementById('searchToggle');
  const searchContainer = document.getElementById('searchForm');

  toggleBtn.addEventListener('click', () => {
    searchContainer.classList.toggle('d-none');
  });
</script>


<?php
require 'notificacao.php';
?>


<!-- Produto Detalhes -->
<div class="container my-5" style="margin-top: 100px;"><br><br>
  <div class="row g-4">
    <div class="col-md-6">
      <img src="../IMG/uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="product-img rounded shadow">
    </div>

    <div class="col-md-6">
      <h1 class="mb-3"><?= htmlspecialchars($produto['nome']) ?></h1>
      <p class="text-muted">Ref: <?= $produto['id'] ?></p>
      <p class="fs-4 fw-bold text-primary">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
      <p><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>


      <!-- Tamanhos (exemplo fixo, pode ajustar para puxar do banco) -->
      <?php if (
    !empty($produto['categoria_nome']) && 
    !in_array(strtolower($produto['categoria_nome']), ['acessórios', 'joias', 'bolsas'])
): ?>
  <form action="adicionar_carrinho.php" method="POST">
    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
    <div class="mb-4">
      <p class="mb-1">Tamanho:</p>
      <div class="size-option d-flex">
        <input type="radio" name="tamanho" id="sizeP" value="P" required>
        <label for="sizeP">P</label>

        <input type="radio" name="tamanho" id="sizeM" value="M" required>
        <label for="sizeM">M</label>

        <input type="radio" name="tamanho" id="sizeG" value="G" required>
        <label for="sizeG">G</label>

        <input type="radio" name="tamanho" id="sizeGG" value="GG" required>
        <label for="sizeGG">GG</label>
      </div>
    </div>
  </form><?php endif; ?>


        <div class="d-grid gap-2 d-md-block">
        <form action="add_to_cart.php" method="post" class="d-inline">
  <!-- ID do produto -->
  <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
  
  <!-- Quantidade (opcional, pode ser escondida ou com campo de input) -->
  <input type="hidden" name="quantidade" value="1">

  <button type="submit" class="btn btn-primary btn-lg me-2">
    <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
  </button>
</form>

          <a href="compra.php?produto_id=<?= $produto['id'] ?>" class="btn btn-outline-secondary btn-lg">Comprar Agora</a>
        </div>
    </div>
  </div>
</div>

<!-- Avaliações e Comentários -->
<div class="container my-5">
  <h3 class="mb-4">Avaliações dos Clientes</h3>

  <?php
$stmt = $pdo->prepare("
    SELECT a.*, u.nome 
    FROM avaliacao a
    JOIN usuario u ON u.id = a.usuarioId
    WHERE a.produtoId = ?
    ORDER BY a.data_hora DESC
");
$stmt->execute([$produto_id]);
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-4">
  <?php if ($avaliacoes): ?>
    <?php foreach ($avaliacoes as $avaliacao): ?>
      <div class="border rounded p-3 mb-3">
        <div class="d-flex justify-content-between">
          <strong><?= htmlspecialchars($avaliacao['nome']) ?></strong>
          <div class="text-warning">
            <?= str_repeat("★", $avaliacao['nota']) . str_repeat("☆", 5 - $avaliacao['nota']) ?>
          </div>
        </div>
        <p class="mb-0"><?= nl2br(htmlspecialchars($avaliacao['comentario'])) ?></p>
        <small class="text-muted"><?= date("d/m/Y H:i", strtotime($avaliacao['data_hora'])) ?></small>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-muted">Nenhuma avaliação ainda. Seja o primeiro!</p>
  <?php endif; ?>
</div>



  
<!-- Formulário para nova avaliação -->
<h5>Deixe sua avaliação</h5>
<form method="POST" action="enviar_avaliacao.php">
  <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
  <input type="hidden" name="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">
  
  <div class="mb-3">
    <label for="comentario" class="form-label">Comentário</label>
    <textarea class="form-control" id="comentario" name="comentario" rows="3" required></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Nota</label>
    <select class="form-select" name="nota" required>
      <option value="">Selecione...</option>
      <option value="5">★★★★★ - Excelente</option>
      <option value="4">★★★★☆ - Muito bom</option>
      <option value="3">★★★☆☆ - Bom</option>
      <option value="2">★★☆☆☆ - Regular</option>
      <option value="1">★☆☆☆☆ - Ruim</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
</form>
  </div>

<!-- Produtos relacionados -->
<div class="container mb-5">
  <h3 class="mb-4">Produtos Relacionados</h3>
  <div class="row">
    <?php if ($relacionados): ?>
      <?php foreach ($relacionados as $rel): ?>
        <div class="col-md-3">
          <div class="card h-100 card-wrapper">
            <div class="favorite-checkbox">
              <input type="checkbox" id="fav<?= $rel['id'] ?>">
              <label for="fav<?= $rel['id'] ?>"><i class="fas fa-heart"></i></label>
            </div>
            <img src="../IMG/uploads/<?= htmlspecialchars($rel['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($rel['nome']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($rel['nome']) ?></h5>
              <p class="text-primary fw-bold">R$ <?= number_format($rel['preco'], 2, ',', '.') ?></p>
              <a href="r1.php?id=<?= $rel['id'] ?>" class="btn btn-outline-primary btn-sm">Ver Produto</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted">Nenhum produto relacionado encontrado.</p>
    <?php endif; ?>
  </div>
</div>


<!-- Rodapé -->
<footer class="bg-dark text-white text-center py-4">
  <div class="container">
    <p class="mb-1">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
