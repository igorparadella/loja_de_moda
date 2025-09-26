<?php
// Ativa os erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco
require 'ADMIN/db.php';

// ----------- PRODUTOS DESTAQUE (sem filtro específico) -----------
$sql = "SELECT p.id, p.nome, p.preco, p.imagem, c.nome AS categoria
        FROM Produto p
        INNER JOIN Categoria c ON p.categoria_id = c.id
        ORDER BY p.id DESC
        LIMIT 3";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtosDestaque = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ----------- ROUPAS FEMININAS (categoria_id = 2) -----------
$sql = "SELECT p.id, p.nome, p.preco, p.imagem, c.nome AS categoria
        FROM Produto p
        INNER JOIN Categoria c ON p.categoria_id = c.id
        WHERE c.id = 2
        ORDER BY p.id DESC
        LIMIT 3";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtosFemininos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ----------- ROUPAS MASCULINAS (categoria_id = 1) -----------
$sql = "SELECT p.id, p.nome, p.preco, p.imagem, c.nome AS categoria
        FROM Produto p
        INNER JOIN Categoria c ON p.categoria_id = c.id
        WHERE c.id = 1
        ORDER BY p.id DESC
        LIMIT 3";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtosMasculinos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Loja de Roupas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="../CSS/index.css" rel="stylesheet">
  
  <link href="../CSS/logo.css" rel="stylesheet">
  
</head>
<body>


    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php">  <div class="logo">
          <div class="logo-icon">M</div>
          <div class="logo-text">Moda<span class="highlight">Top</span></div>
        </div>
      </a>
    
        <!-- Toggle do menu -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
    
        <!-- Itens da navbar -->
        <div class="collapse navbar-collapse" id="navbarNav">
    
          <!-- Ícone da lupa -->
          <button id="searchToggle" class="btn btn-outline-light me-2 ms-auto" type="button">
            <i class="bi bi-search"></i>
          </button>
    
          <!-- Campo de busca escondido -->
          <form id="searchForm" class="d-flex d-none" role="search" method="GET" action="produtos.php">
  <input class="form-control me-2" type="search" name="nome" placeholder="Buscar produtos..." aria-label="Buscar"
         value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>">
  <button class="btn btn-outline-light" type="submit">
    <i class="bi bi-arrow-right"></i>
  </button>
</form>

    
          <!-- Links do menu -->
          <ul class="navbar-nav ms-3">
            <li class="nav-item"><a class="nav-link active" href="index.php">Início</a></li>
                          <li class="nav-item"><a class="nav-link " href="produtos.php">Produtos</a></li>
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
    <!-- Script para alternar visibilidade -->
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
    
  

<!-- Banner dividido em 3 partes -->
<header class="py-0"><br><br>
    <div class="container-fluid banner1">
      <div class="row text-white text-center">
        
        <!-- Parte 1 -->
        <div class="col-md-4 p-5 banner2">
        </div>
  
        <!-- Parte 2 (central) -->
        <div class="col-md-4 p-5">
          <h1 class="display-5">Coleção Primavera 2025</h1>
          <p>Estilo e conforto para todas as ocasiões.</p>
          <a href="produtos.php" class="btn btn-light btn-lg mt-2">Ver Produtos</a>
        </div>
  
        <!-- Parte 3 -->
        <div class="col-md-4 p-5 banner3">
        </div>
  
      </div>
    </div>
  </header>
  
<!-- Produtos -->
<section class="py-5 h-100"><br>
<div class="container" style="margin-left: 320px;">
  <h2 class="text-center mb-4">Destaques da Loja</h2>
  <div class="row g-3">
    <?php if (count($produtosDestaque) > 0): ?>
      <?php foreach ($produtosDestaque as $produto): ?>
        <div class="col-12 col-sm-6 col-md-4 mb-4">
          <div class="card h-100">
            <div class="favorite-checkbox">
              <input type="checkbox" id="fav<?= $produto['id'] ?>">
              <label for="fav<?= $produto['id'] ?>"><i class="fas fa-heart"></i></label>
            </div>
            <?php if (!empty($produto['imagem'])): ?>
              <img src="../IMG/uploads/<?= htmlspecialchars($produto['imagem']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($produto['nome']) ?>">
            <?php else: ?>
              <img src="https://via.placeholder.com/300x200?text=Sem+Imagem" class="card-img-top product-img" alt="Sem imagem">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($produto['categoria']) ?></p>
              <p class="text-primary fw-bold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
              <div class="mt-auto">
                <a href="r1.php?id=<?= $produto['id'] ?>" class="btn btn-outline-primary w-100">Comprar</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">Nenhum produto encontrado.</p>
    <?php endif; ?>
  </div>
</div>
<div class="container" style="margin-left: 320px;">
  <h2 class="text-center mb-4">Roupas Femininas</h2>
  <div class="row g-3">
    <?php if (count($produtosFemininos) > 0): ?>
      <?php foreach ($produtosFemininos as $produto): ?>
        <div class="col-12 col-sm-6 col-md-4 mb-4">
          <div class="card h-100">
            <div class="favorite-checkbox">
              <input type="checkbox" id="fav<?= $produto['id'] ?>">
              <label for="fav<?= $produto['id'] ?>"><i class="fas fa-heart"></i></label>
            </div>
            <?php if (!empty($produto['imagem'])): ?>
              <img src="../IMG/uploads/<?= htmlspecialchars($produto['imagem']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($produto['nome']) ?>">
            <?php else: ?>
              <img src="https://via.placeholder.com/300x200?text=Sem+Imagem" class="card-img-top product-img" alt="Sem imagem">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($produto['categoria']) ?></p>
              <p class="text-primary fw-bold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
              <div class="mt-auto">
                <a href="r1.php?id=<?= $produto['id'] ?>" class="btn btn-outline-primary w-100">Comprar</a>
              </div>
            </div>
          </div>
        </div>      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">Nenhum produto encontrado.</p>
    <?php endif; ?>
  </div>
</div>

<div class="container" style="margin-left: 320px;">
  <h2 class="text-center mb-4">Roupas Masculinas</h2>
  <div class="row g-3">
    <?php if (count($produtosMasculinos) > 0): ?>
      <?php foreach ($produtosMasculinos as $produto): ?>
        <div class="col-12 col-sm-6 col-md-4 mb-4">
          <div class="card h-100">
            <div class="favorite-checkbox">
              <input type="checkbox" id="fav<?= $produto['id'] ?>">
              <label for="fav<?= $produto['id'] ?>"><i class="fas fa-heart"></i></label>
            </div>
            <?php if (!empty($produto['imagem'])): ?>
              <img src="../IMG/uploads/<?= htmlspecialchars($produto['imagem']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($produto['nome']) ?>">
            <?php else: ?>
              <img src="https://via.placeholder.com/300x200?text=Sem+Imagem" class="card-img-top product-img" alt="Sem imagem">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($produto['categoria']) ?></p>
              <p class="text-primary fw-bold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
              <div class="mt-auto">
                <a href="r1.php?id=<?= $produto['id'] ?>" class="btn btn-outline-primary w-100">Comprar</a>
              </div>
            </div>
          </div>
        </div>            <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">Nenhum produto encontrado.</p>
    <?php endif; ?>
  </div>
</div>

</section>


  <!-- Rodapé -->
  <footer class="bg-dark text-white text-center py-4">
    <div class="container">
      <p class="mb-1">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

