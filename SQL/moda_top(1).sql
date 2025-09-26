-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 26-Set-2025 às 11:10
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
  `produtoId` bigint UNSIGNED NOT NULL,
  `usuarioId` bigint UNSIGNED NOT NULL,
  `nota` int NOT NULL,
  `comentario` text,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `avaliacao`
--

INSERT INTO `avaliacao` (`id`, `produtoId`, `usuarioId`, `nota`, `comentario`, `data_hora`) VALUES
(1, 10, 6, 5, 'adf', '2025-09-24 12:41:19'),
(2, 10, 12, 3, 'muito caro!!!', '2025-09-24 12:43:21'),
(3, 10, 14, 5, 'Zika do Baile!', '2025-09-24 13:12:14');

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
(4, 10, '2025-09-19 12:55:48'),
(5, 12, '2025-09-24 12:25:05'),
(6, 13, '2025-09-24 13:00:15'),
(7, 14, '2025-09-24 13:11:44');

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
(29, 4, 10, 1, '2025-09-19 12:55:48'),
(30, 5, 10, 4, '2025-09-24 12:25:05'),
(31, 6, 10, 1, '2025-09-24 13:00:15'),
(33, 7, 3, 1, '2025-09-24 13:11:44'),
(34, 7, 10, 2, '2025-09-24 13:11:56');

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
(12, 'Calçados', NULL),
(13, 'Moda Plus Size', NULL),
(14, 'Moda Praia', NULL),
(15, 'Moda Infantil', NULL),
(16, 'Moda Esportiva', NULL),
(17, 'Bolsas', NULL),
(18, 'Joias', NULL);

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
(3, 10, '94.90', 'Em processamento', '2025-09-19', 'Cartão de Crédito', 'Rua: Rua Aracy, Nº: 222, Bairro: Jardim Leblon, Cidade: Guarulhos, Estado: SP, CEP: 07272-040'),
(4, 6, '94.90', 'Cancelado', '2025-09-23', 'Cartão de Crédito', 'Rua: Rua Paquitá, Nº: 87, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(5, 6, '135.00', 'Cancelado', '2025-09-23', 'Cartão de Crédito', 'Rua: Rua Paquitá, Nº: 87, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(6, 6, '1015.00', 'Aguardando pagamento', '2025-09-24', 'PIX', 'Rua: Rua Paquitá, Nº: 87, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(7, 6, '1015.00', 'Em processamento', '2025-09-24', 'Boleto', 'Rua: Rua Paquitá, Nº: 87, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(8, 6, '1015.00', 'Em processamento', '2025-09-24', 'Cartão de crédito', 'Rua: Rua Paquitá, Nº: 87, Bairro: Recreio São Jorge, Cidade: Guarulhos, Estado: SP, CEP: 07144-160'),
(9, 12, '4015.00', 'Cancelado', '2025-09-24', 'Cartão de crédito', 'Rua: Rua Aracy, Nº: 333, Bairro: Jardim Leblon, Cidade: Guarulhos, Estado: SP, CEP: 07272-040'),
(10, 13, '1015.00', 'Em processamento', '2025-09-24', 'PIX', 'Rua: Estrada do Sacramento, Nº: 2089, Bairro: Cidade Tupinambá, Cidade: Guarulhos, Estado: SP, CEP: 07263-000'),
(11, 14, '2165.00', 'Cancelado', '2025-09-24', 'PIX', 'Rua: Rua Alexandre Coelho, Nº: 118, Bairro: Jardim Divinolândia, Cidade: Guarulhos, Estado: SP, CEP: 07133-190');

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
(3, 11, 1),
(4, 11, 1),
(5, 12, 1),
(6, 10, 1),
(7, 10, 1),
(8, 10, 1),
(9, 10, 4),
(10, 10, 1),
(11, 3, 1),
(11, 10, 2);

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
(1, 'Camisa Polo Masculina', 'Camisa polo confortável 100% algodão', '89.90', 'r7.webp', 1, 50, 1),
(2, 'Vestido Longo Floral', 'Vestido elegante com estampa floral', '149.99', 'r4.webp', 1, 30, 2),
(3, 'Relógio de Pulso', 'Relógio analógico com pulseira de couro', '150.00', 're.jpg', 0, 20, 3),
(10, 'Karpo', 'blusa do sung', '1000.00', '68c17c403580a.png', 1, 10, 1),
(11, 'Camisa Polo', 'Camisa Polo de algodão', '79.90', 'polo.jpg', 1, 50, 1),
(12, 'Calça Jeans', 'Calça jeans masculina', '120.00', 'cal.webp', 1, 30, 2),
(13, 'Top', 'Top fitness preto com recortes e bojo básico dlk', '23.00', '68d528578a795.jpg', 0, 23, 2),
(24, 'Blusa Canelada', 'Blusa feminina manga curta', '45.00', 'blusa1.jpg', 1, 80, 2),
(25, 'Saia Midi', 'Saia midi estampada', '79.90', 'saia1.jpg', 1, 50, 2),
(26, 'Macacão Floral', 'Macacão de verão com flores', '139.90', 'macacao1.jpg', 1, 40, 2),
(27, 'Short Jeans', 'Short jeans com rasgos', '69.90', 'short1.jpg', 1, 60, 2),
(28, 'Vestido Curto', 'Vestido para festas', '99.90', 'vestido1.jpg', 1, 45, 2),
(29, 'Calça Legging', 'Legging preta básica', '59.90', 'legging1.jpg', 1, 70, 2),
(30, 'Cropped', 'Top cropped alça fina', '35.90', 'cropped1.jpg', 1, 80, 2),
(31, 'Jaqueta de Couro', 'Jaqueta sintética com zíper', '199.90', 'jaquetafem1.jpg', 1, 25, 2),
(32, 'Body de Renda', 'Body sensual de renda', '89.90', 'body1.jpg', 1, 50, 2),
(33, 'Kimono Floral', 'Kimono leve e elegante', '79.90', 'kimono1.jpg', 1, 30, 2),
(34, 'Óculos de Sol', 'Óculos com proteção UV', '120.00', 'oculos1.jpg', 1, 100, 3),
(35, 'Pulseira de Couro', 'Pulseira masculina ajustável', '49.90', 'pulseira1.jpg', 1, 60, 3),
(36, 'Boné', 'Boné ajustável com logo', '39.90', 'bone1.jpg', 1, 80, 3),
(37, 'Cinto Social', 'Cinto de couro legítimo', '89.90', 'cinto1.jpg', 1, 40, 3),
(38, 'Chapéu Panamá', 'Chapéu elegante unissex', '109.90', 'chapeu1.jpg', 1, 25, 3),
(39, 'Carteira Masculina', 'Carteira em couro', '79.90', 'carteira1.jpg', 1, 50, 3),
(40, 'Brinco de Argola', 'Brinco dourado feminino', '29.90', 'brinco1.jpg', 1, 90, 3),
(41, 'Colar Minimalista', 'Colar delicado com pingente', '39.90', 'colar1.jpg', 1, 70, 3),
(42, 'Touca de Lã', 'Touca para inverno', '25.00', 'touca1.jpg', 1, 60, 3),
(43, 'Lenço Estampado', 'Lenço feminino elegante', '35.00', 'lenco1.jpg', 1, 50, 3),
(44, 'Tênis Esportivo', 'Tênis leve para corrida', '199.90', 'tenis1.jpg', 1, 100, 12),
(45, 'Sandália Rasteira', 'Sandália confortável para verão', '79.90', 'sandalia1.jpg', 1, 80, 12),
(46, 'Bota Coturno', 'Bota de couro masculina', '249.90', 'bota1.jpg', 1, 40, 12),
(47, 'Chinelo Slide', 'Chinelo moderno com logo', '59.90', 'chinelo1.jpg', 1, 100, 12),
(48, 'Salto Alto', 'Sapato de salto para festas', '149.90', 'salto1.jpg', 1, 30, 12),
(49, 'Tênis Casual', 'Tênis branco unissex', '159.90', 'teniscasual1.jpg', 1, 70, 12),
(50, 'Mocassim', 'Sapato confortável social', '189.90', 'mocassim1.jpg', 1, 35, 12),
(51, 'Chuteira Society', 'Chuteira para futebol society', '219.90', 'chuteira1.jpg', 1, 60, 12),
(52, 'Sapatilha Feminina', 'Sapatilha casual confortável', '89.90', 'sapatilha1.jpg', 1, 45, 12),
(53, 'Tamanco', 'Tamanco para uso diário', '99.90', 'tamanco1.jpg', 1, 55, 12),
(54, 'Vestido Plus Size Longo', 'Vestido estampado para plus size', '159.90', 'vestidoplus1.jpg', 1, 40, 13),
(55, 'Blusa Plus Size Manga 3/4', 'Blusa casual plus size', '79.90', 'blusaplus1.jpg', 1, 60, 13),
(56, 'Calça Plus Size', 'Calça reta plus size', '129.90', 'calcaplus1.jpg', 1, 50, 13),
(57, 'Kimono Plus Size', 'Kimono leve para plus size', '99.90', 'kimonoplus1.jpg', 1, 30, 13),
(58, 'Saia Plus Size Midi', 'Saia para plus size', '89.90', 'saiaplus1.jpg', 1, 45, 13),
(59, 'Macacão Plus Size', 'Macacão feminino plus size', '149.90', 'macacaoplus1.jpg', 1, 35, 13),
(60, 'Blazer Plus Size', 'Blazer estruturado plus size', '199.90', 'blazerplus1.jpg', 1, 20, 13),
(61, 'Cropped Plus Size', 'Cropped confortável plus size', '59.90', 'croppedplus1.jpg', 1, 55, 13),
(62, 'Body Plus Size', 'Body de malha plus size', '99.90', 'bodyplus1.jpg', 1, 50, 13),
(63, 'Cardigan Plus Size', 'Cardigan leve plus size', '119.90', 'cardiganplus1.jpg', 1, 25, 13),
(64, 'Biquíni Tomara Que Caia', 'Conjunto de biquíni tomara que caia', '129.90', 'biquini1.jpg', 1, 40, 14),
(65, 'Maiô Cavado', 'Maiô cavado para praia/piscina', '149.90', 'maio1.jpg', 1, 30, 14),
(66, 'Saída de Praia', 'Saída leve para usar sobre biquíni', '89.90', 'saidapraia1.jpg', 1, 50, 14),
(67, 'Sunga Masculina', 'Sunga estampada masculina', '69.90', 'sunga1.jpg', 1, 60, 14),
(68, 'Short Praia Masculino', 'Short estilo boardshort', '99.90', 'shortpraia1.jpg', 1, 70, 14),
(69, 'Biquíni de Cores', 'Biquíni colorido com amarrações', '139.90', 'biquini2.jpg', 1, 35, 14),
(70, 'Maiô Engana Mamãe', 'Maiô com decote e detalhe', '159.90', 'maio2.jpg', 1, 25, 14),
(71, 'Canga Estampada', 'Canga leve para praia', '49.90', 'canga1.jpg', 1, 80, 14),
(72, 'Chapéu de Palha Praia', 'Chapéu para proteção solar', '89.90', 'chapepraia1.jpg', 1, 40, 14),
(73, 'Saia Longa Praia', 'Saia leve para usar na praia', '99.90', 'saiapraia1.jpg', 1, 50, 14),
(74, 'Camiseta Infantil', 'Camiseta divertida infantil', '39.90', 'camiseta_inf1.jpg', 1, 100, 15),
(75, 'Vestido Infantil', 'Vestido floral infantil', '59.90', 'vestido_inf1.jpg', 1, 60, 15),
(76, 'Short Infantil', 'Short jeans infantil', '49.90', 'short_inf1.jpg', 1, 80, 15),
(77, 'Moletom Infantil', 'Moletom com capuz infantil', '79.90', 'moletom_inf1.jpg', 1, 50, 15),
(78, 'Conjunto Infantil', 'Short + camiseta conjunto infantil', '69.90', 'conjunto_inf1.jpg', 1, 70, 15),
(79, 'Pijama Infantil', 'Pijama de algodão infantil', '59.90', 'pijama_inf1.jpg', 1, 55, 15),
(80, 'Jaqueta Infantil', 'Jaqueta leve infantil', '99.90', 'jaqueta_inf1.jpg', 1, 40, 15),
(81, 'Macacão Infantil', 'Macacão confortável infantil', '69.90', 'macacao_inf1.jpg', 1, 45, 15),
(82, 'Calça Infantil', 'Calça de moletom infantil', '59.90', 'calca_inf1.jpg', 1, 60, 15),
(83, 'Tênis Infantil', 'Tênis para criança', '89.90', 'tenis_inf1.jpg', 1, 50, 15),
(84, 'Legging Fitness', 'Legging de compressão', '99.90', 'legging_fit1.jpg', 1, 70, 16),
(85, 'Top Esportivo', 'Top com suporte e tecido tech', '59.90', 'top_fit1.jpg', 1, 80, 16),
(86, 'Short de Corrida', 'Short leve para corrida', '69.90', 'short_fit1.jpg', 1, 90, 16),
(87, 'Regata Dry Fit', 'Regata respirável para treino', '49.90', 'regata_fit1.jpg', 1, 100, 16),
(88, 'Camiseta de Compressão', 'Camiseta justa para treino', '89.90', 'camiseta_comp1.jpg', 1, 60, 16),
(89, 'Moleton Esportivo', 'Moletom leve para aquecimento', '119.90', 'moletom_fit1.jpg', 1, 40, 16),
(90, 'Calça de Agasalho', 'Calça para aquecimento muscular', '129.90', 'calca_fit1.jpg', 1, 50, 16),
(91, 'Jaqueta Corta Vento', 'Jaqueta resistente à chuva leve', '159.90', 'jaqueta_fit1.jpg', 1, 30, 16),
(92, 'Top Cropped Fitness', 'Top curto com boa sustentação', '69.90', 'topcropped_fit1.jpg', 1, 75, 16),
(93, 'Legging Estampada', 'Legging com estampa digital', '109.90', 'legging_est1.jpg', 1, 65, 16),
(94, 'Bolsa Tote', 'Bolsa grande de uso diário', '149.90', 'bolsa_tote1.jpg', 1, 40, 17),
(95, 'Bolsa Crossbody', 'Bolsa pequena transversal', '129.90', 'bolsa_cross1.jpg', 1, 50, 17),
(96, 'Bolsa Clutch', 'Bolsa para eventos', '119.90', 'bolsa_clutch1.jpg', 1, 30, 17),
(97, 'Mochila Feminina', 'Mochila elegante para sair', '179.90', 'mochila1.jpg', 1, 35, 17),
(98, 'Mochila Casual', 'Mochila unissex casual', '149.90', 'mochila2.jpg', 1, 45, 17),
(99, 'Bolsa de Ombro', 'Bolsa com alça média', '139.90', 'bolsa_ombro1.jpg', 1, 50, 17),
(100, 'Bolsa Satchel', 'Bolsa com estrutura rígida', '159.90', 'bolsa_satchel1.jpg', 1, 25, 17),
(101, 'Pochete', 'Pochete moderna unissex', '89.90', 'pochete1.jpg', 1, 70, 17),
(102, 'Bolsa de Viagem Pequena', 'Bolsa compacta para viagem', '169.90', 'bolsa_viagem1.jpg', 1, 20, 17),
(103, 'Bolsa Saco', 'Bolsa estilo saco leve', '129.90', 'bolsa_saco1.jpg', 1, 30, 17),
(104, 'Anel de Prata', 'Anel em prata 925', '199.90', 'anel1.jpg', 1, 25, 18),
(105, 'Brinco de Ouro', 'Brinco em ouro 18k', '399.90', 'brinco_ouro1.jpg', 1, 15, 18),
(106, 'Colar de Pérolas', 'Colar clássico de pérolas', '499.90', 'colar_perola1.jpg', 1, 10, 18),
(107, 'Pulseira de Ouro', 'Pulseira fina em ouro', '299.90', 'pulseira_ouro1.jpg', 1, 20, 18),
(108, 'Pingente Personalizado', 'Pingente em prata com gravação', '149.90', 'pingente1.jpg', 1, 30, 18),
(109, 'Relógio Feminino Luxo', 'Relógio com pedras decorativas', '999.90', 'relogio_luxo1.jpg', 1, 8, 18),
(110, 'Brinco de Diamante', 'Brinco pequeno com diamante', '1299.90', 'brinco_diamante1.jpg', 1, 5, 18),
(111, 'Tornozeleira Slim', 'Tornozeleira delicada em prata', '179.90', 'tornozeleira1.jpg', 1, 20, 18),
(112, 'Bracelete Rígido', 'Bracelete em aço/prata', '249.90', 'bracelete1.jpg', 1, 18, 18),
(113, 'Colar Choker', 'Choker em ouro branco', '299.90', 'choker1.jpg', 1, 12, 18),
(114, 'Camisa UV Infantil', 'Proteção solar para os pequenos, ideal para praia ou piscina', '59.90', 'camisa_uv_kids.jpg', 1, 22, 15),
(115, 'Tênis de Corrida Plus Size', 'Conforto e desempenho para todas as formas e tamanhos', '199.90', 'tenis_corrida_plus.jpg', 1, 14, 13);

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
(11, 'João Silva', 'joao@email.com', 'senha123', 'Masculino', '11999999999', 'Rua A, 123, São Paulo'),
(12, 'dilan', 'dilan20132@gmail.com', '$2y$10$nL114eJYCL2wILSmmp2lfeZB1zdIoHi5u64n7hyQM.khL.LCM21Yi', 'masculino', '(11) 99999-9999', 'Rua: Rua Aracy, Nº: 333, Bairro: Jardim Leblon, Cidade: Guarulhos, Estado: SP, CEP: 07272-040'),
(13, 'lucas', 'lucasxavier.9087@gmail.com', '$2y$10$sCqY5LvW/w99sSv0hPitD.SUPsQ55WJp6Lx5iNiW0JtzuSeUL09nu', 'masculino', '(11) 96745-2345', 'Rua: Estrada do Sacramento, Nº: 2089, Bairro: Cidade Tupinambá, Cidade: Guarulhos, Estado: SP, CEP: 07263-000'),
(14, 'Leandro', 'leandro@gmail.com', '$2y$10$qYM3cSVPpJn0pNj9vmUs.uQSYl.g5YOBgZIneZVbf0shZJDoPMbsm', 'masculino', '(11) 94749-5238', 'Rua: Rua Alexandre Coelho, Nº: 118, Bairro: Jardim Divinolândia, Cidade: Guarulhos, Estado: SP, CEP: 07133-190');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produtoId` (`produtoId`),
  ADD KEY `usuarioId` (`usuarioId`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `carrinho_item`
--
ALTER TABLE `carrinho_item`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `contato`
--
ALTER TABLE `contato`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `plataforma`
--
ALTER TABLE `plataforma`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de tabela `promocao`
--
ALTER TABLE `promocao`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `avaliacao_ibfk_1` FOREIGN KEY (`produtoId`) REFERENCES `produto` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacao_ibfk_2` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
