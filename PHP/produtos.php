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
  <title>Produtos - ModaTop</title>

  <!-- Estilos e ícones -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="../CSS/index.css" rel="stylesheet">
  <link href="../CSS/logo.css" rel="stylesheet">
</head>






<body class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1">
  
<!-- Navbar -->


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
            <li class="nav-item"><a class="nav-link " href="index.php">Início</a></li>
                          <li class="nav-item"><a class="nav-link active" href="produtos.php">Produtos</a></li>
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
const searchContainer = document.getElementById('searchContainer');

toggleBtn.addEventListener('click', () => {
  searchContainer.classList.toggle('d-none');
});

    </script>
    
  



<!-- Filtro lateral -->
<div class="position-fixed top-0 start-0 mt-5 ms-1 z-3" style="z-index: 1030;">
  <br><br>
  <div class="card shadow p-4" style="max-width: 300px;">
    <h5 class="card-title">Filtrar Produtos</h5>

    <?php
      // Garantir que preco seja numérico para evitar erro no number_format
      $precoAtual = isset($_GET['preco']) && is_numeric($_GET['preco']) ? (float) $_GET['preco'] : 0;
    ?>

    <form method="GET" action="">

      <!-- Nome -->
      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" id="nome" name="nome" class="form-control"
               value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>"
               placeholder="Ex: Camiseta, Vestido...">
      </div>

      <!-- Categoria -->
      <div class="mb-3">
        <label class="form-label">Categoria</label>
        <select id="categoria" name="categoria" class="form-select">
          <option value="">Selecione</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id']; ?>" <?= (($_GET['categoria'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['nome']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Preço Máx. -->
      <div class="mb-3">
        <label for="preco" class="form-label">Preço Máx.</label>
        <input type="number" id="preco" name="preco" min="0" max="1000" step="1"
               class="form-control" placeholder="Ex: 150"
               value="<?= $precoAtual ?>">
      </div>

      <!-- Slider -->
      <div class="mb-3">
        <label for="preco_range" class="form-label">Ajuste o preço com o slider</label>
        <input type="range" id="preco_range" class="w-100" min="0" max="1000" step="1"
               value="<?= $precoAtual ?>"
               oninput="updatePreco(this.value)"><br>
        <span id="range_value">R$ <?= number_format($precoAtual, 2, ',', '.') ?></span>
      </div>

      <!-- Botões -->
      <div class="d-flex justify-content-between">
        <button type="reset" class="btn btn-secondary">Limpar</button>
        <button type="submit" class="btn btn-primary">Aplicar</button>
      </div>
    </form>
  </div>
</div>

<!-- Script para sincronizar os campos -->
<script>
  const precoInput = document.getElementById('preco');
  const precoRange = document.getElementById('preco_range');
  const rangeValue = document.getElementById('range_value');

  function formatarReal(valor) {
    return parseFloat(valor).toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    });
  }

  function updatePreco(valor) {
    precoInput.value = valor;
    rangeValue.textContent = formatarReal(valor);
  }

  // Sincroniza o número com o slider
  precoInput.addEventListener('input', () => {
    precoRange.value = precoInput.value;
    rangeValue.textContent = formatarReal(precoInput.value);
  });
</script>


<!-- Produtos -->
<section class="py-5 h-100"><br>
  <div class="container" style="margin-left: 320px;">
    <h2 class="text-center mb-4">Destaques da Loja</h2>
    <div class="row g-3">

      <?php if (count($produtos) > 0): ?>
        <?php foreach ($produtos as $produto): ?>
          <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card h-100">
              <!-- Checkbox de favorito -->
              <div class="favorite-checkbox">
                <input type="checkbox" id="fav<?= $produto['id'] ?>">
                <label for="fav<?= $produto['id'] ?>"><i class="fas fa-heart"></i></label>
              </div>

              <!-- Imagem do produto -->
              <?php if (!empty($produto['imagem'])): ?>
                <img src="../IMG/uploads/<?= htmlspecialchars($produto['imagem']) ?>"
                     class="card-img-top product-img" alt="<?= htmlspecialchars($produto['nome']) ?>">
              <?php else: ?>
                <img src="https://via.placeholder.com/300x200?text=Sem+Imagem"
                     class="card-img-top product-img" alt="Sem imagem">
              <?php endif; ?>

              <!-- Detalhes -->
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($produto['categoria']) ?></p>
                <p class="text-primary fw-bold">
                  R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                </p>

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
</section>

  </main>
<!-- Rodapé -->
<footer class="bg-dark text-white text-center py-4 mt-5">
  <div class="container">
    <p class="mb-0">&copy; <?= date('Y') ?> ModaTop - Todos os direitos reservados.</p>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle do formulário de busca na navbar (se houver)
  document.getElementById('searchToggle')?.addEventListener('click', function () {
    document.getElementById('searchForm')?.classList.toggle('d-none');
  });
</script>
</body>
</html>
