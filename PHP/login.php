<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ModaTop - Loja de Roupas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../CSS/index.css" rel="stylesheet">

  <link href="../CSS/logo.css" rel="stylesheet">

 

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



 

          
  <!-- Login -->
  <main class="flex-grow-1 d-flex align-items-center justify-content-center bg-light">
    <div class="login-card mt-5 mb-5">
      <h2 class="text-center">Login</h2>
      <form action="log.php" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="seu-email@exemplo.com" required>
        </div>
        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Entrar</button>
        </div>
        <div class="mt-3 text-center">
          <a href="cadastro.php">Não tem uma conta? Cadastre-se</a>
        </div>
      </form>
    </div>
  </main>

  <!-- Rodapé -->
  <footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
      <p class="mb-0">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
    </div>
  </footer>

  <!-- Scripts -->


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
