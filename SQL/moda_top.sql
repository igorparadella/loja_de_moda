-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 23-Set-2025 às 11:23
-- Versão do servidor: 8.0.41
-- versão do PHP: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `moda_top`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `id` bigint UNSIGNED NOT NULL,
  `produtoId` int NOT NULL,
  `usuarioId` int NOT NULL,
  `nota` int NOT NULL,
  `comentario` text,
  `data_hora` timestamp NOT NULL
) ;

--
-- Extraindo dados da tabela `avaliacao`
--

INSERT INTO `avaliacao` (`id`, `produtoId`, `usuarioId`, `nota`, `comentario`, `data_hora`) VALUES
(1, 2, 1, 5, 'Vestido maravilhoso, chegou rápido!', '2025-09-10 13:30:00'),
(2, 3, 2, 4, 'Relógio bonito, mas demorou um pouco.', '2025-09-09 12:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario_id` int NOT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `carrinho`
--

INSERT INTO `carrinho` (`id`, `usuario_id`, `criado_em`) VALUES
(1, 6, '2025-09-16 11:38:02'),
(2, 7, '2025-09-17 12:42:14'),
(3, 8, '2025-09-19 11:13:23'),
(4, 10, '2025-09-19 12:55:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho_item`
--

CREATE TABLE `carrinho_item` (
  `id` bigint UNSIGNED NOT NULL,
  `carrinho_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `quantidade` int NOT NULL,
  `adicionado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Extraindo dados da tabela `carrinho_item`
--

INSERT INTO `carrinho_item` (`id`, `carrinho_id`, `produto_id`, `quantidade`, `adicionado_em`) VALUES
(6, 2, 10, 3, '2025-09-17 12:42:14'),
(10, 2, 3, 1, '2025-09-17 13:12:19'),
(25, 3, 10, 1, '2025-09-19 12:11:15'),
(26, 3, 3, 1, '2025-09-19 12:11:20'),
(29, 4, 10, 1, '2025-09-19 12:55:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `descricao`) VALUES
(1, 'Roupas Masculinas', 'Vestuário voltado para o público masculino'),
(2, 'Roupas Femininas', 'Moda feminina moderna e clássica'),
(3, 'Acessórios', 'Relógios, bolsas, óculos, etc.'),
(10, 'Camisas', 'Camisas masculinas e femininas'),
(11, 'Calças', 'Calças de diversos estilos');

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracao`
--

CREATE TABLE `configuracao` (
  `chave` varchar(50) NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contato`
--

CREATE TABLE `contato` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `dataContato` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `contato`
--

INSERT INTO `contato` (`id`, `nome`, `email`, `mensagem`, `dataContato`) VALUES
(1, 'João Pedro', 'joao@gmail.com', 'Gostaria de saber sobre o prazo de entrega.', '2025-09-08'),
(2, 'Maria Clara', 'maria@gmail.com', 'Produto veio com defeito.', '2025-09-07');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido`
--

CREATE TABLE `pedido` (
  `id` bigint UNSIGNED NOT NULL,
  `idUsuario` int NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `data` date NOT NULL,
  `formaPagamento` varchar(100) DEFAULT NULL,
  `enderecoEntrega` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `pedido`
--

INSERT INTO `pedido` (`id`, `idUsuario`, `total`, `status`, `data`, `formaPagamento`, `enderecoEntrega`) VALUES
(1, 10, '1015.00', 'Em processamento', '2025-09-19', 'Cartão de Crédito', 'Rua: Rua Aracy, Nº: 222, Bairro: Jardim Leblon, Cidade: Guarulhos, Estado: SP, CEP: 07272-040'),
(2, 10, '1015.00', 'Em processamento', '2025-09-19', 'Cartão de Crédito', 'Rua: Rua Aracy, Nº: 222, Bairro: Jardim Leblon, Cidade: Guarulhos, Estado: SP, CEP: 07272-040'),
(3, 10, '94.90', 'Em processamento', '2025-09-19', 'Cartão de Crédito', 'Rua: Rua Aracy, Nº: 222, Bairro: Jardim Leblon, Cidade: Guarulhos, Estado: SP, CEP: 07272-040');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido_produto`
--

CREATE TABLE `pedido_produto` (
  `pedido_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `quantidade` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `pedido_produto`
--

INSERT INTO `pedido_produto` (`pedido_id`, `produto_id`, `quantidade`) VALUES
(1, 10, 1),
(2, 10, 1),
(3, 11, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `plataforma`
--

CREATE TABLE `plataforma` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `segura` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `plataforma`
--

INSERT INTO `plataforma` (`id`, `nome`, `url`, `segura`) VALUES
(1, 'ModaOnline', 'https://www.modaonline.com', 1),
(2, 'FashionStore', 'https://www.fashionstore.com', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `plataforma_categoria`
--

CREATE TABLE `plataforma_categoria` (
  `plataforma_id` int NOT NULL,
  `categoria_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `plataforma_categoria`
--

INSERT INTO `plataforma_categoria` (`plataforma_id`, `categoria_id`) VALUES
(1, 1),
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `plataforma_produto`
--

CREATE TABLE `plataforma_produto` (
  `plataforma_id` int NOT NULL,
  `produto_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `plataforma_produto`
--

INSERT INTO `plataforma_produto` (`plataforma_id`, `produto_id`) VALUES
(1, 1),
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `certificacaoSeguranca` tinyint(1) DEFAULT NULL,
  `estoque` int NOT NULL,
  `categoria_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id`, `nome`, `descricao`, `preco`, `imagem`, `certificacaoSeguranca`, `estoque`, `categoria_id`) VALUES
(1, 'Camisa Polo Masculina', 'Camisa polo confortável 100% algodão', '89.90', 'camisa_polo.jpg', 1, 50, 1),
(2, 'Vestido Longo Floral', 'Vestido elegante com estampa floral', '149.99', 'vestido_floral.jpg', 1, 30, 2),
(3, 'Relógio de Pulso', 'Relógio analógico com pulseira de couro', '150.00', 'uploads/68c17c0bcb8fe_mulher-correndo-isolada-em-fundo-transparente_879541-1171.png', 0, 20, 3),
(10, 'Karpo', 'blusa do sung', '1000.00', '68c17c403580a.png', 1, 10, 1),
(11, 'Camisa Polo', 'Camisa Polo de algodão', '79.90', 'camisa_polo.jpg', 1, 50, 1),
(12, 'Calça Jeans', 'Calça jeans masculina', '120.00', 'calca_jeans.jpg', 1, 30, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `promocao`
--

CREATE TABLE `promocao` (
  `id` bigint UNSIGNED NOT NULL,
  `descricao` text,
  `desconto` decimal(5,2) NOT NULL,
  `validade` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `promocao`
--

INSERT INTO `promocao` (`id`, `descricao`, `desconto`, `validade`) VALUES
(1, 'Desconto de Primavera', '10.00', '2025-09-30'),
(2, 'Black Friday', '50.00', '2025-11-29');

-- --------------------------------------------------------

--
-- Estrutura da tabela `promocao_produto`
--

CREATE TABLE `promocao_produto` (
  `promocao_id` int NOT NULL,
  `produto_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `promocao_produto`
--

INSERT INTO `promocao_produto` (`promocao_id`, `produto_id`) VALUES
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `endereco` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `genero`, `telefone`, `endereco`) VALUES
(1, 'Ana Souza', 'ana@gmail.com', 'senha123', 'Feminino', '11999999999', 'Rua das Flores, 123 - SP'),
(2, 'Carlos Lima', 'carlos@gmail.com', 'senha456', 'Masculino', '11988888888', 'Av. Paulista, 456 - SP'),
(3, 'asdf', 'sdsdsds@dsafsdf', '$2y$10$nC3.cqwRqNmEKANuA5QqLeLiqkJS6GprytruZLvyFC/QXipmMgrsS', 'masculino', '(21) 12121-2121', 'Rua: Rua Paquitá, Nº: 87, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(4, 'Dilan jair', 'dilan20132@gmail.copm', '$2y$10$McjaSBWa2IjFvsMvRvD58.771DQYRvlGv/RZIOmefXZqnqd3dkZ1m', 'masculino', '(11) 96013-4369', 'Rua: Rua Aracy, Nº: 234, Bairro: Jardim Leblon, Cidade: Guarulhos, Estado: SP, CEP: 07272-040'),
(5, 'adf', '121212@sdfdf', '$2y$10$48HSf3P8ZjtcmCv1ACnfwuLm3Oh.8xTxfQnuqDNQjA86lxvGp8x1C', 'masculino', '(12) 12121-21', 'Rua: Rua Paquitá, Nº: 87, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(6, 'Igor Gomes', 'Igor@gmail.com', '$2y$10$THcSP57uXqcbtr6YQZR5T.YYyUy8jnYHV.tZZw0JYHETCpWqAiBs2', 'masculino', '(11) 99548-6473', 'Rua: Rua Paquitá, Nº: 87, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(7, 'Leandro', 'pepsi.melhor.que.coca3@gmail.com', '$2y$10$Hz5T0wWU80Zfo8vajwWi8e3GbE9AIeXs5EKYJXteGs3xl2IB.o7F6', 'masculino', '(11) 94749-5238', 'Rua: Rua Alexandre Coelho, Nº: 118, Bairro: Jardim Divinolândia, Cidade: Guarulhos, Estado: SP, CEP: 07133-190'),
(8, 'Bruno Viana Mendonça', 'bruno@gmail.com', '$2y$10$WqscOG7F2Wank35a36ZrsO1JpFXr7FGhrgpO7MhnMyYsyaElyymdq', 'masculino', '(11) 96013-4369', 'Rua: Rua Ministro Marcos Freire, Nº: 131, Bairro: Residencial Parque Cumbica, Cidade: Guarulhos, Estado: SP, CEP: 07174-270'),
(9, 'asdf', 'adfdf@dsfdf', '$2y$10$KuimPKRysIJwEhee9fM4VOq/FOI.Dg66RvfK0NPulx1OpQ6vNnDZW', 'masculino', '(11) 11111-111', 'Rua: Rua Paquitá, Nº: 12, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(10, 'dilan', 'dilan0900@gmail.com', '$2y$10$afJkx4vmGEvvyZPOpiemS..Jnogcrt6rRNn0RCbGjwcxC2NWuOEFq', 'masculino', '(11) 96013-4369', 'Rua: Rua Aracy, Nº: 222, Bairro: Jardim Leblon, Cidade: Guarulhos, Estado: SP, CEP: 07272-040'),
(11, 'João Silva', 'joao@email.com', 'senha123', 'Masculino', '11999999999', 'Rua A, 123, São Paulo');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `carrinho_item`
--
ALTER TABLE `carrinho_item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `configuracao`
--
ALTER TABLE `configuracao`
  ADD PRIMARY KEY (`chave`);

--
-- Índices para tabela `contato`
--
ALTER TABLE `contato`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `pedido_produto`
--
ALTER TABLE `pedido_produto`
  ADD PRIMARY KEY (`pedido_id`,`produto_id`);

--
-- Índices para tabela `plataforma`
--
ALTER TABLE `plataforma`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `plataforma_categoria`
--
ALTER TABLE `plataforma_categoria`
  ADD PRIMARY KEY (`plataforma_id`,`categoria_id`);

--
-- Índices para tabela `plataforma_produto`
--
ALTER TABLE `plataforma_produto`
  ADD PRIMARY KEY (`plataforma_id`,`produto_id`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `promocao`
--
ALTER TABLE `promocao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `promocao_produto`
--
ALTER TABLE `promocao_produto`
  ADD PRIMARY KEY (`promocao_id`,`produto_id`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `carrinho_item`
--
ALTER TABLE `carrinho_item`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `contato`
--
ALTER TABLE `contato`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `plataforma`
--
ALTER TABLE `plataforma`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `promocao`
--
ALTER TABLE `promocao`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
