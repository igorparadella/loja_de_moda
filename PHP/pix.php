<?php
session_start();
require 'ADMIN/db.php';

// Verifica login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
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

$tp = $_GET['tp'] ?? '';
$produto_id_url = $_GET['produto_id'] ?? null;

$itens = [];
$total = 0;

// Compra direta
if ($produto_id_url) {
    $stmt = $pdo->prepare("SELECT id AS produto_id, nome, preco, imagem FROM Produto WHERE id = ?");
    $stmt->execute([$produto_id_url]);
    $produto = $stmt->fetch();

    if ($produto) {
        $itens[] = [
            'produto_id' => $produto['produto_id'],
            'nome'       => $produto['nome'],
            'preco'      => $produto['preco'],
            'imagem'     => $produto['imagem'],
            'quantidade' => 1
        ];
        $total = $produto['preco'];
    } else {
        echo "Produto não encontrado.";
        exit;
    }
}
// Compra pelo carrinho
elseif ($tp === 'carrinho') {
    $stmt = $pdo->prepare("SELECT id FROM Carrinho WHERE usuario_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$usuario_id]);
    $carrinho = $stmt->fetch();

    if ($carrinho) {
        $carrinho_id = $carrinho['id'];

        $stmt = $pdo->prepare("
            SELECT ci.id AS item_id, p.id AS produto_id, p.nome, p.preco, p.imagem, ci.quantidade
            FROM Carrinho_Item ci
            JOIN Produto p ON p.id = ci.produto_id
            WHERE ci.carrinho_id = ?
        ");
        $stmt->execute([$carrinho_id]);
        $itens = $stmt->fetchAll();

        foreach ($itens as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
    }
} else {
    header('Location: carrinho.php');
    exit;
}

$frete = 15.00;
$total_com_frete = $total + $frete;

// --- INSERÇÃO DO PEDIDO ---
try {
    $pdo->beginTransaction();

    $formaPagamento = 'PIX';
    $status = 'Em processamento';
    $enderecoEntrega = $usuario['endereco'] ?? 'Endereço não informado';

    $stmt = $pdo->prepare("INSERT INTO Pedido 
        (idUsuario, total, status, data, formaPagamento, enderecoEntrega) 
        VALUES (?, ?, ?, NOW(), ?, ?)");
    $stmt->execute([$usuario_id, $total_com_frete, $status, $formaPagamento, $enderecoEntrega]);

    $pedido_id = $pdo->lastInsertId();

    $stmtItem = $pdo->prepare("INSERT INTO Pedido_Produto (pedido_id, produto_id, quantidade) VALUES (?, ?, ?)");
    foreach ($itens as $item) {
        $stmtItem->execute([$pedido_id, $item['produto_id'], $item['quantidade']]);
    }

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao processar o pedido: " . $e->getMessage();
    exit;
}
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Pagamento via PIX - ModaTop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .pix-img {
      max-width: 280px;
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 10px;
      background: #fff;
    }

    .pix-container {
      background-color: #f8f9fa;
      padding: 30px;
      border-radius: 10px;
      text-align: center;
    }

    .pix-key {
      background-color: #e9ecef;
      padding: 10px;
      border-radius: 5px;
      font-family: monospace;
    }
  </style>
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
            <form id="searchForm" class="d-flex d-none" role="search">
              <input class="form-control me-2" type="search" placeholder="Buscar produtos..." aria-label="Buscar">
              <button class="btn btn-outline-light" type="submit">
                <i class="bi bi-arrow-right"></i>
              </button>
            </form>
      
            <!-- Links do menu -->
            <ul class="navbar-nav ms-3">
              <li class="nav-item"><a class="nav-link " href="index.php">Início</a></li>
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
        const searchForm = document.getElementById('searchForm');
      
        toggleBtn.addEventListener('click', () => {
          searchForm.classList.toggle('d-none');
        });
      </script>

  <!-- Conteúdo -->
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6 pix-container">
        <h3 class="mb-3">Pagamento via PIX</h3>
        <p>Escaneie o QR Code abaixo com seu banco ou aplicativo de pagamento.</p>

        <!-- QR Code fake (substitua pela imagem real) -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=chavePIXmodatop@banco.com" alt="QR Code PIX" class="pix-img mb-4">

        <!-- Valor -->
        <h4 class="text-success fw-bold mb-3">Valor: R$ <?= number_format($total + 15, 2, ',', '.') ?></h4>

        <!-- Chave PIX -->
        <p class="mb-1"><strong>Ou copie a chave PIX:</strong></p>
        <div class="pix-key mb-3" id="pixKey">chavePIXmodatop@banco.com</div>
        <button class="btn btn-outline-primary btn-sm mb-4" onclick="copiarChavePIX()">Copiar Chave</button>

        <!-- Mensagem final -->
        <div class="alert alert-success d-none" id="msgCopia" role="alert">
          Chave PIX copiada com sucesso!
        </div>

        <!-- Botão voltar -->
        <a href="compra.php?tp=<?php echo $tp; ?>" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left"></i> Voltar para a loja</a>
      </div>
    </div>
  </div>

  <!-- Script de cópia -->
  <script>
    function copiarChavePIX() {
      const chave = document.getElementById("pixKey").innerText;
      navigator.clipboard.writeText(chave).then(() => {
        document.getElementById("msgCopia").classList.remove("d-none");
        setTimeout(() => {
          document.getElementById("msgCopia").classList.add("d-none");
        }, 2000);
      });
    }
  </script>

    <!-- Rodapé -->
    <footer class="bg-dark text-white text-center fixed-bottom py-4">
        <div class="container">
          <p class="mb-1">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
        </div>
      </footer>

    
      <script>
    var tempo = 20;
    var corret = tempo * 1000;

    setTimeout(function() {
        window.location.href = "finalizar.php?tp=<?php echo $tp; ?>&produto_id=<?php echo $produto_id_url; ?>";
    }, corret);
</script>



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
