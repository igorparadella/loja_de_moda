<?php
// Ativa os erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco
require 'ADMIN/db.php';

// Inicializa a query
$sql = "SELECT p.id, p.nome, p.preco, p.imagem, c.nome AS categoria
        FROM Produto p
        INNER JOIN Categoria c ON p.categoria_id = c.id
        WHERE 1=1";

// Parâmetros para filtros
$params = [];

// Filtros opcionais
if (!empty($_GET['nome'])) {
    $sql .= " AND p.nome LIKE :nome";
    $params[':nome'] = '%' . $_GET['nome'] . '%';
}

if (!empty($_GET['categoria'])) {
  $sql .= " AND c.id = :categoria";
  $params[':categoria'] = $_GET['categoria'];
  
}

if (!empty($_GET['tamanho'])) {
    $sql .= " AND p.tamanho = :tamanho";
    $params[':tamanho'] = $_GET['tamanho'];
}

if (!empty($_GET['preco'])) {
    $sql .= " AND p.preco <= :preco";
    $params[':preco'] = $_GET['preco'];
}



$sql .= " ORDER BY p.id DESC";

// Executa a consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("SELECT id, nome FROM Categoria ORDER BY nome ASC");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <section class="py-5">
      <div class="container"><br>
        <h2 class="text-center mb-4">Destaques da Loja</h2>
        <div class="row g-4">
  
<!-- Produto -->
<div class="col-md-4">
  <div class="card-wrapper">
    <!-- Coração de favorito -->
    <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav1">
        <label for="fav1"><i class="fas fa-heart"></i></label>
      </div>

  

      <img src="../IMG/roupas/r1.webp" class="card-img-top product-img" alt="Blusa Básica">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">Blusa Básica Feminina</h5>
        <p class="card-text">Leve e confortável, ideal para o dia a dia.</p>
        <p class="text-primary fw-bold">R$ 79,99 <s>R$100,00</s> (20% OFF)</p>
        <a href="r1.php" class="btn btn-outline-primary mt-auto">Comprar</a>
      </div>
    </div>
  </div>
</div>

  
          <!-- Produto -->
          <div class="col-md-4">
            <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav2">
        <label for="fav2"><i class="fas fa-heart"></i></label>
      </div>
              <img src="../IMG/roupas/r2.webp" class="card-img-top product-img" alt="Tênis Branco">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Tênis Branco Casual</h5>
                <p class="card-text">Combina com tudo! Confortável e moderno.</p>
                <p class="text-primary fw-bold">R$ 219,90</p>
                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
              </div>
            </div>
          </div>
  
          <!-- Produto -->
          <div class="col-md-4">
            <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav3">
        <label for="fav3"><i class="fas fa-heart"></i></label>
      </div>
              <img src="../IMG/roupas/r3.webp" class="card-img-top product-img" alt="Calça Jeans">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Calça Jeans Slim</h5>
                <p class="card-text">Modelo ajustado, ideal para um visual moderno.</p>
                <p class="text-primary fw-bold">R$ 139,90</p>
                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
              </div>
            </div>
          </div>
  
        </div>
      </div>
  
      <div class="container"><br>
        <div class="row g-4">
          <h2 class="text-center mb-4">Moda Feminina</h2>

          <!-- Produto -->
          <div class="col-md-4">
            <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav4">
        <label for="fav4"><i class="fas fa-heart"></i></label>
      </div>
              <img src="../IMG/roupas/r4.webp" class="card-img-top product-img" alt="Blusa Básica">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Vestido Midi Floral</h5>
                <p class="card-text">Romântico e ideal para dias ensolarados.</p>
                <p class="text-primary fw-bold">R$ 79,90 (20% OFF)</p>
                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
              </div>
            </div>
          </div>
  
          <!-- Produto -->
          <div class="col-md-4">
            <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav5">
        <label for="fav5"><i class="fas fa-heart"></i></label>
      </div>
              <img src="../IMG/roupas/r5.webp" class="card-img-top product-img" alt="Tênis Branco">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Saia Jeans Curta</h5>
                <p class="card-text">Peça versátil para o verão.</p>
                <p class="text-primary fw-bold">R$ 219,90</p>
                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
              </div>
            </div>
          </div>
  
          <!-- Produto -->
          <div class="col-md-4">
            <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav6">
        <label for="fav6"><i class="fas fa-heart"></i></label>
      </div>
              <img src="../IMG/roupas/r6.webp" class="card-img-top product-img" alt="Calça Jeans">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Salto Alto Nude</h5>
                <p class="card-text">Elegância e estilo para ocasiões especiais.</p>
                <p class="text-primary fw-bold">R$ 139,90</p>
                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
              </div>
            </div>
          </div>
  
        </div>
      </div>
  
      <div class="container"><br>
        <div class="row g-4">
          <h2 class="text-center mb-4">Moda Masculina</h2>

          <!-- Produto -->
          <div class="col-md-4">
            <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav7">
        <label for="fav7"><i class="fas fa-heart"></i></label>
      </div>
              <img src="../IMG/roupas/r7.webp" class="card-img-top product-img" alt="Blusa Básica">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Camiseta Básica Branca</h5>
                <p class="card-text">Ideal para looks casuais e confortáveis.</p>
                <p class="text-primary fw-bold">R$ 79,90 (20% OFF)</p>
                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
              </div>
            </div>
          </div>
  
          <!-- Produto -->
          <div class="col-md-4">
            <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav8">
        <label for="fav8"><i class="fas fa-heart"></i></label>
      </div>
              <img src="../IMG/roupas/r8.webp" class="card-img-top product-img" alt="Tênis Branco">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Blazer Slim Fit</h5>
                <p class="card-text">Sofisticação para eventos formais.</p>
                <p class="text-primary fw-bold">R$ 219,90</p>
                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
              </div>
            </div>
          </div>
  
          <!-- Produto -->
          <div class="col-md-4">
            <div class="card h-100">
      <div class="favorite-checkbox">
        <input type="checkbox" id="fav9">
        <label for="fav9"><i class="fas fa-heart"></i></label>
      </div>
              <img src="../IMG/roupas/r9.webp" class="card-img-top product-img" alt="Calça Jeans">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Tênis Esportivo Masculino</h5>
                <p class="card-text">Ideal para o dia a dia e academia.</p>
                <p class="text-primary fw-bold">R$ 139,90</p>
                <a href="#" class="btn btn-outline-primary mt-auto">Comprar</a>
              </div>
            </div>
          </div>
  
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

