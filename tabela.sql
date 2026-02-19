CREATE TABLE users (
    matricula VARCHAR(20) PRIMARY KEY,   -- chave primária
    nome      VARCHAR(100) NOT NULL,     -- nome do usuário
    senha     VARCHAR(255) NOT NULL,     -- senha criptografada (hash)
    is_admin  BOOLEAN DEFAULT FALSE      -- flag para administrador
);
