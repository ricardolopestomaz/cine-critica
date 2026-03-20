-- 1. Criação do Banco de Dados
CREATE DATABASE IF NOT EXISTS cine_critica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cine_critica;

-- 2. Tabela de Usuário
CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(100) NOT NULL,
    email_usuario VARCHAR(100) NOT NULL UNIQUE,
    senha_usuario VARCHAR(255) NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabela de Filme
CREATE TABLE IF NOT EXISTS filme (
    id_filme INT AUTO_INCREMENT PRIMARY KEY,
    titulo_filme VARCHAR(150) NOT NULL,
    ano_lancamento YEAR,
    duracao_minutos SMALLINT UNSIGNED,
    url_poster VARCHAR(255),          /* Foto vertical (Home) */
    url_banner VARCHAR(255),          /* Foto horizontal (Detalhes) */
    url_trailer VARCHAR(255),         /* Link do YouTube/Vimeo */
    resumo VARCHAR(255),              /* Texto curto para o card */
    sinopse TEXT,                     /* Texto longo */
    diretor VARCHAR(100),
    elenco TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tabela de Genero
CREATE' TABLE IF NOT EXISTS genero (
    id_genero INT AUTO_INCREMENT PRIMARY KEY,
    nome_genero VARCHAR(50) NOT NULL
);

-- 5. Tabela de Relacionamento Filme-Genero
CREATE TABLE IF NOT EXISTS filme_genero (
    id_filme INT,
    id_genero INT,
    PRIMARY KEY (id_filme, id_genero), 
    FOREIGN KEY (id_filme) REFERENCES filme(id_filme) ON DELETE CASCADE,
    FOREIGN KEY (id_genero) REFERENCES genero(id_genero) ON DELETE CASCADE
);

-- 6. Tabela de Avaliações (Votação e Crítica)
CREATE TABLE IF NOT EXISTS avaliacao (
    id_avaliacao INT AUTO_INCREMENT PRIMARY KEY,
    id_filme INT NOT NULL,
    id_usuario INT NOT NULL,
    nota TINYINT UNSIGNED NOT NULL CHECK (nota >= 0 AND nota <= 5),
    critica TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_filme) REFERENCES filme(id_filme) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    UNIQUE KEY uc_usuario_filme (id_usuario, id_filme)
);

-- 7. Inserção de Gêneros Padrão
INSERT INTO genero (nome_genero) VALUES 
('Ação'), ('Aventura'), ('Animação'), ('Biografia'), ('Comédia'), 
('Crime'), ('Documentário'), ('Drama'), ('Família'), ('Fantasia'), 
('Ficção Científica'), ('Guerra'), ('Musical'), ('Mistério'), 
('Romance'), ('Terror'), ('Suspense');