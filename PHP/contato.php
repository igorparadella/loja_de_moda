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
      <h2 class="text-center mb-4">Entre em Contato</h2>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow p-4">
            <form>
              <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" placeholder="Digite seu nome" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" placeholder="Digite seu e-mail" required>
              </div>
              <div class="mb-3">
                <label for="assunto" class="form-label">Assunto</label>
                <input type="text" class="form-control" id="assunto" placeholder="Assunto da mensagem" required>
              </div>
              <div class="mb-3">
                <label for="mensagem" class="form-label">Mensagem</label>
                <textarea class="form-control" id="mensagem" rows="5" placeholder="Escreva sua mensagem" required></textarea>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-envelope-fill me-1"></i> Enviar Mensagem
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="row mt-5 text-center">
        <div class="col-md-4 mb-3">
          <i class="bi bi-telephone-fill fs-2 text-primary"></i>
          <p class="mt-2">Telefone: (11) 1234-5678</p>
        </div>
        <div class="col-md-4 mb-3">
          <i class="bi bi-envelope-at-fill fs-2 text-primary"></i>
          <p class="mt-2">E-mail: modatop@gmail.com</p>
        </div>
        <div class="col-md-4 mb-3">
          <i class="bi bi-geo-alt-fill fs-2 text-primary"></i>
          <p class="mt-2">Endereço: Rua Exemplo, 123 - São Paulo/SP</p>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
      <p class="mb-0">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
    </div>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
