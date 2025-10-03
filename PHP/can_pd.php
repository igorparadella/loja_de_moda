<?php
session_start();
require 'ADMIN/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?msg=login_obrigatorio");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Verifica se veio o pedido_id
if (!isset($_POST['pedido_id'])) {
    header("Location: pedidos.php?msg=requisicao_invalida");
    exit();
}

$pedido_id = $_POST['pedido_id'];

// Busca os dados do pedido
$stmt = $pdo->prepare("SELECT id, total, status FROM Pedido WHERE id = ? AND idUsuario = ?");
$stmt->execute([$pedido_id, $usuario_id]);
$pedido = $stmt->fetch();

if (!$pedido || $pedido['status'] !== 'Em processamento') {
    header("Location: pedidos.php?msg=pedido_nao_encontrado_ou_invalido");
    exit();
}

// Busca dados do usuário
$stmt = $pdo->prepare('SELECT nome, email FROM Usuario WHERE id = ?');
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contato | ModaTop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../CSS/index.css" rel="stylesheet">
  <link href="../CSS/logo.css" rel="stylesheet">
  
</head>
<body class="d-flex flex-column min-vh-100">

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
                          <li class="nav-item"><a class="nav-link " href="produtos.php">Produtos</a></li>
              <li class="nav-item"><a class="nav-link" href="carrinho.php">Carrinho</a></li>
            <li class="nav-item"><a class="nav-link" href="sobre.php">Sobre</a></li>
                        <li class="nav-item"><a class="nav-link active" href="contato.php">Contato</a></li>
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
  <main class="flex-grow-1 bg-light py-5 mt-5">
    <div class="container">
      <h2 class="text-center mb-4">Cancelar o pedido</h2>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow p-4">
          <form action="cancelar_pedido.php" method="POST">
  <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
  <input type="hidden" name="valor" value="<?= htmlspecialchars($pedido['total']) ?>">

  <div class="mb-3">
    <label class="form-label">Valor do pedido</label>
    <input type="text" class="form-control" value="R$ <?= number_format($pedido['total'], 2, ',', '.') ?>" disabled>
  </div>

  <div class="mb-3">
    <label for="email" class="form-label">E-mail</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
  </div>

  <div class="mb-3">
    <label for="motivo" class="form-label">Motivo do cancelamento</label>
    <textarea type="text" class="form-control" id="motivo" name="motivo" placeholder="Por que deseja cancelar?" required></textarea>
  </div>

  <div class="d-grid">
    <button type="submit" class="btn btn-danger">
      <i class="bi bi-x-circle me-1"></i> Confirmar Cancelamento
    </button>
  </div>
</form>

          </div>
        </div>
      </div>

    </div>
  </main>


  <a href="faq.php" class="btn btn-primary position-fixed bottom-0 end-0 m-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; font-size: 32px;">
  <i class="bi bi-question-circle"></i>
</a>

  <footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
      <p class="mb-0">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
    </div>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
