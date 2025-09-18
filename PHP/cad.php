<?php
// Ativa os erros para debug (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui conexão com banco - ajuste o caminho conforme seu projeto
require 'ADMIN/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pega os dados do POST, com sanitização básica
    $nome       = trim($_POST['nome'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $senha      = $_POST['senha'] ?? '';
    $telefone   = trim($_POST['telefone'] ?? '');
    $genero     = $_POST['genero'] ?? '';
    $cep        = trim($_POST['cep'] ?? '');
    $rua        = trim($_POST['rua'] ?? '');
    $numero     = trim($_POST['numero'] ?? '');
    $bairro     = trim($_POST['bairro'] ?? '');
    $cidade     = trim($_POST['cidade'] ?? '');
    $estado     = trim($_POST['estado'] ?? '');
    $newsletter = isset($_POST['newsletter']) ? 'Sim' : 'Não';

    // Validações simples
    if (empty($nome) || empty($email) || empty($senha)) {
        die("Campos obrigatórios não preenchidos.");
    }

    // Monta endereço
    $endereco = "Rua: $rua, Nº: $numero, Bairro: $bairro, Cidade: $cidade, Estado: $estado, CEP: $cep";

    // Hash da senha para segurança
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        // Insere no banco
        $stmt = $pdo->prepare("INSERT INTO Usuario (nome, email, senha, genero, telefone, endereco) VALUES (:nome, :email, :senha, :genero, :telefone, :endereco)");
        $stmt->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':senha'    => $senhaHash,
            ':genero'   => $genero,
            ':telefone' => $telefone,
            ':endereco' => $endereco
        ]);

        // Sucesso: redireciona ou mostra mensagem
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = 'login.php?msg=cad_s';</script>";
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            die("Este e-mail já está em uso.");
        }
        die("Erro ao cadastrar: " . $e->getMessage());
    }

} else {
    die("Requisição inválida.");
}
