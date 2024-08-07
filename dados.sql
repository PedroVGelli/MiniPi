-- Criação do banco de dados
CREATE DATABASE usuarios_db;

-- Seleção do banco de dados
USE usuarios_db;

-- Criação da tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('normal', 'administrador') DEFAULT 'normal',
    CPF INT(15) NOT NULL,
    telefone INT(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserção do administrador inicial
INSERT INTO usuarios (nome, email, senha, perfil, CPF, telefone ) VALUES 
('Administrador', 'admin@exemplo.com', SHA2('senha_admin', 256), 'administrador', '12345678986','98976543211');





CREATE TABLE produtos (
    id_prod INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    material VARCHAR(100) NOT NULL,
    tamanho VARCHAR(5) NOT NULL,
    imagens VARCHAR(300),
    categoria VARCHAR(20),
    DESCRICAO TEXT

    
);

INSERT INTO produtos (nome, material, tamanho) VALUES ("Calça Skinny", "Jeans", "43");


CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    produto_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES usuarios(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id_prod)
);

