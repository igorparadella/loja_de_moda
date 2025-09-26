<?php
session_start();

require 'ADMIN/db.php';

if (!isset($_SESSION['usuario_id'])) {
    // Usuário não logado, pode redirecionar ou mostrar erro
    header('Location: login.php');
    exit;
}
$usuario_id = $_SESSION['usuario_id'];

// Busca dados do usuário no banco
$stmt = $pdo->prepare('SELECT nome, email, genero, telefone, endereco FROM Usuario WHERE id = ?');
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    // Usuário não encontrado (algo errado)
    echo "Usuário não encontrado.";
    exit;
}

$tp = $_GET['tp'] ?? '';
$produto_id_url = $_GET['produto_id'] ?? null;

$itens = [];
$total = 0;

if ($produto_id_url) {
    // Se veio produto_id, busca só ele para compra direta
    $stmt = $pdo->prepare("SELECT id AS produto_id, nome, preco, imagem FROM Produto WHERE id = ?");
    $stmt->execute([$produto_id_url]);
    $produto = $stmt->fetch();

    if ($produto) {
        $itens[] = [
            'item_id' => null,       // não existe item no carrinho ainda
            'produto_id' => $produto['produto_id'],
            'nome' => $produto['nome'],
            'preco' => $produto['preco'],
            'imagem' => $produto['imagem'],
            'quantidade' => 1         // quantidade padrão 1 para compra direta
        ];
        $total = $produto['preco'] * 1;
    } else {
        // Produto não encontrado
        echo "Produto não encontrado.";
        exit;
    }
} elseif ($tp === 'carrinho') {
    // Busca o carrinho mais recente do usuário
    $stmt = $pdo->prepare("SELECT id FROM Carrinho WHERE usuario_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$usuario_id]);
    $carrinho = $stmt->fetch();

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

        foreach ($itens as $item) {
            $subtotal = $item['preco'] * $item['quantidade'];
            $total += $subtotal;
        }
    }
} else {
    // Se nenhum parâmetro, pode redirecionar ou carregar carrinho por padrão
    header('Location: carrinho.php');
    exit;
}

$frete = 15.00;
$total_com_frete = $total + $frete;

// --- INSERÇÃO DO PEDIDO E ITENS ---

try {
    // Inicia a transação
    $pdo->beginTransaction();

    // Insere o pedido
    $stmt = $pdo->prepare("INSERT INTO Pedido (idUsuario, total, status, data, formaPagamento, enderecoEntrega) VALUES (?, ?, ?, CURRENT_DATE, ?, ?)");
    // Ajuste os valores conforme necessário, aqui usei dados do usuário e valor calculado
    $formaPagamento = 'Boleto'; // exemplo fixo, pode ser dinâmico
    $status = 'Em processamento';
    $enderecoEntrega = $usuario['endereco'] ?? 'Endereço não informado';

    $stmt->execute([$usuario_id, $total_com_frete, $status, $formaPagamento, $enderecoEntrega]);

    // Pega o ID do pedido inserido
    $pedido_id = $pdo->lastInsertId();

    // Insere os produtos do pedido
    $stmtItem = $pdo->prepare("INSERT INTO Pedido_Produto (pedido_id, produto_id, quantidade) VALUES (?, ?, ?)");

    foreach ($itens as $item) {
        $stmtItem->execute([$pedido_id, $item['produto_id'], $item['quantidade']]);
    }

    // Confirma a transação
    $pdo->commit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Falha ao processar o pedido: " . $e->getMessage();
    exit;
}

?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Boleto | ModaTop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .boleto-container {
      max-width: 800px;
      margin: 40px auto;
      background: white;
      border: 1px solid #ccc;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
      padding: 30px;
    }

    .boleto-header {
      border-bottom: 2px solid #ccc;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .linha-digitavel {
      font-family: 'Courier New', Courier, monospace;
      font-size: 1.3rem;
      background: #e9ecef;
      padding: 10px 15px;
      border-radius: 5px;
      display: inline-block;
      margin-top: 10px;
    }

    .barcode {
      margin: 40px 0;
    }

    .section-title {
      font-weight: bold;
      margin-top: 20px;
      margin-bottom: 10px;
      font-size: 1.1rem;
    }

    .btn-print {
      font-size: 1rem;
      padding: 10px 20px;
    }

    @media print {
      .btn-print, nav, footer {
        display: none !important;
      }
      body {
        background: white;
      }
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
    

<!-- Boleto -->
<div class="boleto-container">
  <div class="boleto-header d-flex justify-content-between align-items-center">
    <img src="https://upload.wikimedia.org/wikipedia/commons/8/8e/Banco_do_Brasil_logo.svg" alt="Banco do Brasil" height="50">
    <div class="text-end">
      <h5 class="mb-0">Banco do Brasil</h5>
      <small>001-9</small>
    </div>
  </div>

  <div class="text-center">
    <div class="section-title">Linha Digitável:</div>
    <div class="linha-digitavel">
      00190.00009 01234.567891 23456.789012 3 12340000024990
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-6">
      <p><strong>Beneficiário:</strong> ModaTop Comércio de Roupas LTDA</p>
      <p><strong>CNPJ:</strong> 12.345.678/0001-90</p>
      <p><strong>Agência/Código do Beneficiário:</strong> 1234-5 / 67890-1</p>
      <p><strong>Nosso Número:</strong> 123456789012345</p>
    </div>
    <div class="col-md-6">
      <p><strong>Pagador: </strong><?= htmlspecialchars($usuario['nome']) ?></p>
      <p><strong>CPF:</strong> 123.456.789-00</p>
      <p><strong>Endereço: </strong> <?= nl2br(htmlspecialchars($usuario['endereco'] ?? 'Não informado')) ?></p>
      <p><strong>Data de Emissão:</strong> <?php echo date('d/m/Y'); ?></p>
<p><strong>Vencimento:</strong> <?php echo date('d/m/Y', strtotime('+10 days')); ?></p>

    </div>
  </div>

  <div class="section-title">Descrição da Compra:</div>
  <ul>
  <?php foreach ($itens as $item): 
$subtotal = $item['preco'] * $item['quantidade'];
?>
    <li><?= htmlspecialchars($item['nome']) ?><?php if ($item['quantidade'] > 1): ?>
    x <?= $item['quantidade'] ?>
<?php endif; ?>

    <strong>R$ <?= number_format($item['preco'], 2, ',', '.') ?></strong></li>


    <?php endforeach; ?>

    <li>Frete - <strong>R$ 15,00</strong></li>
  </ul>

  <p class="mt-3"><strong>Valor Total:</strong> <span class="fs-5 fw-bold">R$ <?= number_format($total + 15, 2, ',', '.') ?></span></p>

  <!-- Código de Barras -->
  <div class="barcode text-center">
    <svg id="barcode"></svg>
  </div>

  <div class="text-center">
    <button class="btn btn-dark btn-print" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Imprimir Boleto
    </button>
  </div>
</div>

<a href="compra.php?tp=<?php echo $tp; ?>" class="btn btn-secondary mt-3 align-center"><i class="bi bi-arrow-left"></i> Voltar para a loja</a>


<!-- Rodapé -->
<footer class="bg-dark text-white text-center py-4 mt-5">
  <div class="container">
    <p class="mb-0">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
  JsBarcode("#barcode", "00190000090123456789123456789012312340000024990", {
    format: "CODE128",
    lineColor: "#000",
    width: 2,
    height: 60,
    displayValue: false,
    margin: 0
  });
</script>

<script>
    var tempo = 20;
    var corret = tempo * 1000;

    setTimeout(function() {
        window.location.href = "finalizar.php?tp=<?php echo $tp; ?>&produto_id=<?php echo $produto_id_url; ?>";
    }, corret);
</script>

</body>
</html>
