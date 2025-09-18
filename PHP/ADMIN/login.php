<?php
session_start();

// Configurações de acesso fixas
$usuarioPermitido = 'admin';
$senhaPermitida = 'admin'; // Altere para a senha que você quiser

// Se já estiver logado, redireciona
if (isset($_SESSION['admin_id'])) {
    header('Location: admin.php');
    exit;
}

$erro = '';

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($nome === $usuarioPermitido && $senha === $senhaPermitida) {
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_nome'] = $usuarioPermitido;
        header('Location: admin.php');
        exit;
    } else {
        $erro = 'Nome de usuário ou senha incorretos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Moda Top Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title mb-4 text-center">Login Administrativo</h4>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome de Usuário</label>
                    <input type="text" class="form-control" name="nome" id="nome" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" name="senha" id="senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
