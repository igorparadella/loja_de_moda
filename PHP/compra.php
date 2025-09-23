<?php
session_start();

require 'ADMIN/db.php';

if (!isset($_SESSION['usuario_id'])) {
    // Usuário não logado, pode redirecionar ou mostrar erro
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

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

$endereco = $usuario['endereco'];

// Explode por vírgula para separar os campos
$partes = explode(',', $endereco);

$resultado = [];

foreach ($partes as $parte) {
    // Remove espaços extras no começo e fim
    $parte = trim($parte);
    
    // Explode pelo ": " para separar chave e valor
    $info = explode(': ', $parte);
    
    if (count($info) == 2) {
        $chave = trim($info[0]);
        $valor = trim($info[1]);
        $resultado[$chave] = $valor;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Loja de Roupas</title>
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
    <a class="navbar-brand" href="index.php">
      <div class="logo">
        <div class="logo-icon">M</div>
        <div class="logo-text">Moda<span class="highlight">Top</span></div>
      </div>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <button id="searchToggle" class="btn btn-outline-light me-2 ms-auto" type="button">
        <i class="bi bi-search"></i>
      </button>

      <form id="searchForm" class="d-flex d-none" role="search" method="GET" action="produtos.php">
        <input
          class="form-control me-2"
          type="search"
          name="nome"
          placeholder="Buscar produtos..."
          aria-label="Buscar"
          value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>"
        />
        <button class="btn btn-outline-light" type="submit">
          <i class="bi bi-arrow-right"></i>
        </button>
      </form>

      <ul class="navbar-nav ms-3">
        <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
        <li class="nav-item"><a class="nav-link" href="produtos.php">Produtos</a></li>
        <li class="nav-item"><a class="nav-link" href="carrinho.php">Carrinho</a></li>
        <li class="nav-item"><a class="nav-link" href="sobre.php">Sobre</a></li>
        <li class="nav-item"><a class="nav-link" href="contato.php">Contato</a></li>

        <li class="nav-item dropdown">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="loginDropdown"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            Login
          </a>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="loginDropdown">
            <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
            <li><a class="dropdown-item" href="login.php">Logar</a></li>
            <li><a class="dropdown-item" href="cadastro.php">Cadastrar</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  const toggleBtn = document.getElementById('searchToggle');
  const searchForm = document.getElementById('searchForm');
  toggleBtn.addEventListener('click', () => {
    searchForm.classList.toggle('d-none');
  });
</script>

<?php require 'notificacao.php'; ?>

<div class="container my-5"><br>
  <h2 class="mb-4">Finalizar Compra</h2>
  <div class="row">

    <!-- Resumo do Pedido -->
    <div class="col-md-5 mb-4">
      <div class="card">
        <div class="card-header">
          <strong>Resumo do Pedido</strong>
        </div>
        <div class="card-body">
    <?php foreach ($itens as $item): 
        $subtotal = $item['preco'] * $item['quantidade'];
    ?>
    <div class="d-flex mb-3">
        <img src="../IMG/uploads/<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>" width="100" class="rounded me-3">
        <div>
            <h5 class="mb-1"><?= htmlspecialchars($item['nome']) ?></h5>
            <p class="mb-0 text-muted">Quantidade: <?= $item['quantidade'] ?></p>
            <p class="fw-bold text-primary mt-2">R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
        </div>
    </div>
    <hr>
    <?php endforeach; ?>
    <p class="d-flex justify-content-between">
        <span>Subtotal:</span>
        <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
    </p>
    <p class="d-flex justify-content-between">
        <span>Frete:</span>
        <span>R$ 15,00</span>
    </p>
    <hr>
    <h5 class="d-flex justify-content-between">
        <span>Total:</span>
        <span class="text-success">R$ <?= number_format($total + 15, 2, ',', '.') ?></span>
    </h5>
</div>

      </div>
    </div>

    <!-- Formulário de Compra -->
    <div class="col-md-7">
      <form action="" method="post" id="form">
        <h5 class="mb-3">Informações de Entrega</h5>

        <div class="mb-3">
          <label for="nome" class="form-label">Nome completo</label>
          <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($usuario['nome']) ?>"/>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($usuario['email']) ?>"/>
        </div>

        <div class="mb-3">
          <label for="cep" class="form-label">CEP</label>
          <input type="text" class="form-control" id="cep" name="cep" required onblur="buscarCep()" value="<?= $resultado['CEP']?>"/>
        </div>

        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" class="form-control" id="cidade" name="cidade" required value="<?= $resultado['Cidade']?>"/>
          </div>
          <div class="col-md-3 mb-3">
            <label for="bairro" class="form-label">Bairro</label>
            <input type="text" class="form-control" id="bairro" name="bairro" required value="<?= $resultado['Bairro']?>"/>
          </div>
          <div class="col-md-3 mb-3">
            <label for="rua" class="form-label">Rua</label>
            <input type="text" class="form-control" id="rua" name="rua" required value="<?= $resultado['Rua']?>"/>
          </div>
          <div class="col-md-3 mb-3">
            <label for="numero" class="form-label">Número</label>
            <input type="text" class="form-control" id="numero" name="numero" required value="<?= $resultado['Nº']?>"/>
          </div>
        </div>

        <hr />

        <h5 class="mb-3">Pagamento</h5>

        <div class="mb-3">
          <label for="pagamento" class="form-label">Forma de pagamento</label>
          <select class="form-select" id="pagamento" name="pagamento" required>
            <option selected disabled>Escolha...</option>
            <option value="pix">PIX</option>
            <option value="cartao">Cartão de crédito</option>
            <option value="cartao2">Cartão de débito</option>
            <option value="boleto">Boleto bancário</option>
          </select>
        </div>

        <div class="mb-3" id="pagamento2" style="display:none;">
          <label for="parcelas" class="form-label">Parcela</label>
          <select class="form-select" id="parcelas" name="parcelas">
            <option selected disabled>Escolha...</option>
            <option value="1">1x R$<?= number_format($total_com_frete / 1, 2, ',', '.') ?></option>
            <option value="2">2x R$<?= number_format($total_com_frete / 2, 2, ',', '.') ?></option>
            <option value="3">3x R$<?= number_format($total_com_frete / 3, 2, ',', '.') ?></option>
          </select>
        </div>

        <div class="d-grid mt-4">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-check-circle-fill"></i> Finalizar Compra
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

</main>

<!-- Rodapé -->
<footer class="bg-dark text-white text-center py-3">
    <div class="container">
    <p class="mb-1">&copy; 2025 ModaTop - Todos os direitos reservados.</p>
  </div>
</footer>

<script>
  const selectPagamento = document.getElementById("pagamento");
  const divParcelas = document.getElementById("pagamento2");
  const form = document.getElementById("form");

  selectPagamento.addEventListener("change", function () {
    if (this.value === "cartao") {
      divParcelas.style.display = "block";
      form.action = "cartao.php?tp=<?php echo $tp; ?>&produto_id=<?php echo $produto_id_url; ?> ";
    } else if (this.value === "pix") {
      divParcelas.style.display = "none";
      form.action = "pix.php?tp=<?php echo $tp; ?>&produto_id=<?php echo $produto_id_url; ?> ";
    } else if (this.value === "boleto") {
      divParcelas.style.display = "none";
      form.action = "boleto.php?tp=<?php echo $tp; ?>&produto_id=<?php echo $produto_id_url; ?> ";
    }else if (this.value === "cartao2") {
      divParcelas.style.display = "none";
      form.action = "cartao.php?tp=<?php echo $tp; ?>&produto_id=<?php echo $produto_id_url; ?> ";
    } else {
      divParcelas.style.display = "none";
      form.action = "";
    }
  });

  function buscarCep() {
    const cep = document.getElementById("cep").value.replace(/\D/g, "");

    if (cep.length !== 8) {
      alert("CEP inválido. Deve conter 8 dígitos.");
      return;
    }

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then((response) => response.json())
      .then((data) => {
        if (data.erro) {
          alert("CEP não encontrado.");
          return;
        }
        document.getElementById("cidade").value = data.localidade || "";
        document.getElementById("bairro").value = data.bairro || "";
        document.getElementById("rua").value = data.logradouro || "";
      })
      .catch(() => {
        alert("Erro ao buscar o CEP.");
      });
  }

  document.getElementById("cep").addEventListener("input", function () {
    let value = this.value.replace(/\D/g, "");

    if (value.length > 8) {
      value = value.slice(0, 8);
    }

    if (value.length > 5) {
      value = value.replace(/^(\d{5})(\d)/, "$1-$2");
    }
    this.value = value;
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
