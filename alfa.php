<?php
require 'config.php';
class GerenciadorUsuarios {
    private $pdo;

    // Construtor da classe, estabelece a conexão com o banco de dados
    public function __construct() {
        $dsn = 'mysql:host=localhost;usuarios_db;charset=utf8';
        $usuario = 'root';
        $senha = '';

        try {
            $this->pdo = new PDO($dsn, $usuario, $senha);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Conexão falhou: ' . $e->getMessage();
        }
    }

    // Adiciona um novo usuário ao banco de dados
    public function adicionarUsuario(Usuario $usuario) {
        $sql = 'INSERT INTO usuarios (nascimento, CPF, nome, senha, , email) VALUES (:nascimento, :CPF, :nome, :senha, :email)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nascimento' => $usuario->getNascimento(),
            ':CPF' => $usuario->getCPF(),
            ':nome' => $usuario->getNome(),
            ':senha' => password_hash($usuario->getSenha(), PASSWORD_DEFAULT), // Criptografar senha
            ':email' => $usuario->getEmail()
        ]);
    }
}
