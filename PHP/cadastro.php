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
            <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
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
    


<!-- Multi-step Form com Barra de Progresso -->
<main class="flex-grow-1 d-flex align-items-center justify-content-center bg-light">
  <div class="login-card mt-5 mb-5 w-100" style="max-width: 600px;">
    <h2 class="text-center mb-4">Cadastro</h2>

    <!-- Barra de Progresso -->
    <div class="progress mb-4" style="height: 8px;">
      <div class="progress-bar bg-primary" role="progressbar" id="progressBar" style="width: 25%;"></div>
    </div>

    <form id="cadastroForm" action="cad.php" method="POST">
    <!-- Etapa 1 -->
      <div class="form-step">
        <h5 class="mb-3">1. Dados Pessoais</h5>
        <div class="mb-3">
          <label class="form-label">Nome</label>
          <input type="text" class="form-control" name="nome" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Telefone</label>
          <input type="tel" class="form-control" name="telefone" id="telefone">
          
          <script>
            const telefoneInput = document.getElementById('telefone');
          
            telefoneInput.addEventListener('input', function (e) {
              let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não for dígito
          
              if (value.length > 11) {
                value = value.slice(0, 11); // Limita a 11 dígitos
              }
          
              // Aplica a máscara: (99) 99999-9999
              value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
              value = value.replace(/(\d{5})(\d)/, '$1-$2');
          
              e.target.value = value;
            });
          </script>
          
        </div>
        <div class="mb-3">
          <label class="form-label">E-mail</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Senha</label>
          <input type="password" class="form-control" name="senha" required>
        </div>
      </div>

      <!-- Etapa 2 -->
      <div class="form-step d-none">
        <h5 class="mb-3">2. Endereço</h5>
      
        <div class="mb-3">
          <label class="form-label">CEP</label>
          <input type="text" class="form-control" name="cep" id="cep" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Rua</label>
          <input type="text" class="form-control" name="rua" id="rua" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Número</label>
          <input type="text" class="form-control" name="numero" id="numero" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Bairro</label>
          <input type="text" class="form-control" name="bairro" id="bairro">
        </div>
        <div class="mb-3">
          <label class="form-label">Cidade</label>
          <input type="text" class="form-control" name="cidade" id="cidade" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Estado</label>
          <input type="text" class="form-control" name="estado" id="estado" required>
        </div>
      </div>

      
      <script>
        document.getElementById('cep').addEventListener('blur', function () {
          const cep = this.value.replace(/\D/g, '');
      
          if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
              .then(response => response.json())
              .then(data => {
                if (!data.erro) {
                  document.getElementById('rua').value = data.logradouro;
                  document.getElementById('bairro').value = data.bairro;
                  document.getElementById('cidade').value = data.localidade;
                  document.getElementById('estado').value = data.uf;
                } else {
                  alert('CEP não encontrado!');
                  limpaCamposEndereco();
                }
              })
              .catch(() => {
                alert('Erro ao buscar o CEP.');
                limpaCamposEndereco();
              });
          } else {
            alert('CEP inválido!');
            limpaCamposEndereco();
          }
        });
      
        function limpaCamposEndereco() {
          document.getElementById('rua').value = '';
          document.getElementById('bairro').value = '';
          document.getElementById('cidade').value = '';
          document.getElementById('estado').value = '';
        }
      </script>


<script>
  document.getElementById('cep').addEventListener('input', function () {
    let value = this.value.replace(/\D/g, '');

    if (value.length > 8) {
      value = value.slice(0, 8);
    }

    if (value.length > 5) {
      value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    }
    this.value = value;
  });
</script>


      <!-- Etapa 3 -->
      <div class="form-step d-none">
        <h5 class="mb-3">3. Preferências</h5>
        <div class="mb-3">
          <label class="form-label">Gênero</label>
          <select class="form-select" name="genero">
            <option value="">Selecione</option>
            <option value="feminino">Feminino</option>
            <option value="masculino">Masculino</option>
            <option value="outro">Outro</option>
            <option value="nao_informar">Prefiro não informar</option>
          </select>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter">
          <label class="form-check-label" for="newsletter">
            Desejo receber promoções por e-mail
          </label>
        </div>
      </div>

      <!-- Etapa 4 -->
      <div class="form-step d-none">
        <h5 class="mb-3">4. Revisão</h5>
        <p class="text-muted">Revise suas informações antes de concluir.</p>
        <!-- Exemplo de resumo estático. Pode ser dinâmico com JS -->
        <ul class="list-group">
          <li class="list-group-item">Nome: <strong><span id="resumo-nome">João</span></strong></li>
          <li class="list-group-item">E-mail: <strong><span id="resumo-email">joao@email.com</span></strong></li>
          <li class="list-group-item">Telefone: <strong><span id="resumo-telefone">(11) 99999-9999</span></strong></li>
          <!-- Adicione mais campos conforme necessário -->
        </ul>
      </div>

      <!-- Botões de navegação -->
      <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-outline-secondary" id="prevBtn">Voltar</button>
        <button type="button" class="btn btn-primary" id="nextBtn">Avançar</button>
      </div>

      <!-- Enviar -->
      <div class="d-grid mt-4 d-none" id="submitBtnContainer">
        <button type="submit" class="btn btn-success">Finalizar Cadastro</button>
      </div>

      <!-- Link login -->
      <div class="text-center mt-4">
        <a href="login.php">Já tem uma conta? Logar-se</a>
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


  
  <script>
  const steps = document.querySelectorAll(".form-step");
  const nextBtn = document.getElementById("nextBtn");
  const prevBtn = document.getElementById("prevBtn");
  const submitBtnContainer = document.getElementById("submitBtnContainer");
  const progressBar = document.getElementById("progressBar");

  let currentStep = 0;

  function atualizaResumo() {
  const form = document.getElementById('cadastroForm');

  const nome = form.querySelector('input[name="nome"]').value;
  const email = form.querySelector('input[name="email"]').value;
  const telefone = form.querySelector('input[name="telefone"]').value;

  console.log('Resumo atualizado:', { nome, email, telefone }); // DEBUG

  document.getElementById('resumo-nome').textContent = nome || '(não informado)';
  document.getElementById('resumo-email').textContent = email || '(não informado)';
  document.getElementById('resumo-telefone').textContent = telefone || '(não informado)';
}


  function updateForm() {
    steps.forEach((step, index) => {
      step.classList.toggle("d-none", index !== currentStep);
    });

    prevBtn.style.display = currentStep === 0 ? "none" : "inline-block";
    nextBtn.classList.toggle("d-none", currentStep === steps.length - 1);
    submitBtnContainer.classList.toggle("d-none", currentStep !== steps.length - 1);

    const progress = ((currentStep + 1) / steps.length) * 100;
    progressBar.style.width = progress + "%";

    // Se for a última etapa, atualiza o resumo
    if (currentStep === steps.length - 1) {
      atualizaResumo();
    }
  }

  nextBtn.addEventListener("click", () => {
    // Aqui você pode adicionar validações específicas por etapa, se quiser
    if (currentStep < steps.length - 1) {
      currentStep++;
      updateForm();
    }
  });

  prevBtn.addEventListener("click", () => {
    if (currentStep > 0) {
      currentStep--;
      updateForm();
    }
  });

  updateForm();
</script>

  
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const toggleBtn = document.getElementById('searchToggle');
    const searchForm = document.getElementById('searchForm');
    toggleBtn.addEventListener('click', () => {
      searchForm.classList.toggle('d-none');
    });
  </script>
</body>
</html>
