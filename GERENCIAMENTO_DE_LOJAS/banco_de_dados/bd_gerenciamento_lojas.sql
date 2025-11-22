CREATE DATABASE GERENCIAMENTO_LOJAS;

USE GERENCIAMENTO_LOJAS;

CREATE TABLE forma_pagamento(
	id_pagamento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	tipo VARCHAR(20) NOT NULL
);

CREATE TABLE fornecedor(
	id_fornecedor INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(30) NOT NULL,
    tipo VARCHAR(30) NOT NULL,
    telefone CHAR(14) NOT NULL,
    cnpj CHAR(18) NOT NULL,
    email VARCHAR(50) NOT NULL,
    endereco VARCHAR(80) NOT NULL,
    obs VARCHAR(100)
);

CREATE TABLE loja(
	id_loja INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(30) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    telefone CHAR(14) NOT NULL,
    cnpj CHAR(18),
    email VARCHAR(50) NOT NULL,
    endereco VARCHAR(80) NOT NULL,
    login VARCHAR(40) UNIQUE NOT NULL,
    senha VARCHAR(256) NOT NULL,
    obs VARCHAR(100)
);

CREATE TABLE estoque(
	id_estoque INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_loja INT NOT NULL, /*PK*/
    identificacao VARCHAR(40) NOT NULL,
    obs VARCHAR(100),
    FOREIGN KEY (id_loja) REFERENCES loja(id_loja)
);

CREATE TABLE produto(
	id_produto INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_fornecedor INT NOT NULL, /*PK*/
    id_estoque INT NOT NULL, /*PK*/
    nome VARCHAR(30) NOT NULL,
    marca VARCHAR(30) NOT NULL,
    tipo VARCHAR(30) NOT NULL,
	dt_validade DATE NOT NULL,
    preco_venda DECIMAL(4,2) NOT NULL,
    preco_varejo DECIMAL(4,3) NOT NULL,
    obs VARCHAR(100),
    FOREIGN KEY (id_fornecedor) REFERENCES fornecedor(id_fornecedor),
    FOREIGN KEY (id_estoque) REFERENCES estoque(id_estoque)
);

CREATE TABLE compra(
	id_compra INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_cliente VARCHAR(50) NOT NULL,
    id_produto INT NOT NULL,/*PK*/
	id_pagamento INT NOT NULL, /*PK*/
    preco_total DECIMAL(4,2) NOT NULL,
    dt_compra DATE NOT NULL,
    obs VARCHAR(100),
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto),
    FOREIGN KEY (id_pagamento) REFERENCES forma_pagamento(id_pagamento)
);

CREATE TABLE item_compra(
	id_iten_compra INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_compra INT NOT NULL, /*PK*/
    id_produto INT NOT NULL, /*PK*/
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(4,2) NOT NULL,
    FOREIGN KEY (id_compra) REFERENCES compra(id_compra),
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto)
);

CREATE TABLE cargo(
	id_cargo INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(200) NOT NULL
);

CREATE TABLE permicao(
	id_permicao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(100)
);

CREATE TABLE cargo_permicao(
	id_cargo_permicao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_cargo INT NOT NULL, /*PK*/
    id_permicao INT NOT NULL, /*PK*/
    FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo),
    FOREIGN KEY (id_permicao) REFERENCES permicao(id_permicao)
);

CREATE TABLE funcionario(
	id_funcionario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_cargo INT NOT NULL,
    id_loja INT NOT NULL,
    nome VARCHAR(30) NOT NULL,
    telefone CHAR(14) NOT NULL,
    login VARCHAR(40) UNIQUE NOT NULL,
    senha VARCHAR(256) NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_saida TIME NOT NULL,
    obs VARCHAR(100),
    FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo),
    FOREIGN KEY (id_loja) REFERENCES loja(id_loja)
);

CREATE TABLE funcionario_permicao(
	id_funcionario_permicao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	id_funcionario INT NOT NULL, /*PK*/
    id_permicao INT NOT NULL, /*PK*/
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario),
    FOREIGN KEY (id_permicao) REFERENCES permicao(id_permicao)
);

CREATE TABLE estoque_produto (
    id_estoque_produto INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_estoque INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    FOREIGN KEY (id_estoque) REFERENCES estoque(id_estoque),
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto)
);

INSERT INTO loja (nome, tipo, telefone, cnpj, email, endereco, login, senha, obs) 
VALUES ("Teste jose", "teste", "(13) 68764-335","32.432.426/5364-36", "email@gmail.com", "Internet","jose.loja", "202cb962ac59075b964b07152d234b70", "Nenhuma");

INSERT INTO estoque (id_loja, identificacao, obs)
VALUES 
(1, 'Estoque Principal', 'Nenhuma'),
(1, 'Estoque Secundario', 'Nenhuma');

INSERT INTO fornecedor (nome, tipo, telefone, cnpj, email, endereco, obs)
VALUES 
('Fornecedor Central', 'Alimentos e Bebidas', '(11) 98888-7777', 
 '12.345.678/0001-99', 'contato@central.com', 'Rua Alfa, 123', 'Nenhuma');
 
 INSERT INTO cargo (nome, descricao) VALUES
('Gerente', 'Supervisiona toda a loja, produtos, funcionários e relatórios'),
('Caixa', 'Responsável pelo atendimento e vendas no caixa'),
('Estoquista', 'Gerencia e consulta o estoque de produtos'),
('Repositor', 'Reabastece produtos no estoque'),
('Fiscal', 'Audita e visualiza informações do sistema');

INSERT INTO permicao (nome, descricao) VALUES
('Gerenciar produtos', 'Adicionar, editar ou remover produtos do estoque'),
('Vender produtos', 'Registrar vendas no caixa'),
('Visualizar estoque', 'Consultar quantidade e localização dos produtos'),
('Gerenciar funcionários', 'Cadastrar, editar ou remover funcionários'),
('Gerenciar lojas', 'Cadastrar e editar informações de lojas'),
('Emitir relatórios', 'Gerar relatórios de vendas e estoque'),
('Gerenciar fornecedores', 'Cadastrar, editar ou remover fornecedores'),
('Realizar compras', 'Registrar compras de produtos'),
('Atualizar estoque', 'Alterar quantidade de produtos no estoque');

-- GERENTE - TODAS AS PERMICOES
INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9);

-- CAIXA - VENDER E EMITIR RELATÓRIOS
INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES
(2,2),
(2,6);

/* ESTOQUISTA - 
Gerenciar produtos
Visualizar estoque
Atualizar estoque
*/
INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES
(3,1),
(3,3),
(3,9);

-- FISCAL VISUALIZAR ESTOQUE, EMITIR RELATORIOS
INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES
(5,3),
(5,6);

-- REPOSITOR - ATUALIZAR ESTOQUE
INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES
(4,9);


INSERT INTO funcionario (id_cargo, id_loja, nome, telefone, login, senha, hora_entrada, hora_saida, obs)
VALUES (1, 1, "Jose", "(12) 31213-123", "jose.adm","202cb962ac59075b964b07152d234b70", "05:28:00", "02:20:00", "Nenhuma");


INSERT INTO produto (
    id_fornecedor, id_estoque, nome, marca, tipo,
    dt_validade, preco_venda, preco_varejo, obs
)
VALUES
(1, 1, 'Arroz Tipo 1', 'Tio João', 'Alimento', '2026-01-10', 25.90, 20.500, 'Nenhuma'),
(1, 1, 'Feijão Carioca', 'Kicaldo', 'Alimento', '2025-11-20', 9.80, 7.300, 'Nenhuma'),
(1, 2, 'Refrigerante Cola 2L', 'Coca-Cola', 'Bebida', '2025-06-01', 9.50, 7.200, 'Nenhuma'),
(1, 2, 'Água Mineral 500ml', 'Crystal', 'Bebida', '2026-03-15', 2.50, 1.800, 'Nenhuma'),
(1, 1, 'Macarrão Espaguete', 'Renata', 'Alimento', '2026-02-05', 4.90, 3.600, 'Nenhuma');

-- INSIRIR VENDA 
INSERT INTO venda (
    id_funcionario,
    id_cliente,
    id_loja,
    valor,
    data_venda
) VALUES (
    1,          -- funcionário que realizou a venda
    1,          -- cliente que comprou
    1,          -- loja onde ocorreu a venda
    349.90,     -- valor total
    NOW()       -- data/hora automática
);

SELECT * FROM loja;
SELECT * FROM estoque;
SELECT login, senha FROM loja;
SELeCt * FROM cargo;
SELECT * FROM funcionario;
SELECT * FROM produto;

DELETE FROM funcionario WHERE id_funcionario = 10;






