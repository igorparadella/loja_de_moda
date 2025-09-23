<?php
$msg = $_GET['msg'] ?? '';

$messages = [
    // -----------------------Alerta-----------------------

    'login_obrigatorio' => [
        'text' => '⚠️ Você precisa estar logado para acessar essa página.',
        'class' => 'alert-warning' 
    ],

    // ----------------------Acesso negado------------------------

    'acesso_negado' => [
        'text' => '🚫 Acesso negado. Você não tem permissão para acessar essa área.',
        'class' => 'alert-danger' 
    ],'log_f' => [
        'text' => '🚫 Acesso negado. Tente novamente!',
        'class' => 'alert-danger'
    ],

    // ---------------------sucesso-------------------------

    'sucesso' => [
        'text' => '✅ Operação realizada com sucesso!',
        'class' => 'alert-success'
    ],'log_s' => [
        'text' => '✅ Logado com sucesso!',
        'class' => 'alert-success' 
    ],'cad_s' => [
        'text' => '✅ Cadastrado com sucesso!',
        'class' => 'alert-success' 
    ],'car_s' => [
      'text' => '✅ Foi adicionado ao carrinho com sucesso!',
      'class' => 'alert-success' 
  ],'msg_ev_s' => [
    'text' => '✅ Sua mensagem foi enviada com sucesso!',
    'class' => 'alert-success' 
]
];


if (isset($messages[$msg])): 
    $message = $messages[$msg];
    echo '
    <div id="alert-msg" class="alert ' . $message['class'] . ' alert-dismissible fade show fixed-top-auto" role="alert">
        ' . $message['text'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>

    <style>
    @keyframes slideDownFade {
      0% {
        opacity: 0;
        transform: translateX(-50%) translateY(-60px);
      }
      100% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
      }
    }

    @keyframes fadeSlideUp {
      0% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
      }
      100% {
        opacity: 0;
        transform: translateX(-50%) translateY(-40px);
      }
    }

    .fixed-top-auto {
      position: fixed;
      top: 4.1rem;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1030;
      width: auto;
      animation: slideDownFade 0.5s ease forwards;
    }

    .fade-slide-up {
      animation: fadeSlideUp 0.4s ease forwards;
    }
    </style>

    <script>
    setTimeout(function () {
        const alert = document.getElementById("alert-msg");
        if (alert) {
            alert.classList.remove("show");
            alert.classList.add("fade-slide-up");

            // Aguarda o tempo da animação para remover o elemento
            setTimeout(() => {
                alert.remove();
            }, 600); // duração da animação fadeSlideUp
        }
    }, 3000);
    </script>
    ';
endif;
?>
