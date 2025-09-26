-- ===========================
-- CRIAÇÃO DAS TABELAS
-- ===========================
create database Moda_top;
use Moda_top;


CREATE TABLE `avaliacao` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `produtoId` bigint UNSIGNED NOT NULL,
  `usuarioId` bigint UNSIGNED NOT NULL,
  `nota` int NOT NULL,
  `comentario` text,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `carrinho` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `carrinho_item` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `carrinho_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `quantidade` int NOT NULL,
  `adicionado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `categoria` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `contato` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `dataContato` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `pedido` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idUsuario` int NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `data` date NOT NULL,
  `formaPagamento` varchar(100) DEFAULT NULL,
  `enderecoEntrega` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `plataforma` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `segura` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- produto já estava correto:
-- `id` bigint UNSIGNED AUTO_INCREMENT NOT NULL

CREATE TABLE `promocao` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `descricao` text,
  `desconto` decimal(5,2) NOT NULL,
  `validade` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `usuario` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `endereco` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



------------------------------------


CREATE TABLE Plataforma (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    segura BOOLEAN NOT NULL
);

CREATE TABLE Categoria (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT
);

CREATE TABLE Produto (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255),
    certificacaoSeguranca BOOLEAN,
    estoque INT NOT NULL,
    categoria_id INT NOT NULL REFERENCES Categoria(id)
);

CREATE TABLE Usuario (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    genero VARCHAR(50),
    telefone VARCHAR(50),
    endereco TEXT
);

CREATE TABLE Pedido (
    id SERIAL PRIMARY KEY,
    idUsuario INT NOT NULL REFERENCES Usuario(id),
    total DECIMAL(10,2),
    status VARCHAR(50),
    data DATE NOT NULL,
    formaPagamento VARCHAR(100),
    enderecoEntrega TEXT
);

-- Tabela associativa entre Pedido e Produto (Pedido tem vários Produtos)
CREATE TABLE Pedido_Produto (
    pedido_id INT NOT NULL REFERENCES Pedido(id),
    produto_id INT NOT NULL REFERENCES Produto(id),
    quantidade INT NOT NULL DEFAULT 1,
    PRIMARY KEY (pedido_id, produto_id)
);

CREATE TABLE Avaliacao (
    id SERIAL PRIMARY KEY,
    produtoId INT NOT NULL REFERENCES Produto(id),
    usuarioId INT NOT NULL REFERENCES Usuario(id),
    nota INT NOT NULL CHECK(nota BETWEEN 0 AND 5),
    comentario TEXT,
    data_hora TIMESTAMP NOT NULL
);

CREATE TABLE Contato (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    dataContato DATE NOT NULL
);

CREATE TABLE Promocao (
    id SERIAL PRIMARY KEY,
    descricao TEXT,
    desconto DECIMAL(5,2) NOT NULL,
    validade DATE
);

-- Relacionamento N:N entre Promoção e Produto
CREATE TABLE Promocao_Produto (
    promocao_id INT NOT NULL REFERENCES Promocao(id),
    produto_id INT NOT NULL REFERENCES Produto(id),
    PRIMARY KEY (promocao_id, produto_id)
);

-- Relacionamento N:N entre Plataforma e Categoria
CREATE TABLE Plataforma_Categoria (
    plataforma_id INT NOT NULL REFERENCES Plataforma(id),
    categoria_id INT NOT NULL REFERENCES Categoria(id),
    PRIMARY KEY (plataforma_id, categoria_id)
);

-- Relacionamento N:N entre Plataforma e Produto
CREATE TABLE Plataforma_Produto (
    plataforma_id INT NOT NULL REFERENCES Plataforma(id),
    produto_id INT NOT NULL REFERENCES Produto(id),
    PRIMARY KEY (plataforma_id, produto_id)
);

-- Tabela principal do carrinho (um por usuário)
CREATE TABLE Carrinho (
    id SERIAL PRIMARY KEY,
    usuario_id INT NOT NULL REFERENCES Usuario(id),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Itens do carrinho (vários produtos no mesmo carrinho)
CREATE TABLE Carrinho_Item (
    id SERIAL PRIMARY KEY,
    carrinho_id INT NOT NULL REFERENCES Carrinho(id) ON DELETE CASCADE,
    produto_id INT NOT NULL REFERENCES Produto(id),
    quantidade INT NOT NULL CHECK (quantidade > 0),
    adicionado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
