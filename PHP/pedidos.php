<?php
session_start();
require 'ADMIN/db.php';  // conexão PDO

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php?msg=login_obrigatorio");
  exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Busca dados do usuário para saudação (opcional)
$stmt = $pdo->prepare('SELECT nome FROM Usuario WHERE id = ?');
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
unset($pedido);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Meus Pedidos - ModaTop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
<main class="flex-fill">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        ModaTop
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
          <li class="nav-item"><a class="nav-link active" href="pedidos.php">Pedidos</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
        </ul>
      </div>
    </div>
  </nav>

    <h2>Meus Pedidos</h2>
    <p>Olá, <?= htmlspecialchars($usuario['nome']) ?>!</p>

    <?php if (count($pedidos) === 0): ?>
      <div class="alert alert-info">Você ainda não realizou nenhum pedido.</div>
    <?php else: ?>
      <div class="accordion" id="pedidosAccordion">
        <?php foreach ($pedidos as $index => $pedido): ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?= $index ?>">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
                Pedido #<?= htmlspecialchars($pedido['id']) ?> - <?= date('d/m/Y', strtotime($pedido['data'])) ?> - 
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
                <ul class="list-group mb-3">
                  <?php foreach ($pedido['produtos'] as $produto): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <?= htmlspecialchars($produto['nome']) ?> x <?= $produto['quantidade'] ?>
                      <span>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>
                <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>
                <p><strong>Forma de Pagamento:</strong> <?= htmlspecialchars($pedido['formaPagamento'] ?? 'Não informado') ?></p>
                <p><strong>Endereço de Entrega:</strong> <?= nl2br(htmlspecialchars($pedido['enderecoEntrega'] ?? 'Não informado')) ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <footer class="bg-dark fixed-bottom text-white text-center py-3">
    <div class="container">
      &copy; 2025 ModaTop - Todos os direitos reservados.
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
