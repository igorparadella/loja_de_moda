<?php
session_start();
require 'ADMIN/db.php';  // conexão PDO

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php?msg=login_obrigatorio");
  exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Busca dados do usuário
$stmt = $pdo->prepare('SELECT nome, email, genero, telefone, endereco FROM Usuario WHERE id = ?');
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit;
}

// Busca os pedidos do usuário
$stmt = $pdo->prepare('SELECT * FROM Pedido WHERE idUsuario = ? ORDER BY data DESC');
$stmt->execute([$usuario_id]);
$pedidos = $stmt->fetchAll();

// Para cada pedido, buscar os produtos relacionados
foreach ($pedidos as &$pedido) {
    $stmt = $pdo->prepare('
        SELECT p.nome, pp.quantidade, p.preco
        FROM Pedido_Produto pp
        JOIN Produto p ON p.id = pp.produto_id
        WHERE pp.pedido_id = ?
    ');
    $stmt->execute([$pedido['id']]);
    $pedido['produtos'] = $stmt->fetchAll();
}
unset($pedido);  // limpar referência
?>

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Perfil - ModaTop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="../CSS/index.css" rel="stylesheet" />
  <link href="../CSS/logo.css" rel="stylesheet" />
  </head>
<body class="d-flex flex-column min-vh-100">
<main class="flex-fill">
   

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

  <!-- Perfil do Usuário -->
  <section class="py-5">
    <div class="container">
      <!-- Banner com avatar -->
      <div class="banner1 rounded-top position-relative" style="height: 180px;">
        <div class="position-absolute top-100 start-50 translate-middle">
        <img src="../IMG/<?php
    if ($usuario['genero'] == 'masculino') {
        echo 'mano.jpg';
    } else if ($usuario['genero'] == 'feminino') {
        echo 'mina.jpg';
    } else {
        echo 'asdf.png';
    }
?>" class="rounded-circle Perfil border border-white shadow" width="150" height="150" alt="Foto de Perfil" />        </div>
      </div><br>

      <!-- Conteúdo abaixo do avatar -->
      <div class="text-center mt-5">
        <h3 class="mb-0"><?= htmlspecialchars($usuario['nome']) ?></h3>
        <p class="text-muted"><?= htmlspecialchars($usuario['email']) ?></p>

        <!-- Botões principais -->
        <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
          <a href="carrinho.php" class="btn btn-outline-primary">
          <i class="bi bi-cart"></i>
          Carrinho
          </a>
          <a href="pedidos.php" class="btn btn-outline-dark">
            <i class="bi bi-bag-check"></i> Ver Pedidos
          </a>
          <a href="logout.php" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Sair
          </a>
        </div>
      </div>

      <!-- Abas com conteúdo -->
      <ul class="nav nav-tabs mt-5 justify-content-center" id="perfilTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="dados-tab" data-bs-toggle="tab" data-bs-target="#dados" type="button" role="tab">
            <i class="bi bi-person"></i> Meus Dados
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pedidos-tab" data-bs-toggle="tab" data-bs-target="#pedidos" type="button" role="tab">
            <i class="bi bi-bag"></i> Pedidos
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="endereco-tab" data-bs-toggle="tab" data-bs-target="#endereco" type="button" role="tab">
            <i class="bi bi-geo-alt"></i> Endereço
          </button>
        </li>
      </ul>

      <!-- Conteúdo das Abas -->
      <div class="tab-content p-4 border border-top-0 bg-white rounded-bottom" id="perfilTabsContent">
        <!-- Aba: Dados -->
        <div class="tab-pane fade show active" id="dados" role="tabpanel">
          <h5>Informações Pessoais</h5>
          <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
          <p><strong>Telefone:</strong> <?= htmlspecialchars($usuario['telefone'] ?? 'Não informado') ?></p>
          <!-- Pode pegar a data de cadastro se armazenar no banco (não mostrado no seu esquema) -->
        </div>

        <!-- Aba: Pedidos -->
<div class="tab-pane fade" id="pedidos" role="tabpanel">
  <h5>Últimos Pedidos</h5>

  <?php if (count($pedidos) === 0): ?>
    <p>Você ainda não realizou nenhum pedido.</p>
  <?php else: ?>
    <div class="accordion" id="pedidosAccordion">
      <?php foreach ($pedidos as $index => $pedido): ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?= $index ?>">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
              Pedido #<?= htmlspecialchars($pedido['id']) ?> - <?= date('d/m/Y', strtotime($pedido['data'])) ?> - &nbsp
              <span class="badge 
                <?php 
                  switch ($pedido['status']) {
                    case 'Entregue': echo 'bg-success'; break;
                    case 'A Caminho': echo 'bg-warning text-dark'; break;
                    case 'Em processamento': echo 'bg-primary'; break;
                    default: echo 'bg-secondary';
                  }
                ?>">
                <?= htmlspecialchars($pedido['status']) ?>
              </span>
            </button>
          </h2>
          <div id="collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>" data-bs-parent="#pedidosAccordion">
            <div class="accordion-body">
              <ul class="list-group mb-2">
                <?php foreach ($pedido['produtos'] as $produto): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($produto['nome']) ?> x <?= $produto['quantidade'] ?>
                    <span>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
              <strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?>
              <br>
              <strong>Forma de Pagamento:</strong> <?= htmlspecialchars($pedido['formaPagamento'] ?? 'Não informado') ?>
              <br>
              <strong>Endereço de Entrega:</strong> <?= nl2br(htmlspecialchars($pedido['enderecoEntrega'] ?? 'Não informado')) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>


        <!-- Aba: Endereço -->
        <div class="tab-pane fade" id="endereco" role="tabpanel">
          <h5>Endereço de Entrega</h5>
          <p><?= nl2br(htmlspecialchars($usuario['endereco'] ?? 'Não informado')) ?></p>
        </div>
      </div>
    </div>
  </section>


  </main>


  <!-- Rodapé -->
  <footer class="bg-dark text-white text-center py-4 fixed-bottom">
    <div class="container">
      <p class="mb-1">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
