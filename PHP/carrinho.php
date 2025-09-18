<?php
session_start();
require 'ADMIN/db.php';

if (!isset($_SESSION['usuario_id'])) {
    
}

$usuario_id = $_SESSION['usuario_id'];

// Busca o carrinho mais recente do usuário
$stmt = $pdo->prepare("SELECT id FROM Carrinho WHERE usuario_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$usuario_id]);
$carrinho = $stmt->fetch();

$itens = [];
$total = 0;

if ($carrinho) {
    $carrinho_id = $carrinho['id'];

    // Busca os itens do carrinho com os dados dos produtos
    $stmt = $pdo->prepare("
        SELECT ci.id AS item_id, p.id AS produto_id, p.nome, p.preco, p.imagem, ci.quantidade
        FROM Carrinho_Item ci
        JOIN Produto p ON p.id = ci.produto_id
        WHERE ci.carrinho_id = ?
    ");
    $stmt->execute([$carrinho_id]);
    $itens = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
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
            <li class="nav-item"><a class="nav-link " href="index.php">Início</a></li>
                          <li class="nav-item"><a class="nav-link " href="produtos.php">Produtos</a></li>
              <li class="nav-item"><a class="nav-link active" href="carrinho.php">Carrinho</a></li>
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


<div class="container mt-5"><br><br>
    <h1 class="mb-4">Carrinho de Compras</h1>

    <?php if (empty($itens)): ?>

        <img src="../IMG/carrinho-vazio.png" class="mx-auto d-block" style="width: 10%; object-fit: cover;" alt="">
        <p class="text-center">Seu carrinho está vazio.</p>

        <?php else: ?>
        <form method="post" action="atualizar_carrinho.php">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): 
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                    <td>
                    <input 
  type="number" 
  name="quantidades[<?= $item['item_id'] ?>]" 
  value="<?= $item['quantidade'] ?>" 
  min="1" 
  class="form-control quantidade-input" 
  data-item-id="<?= $item['item_id'] ?>" 
  style="width: 80px;" 
/>

                    </td>
                    <td id="subtotal-<?= $item['item_id'] ?>">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                    <td>
                        <a href="remover_item.php?id=<?= $item['item_id'] ?>" class="btn btn-danger btn-sm">Remover</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
        <h4>Total: R$ <span id="total-geral"><?= number_format($total, 2, ',', '.') ?></span></h4>
            <div>
                <a href="compra.php?tp=carrinho" class="btn btn-primary">Finalizar Compra</a>
            </div>
        </div>
        </form>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.quantidade-input').forEach(input => {
    input.addEventListener('change', function () {
        const itemId = this.dataset.itemId;
        let quantidade = this.value;
        if (quantidade < 1) quantidade = 1;

        fetch('atualizar_carrinho_ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `item_id=${itemId}&quantidade=${quantidade}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.subtotal) {
                document.getElementById(`subtotal-${itemId}`).textContent = `R$ ${data.subtotal}`;
            }

            if (data.total) {
                document.getElementById('total-geral').textContent = data.total;
            }
        })
        .catch(err => {
            console.error('Erro ao atualizar carrinho:', err);
            alert('Erro ao atualizar item do carrinho.');
        });
    });
});

</script>


<footer class="bg-dark fixed-bottom text-white text-center py-4 mt-auto">
    <div class="container">
      <p class="mb-0">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
</body>
</html>
