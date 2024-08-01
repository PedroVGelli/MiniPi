<?php
class Usuario {
    private $conexao;

    // Construtor da classe que inicializa a conexão com o banco de dados
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    // Método para adicionar um usuário no banco de dados
    public function adicionar($nome, $telefone, $email, $senha, $CPF, $nascimento) {
        $senhaHash = hash('sha256', $senha); // Criptografa a senha usando SHA-256
        $sql = "INSERT INTO usuarios (nascimento, telefone, nome, CPF, email, senha) VALUES (:nascimento, :telefone, :nome, :CPF, :email, :senha)";
        
        // Prepara a consulta SQL
        $stmt = $this->conexao->prepare($sql);
        // Associa os valores aos parâmetros da consulta
        $stmt->bindParam(':nascimento', $nascimento);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':CPF', $CPF);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash); // Usa a senha criptografada
        
        // Executa a consulta e retorna o resultado
        return $stmt->execute();
    }

    // Método para listar todos os usuarios do banco de dados
    public function listar() {
        // SQL para selecionar todos os usuarios
        $sql = "SELECT * FROM usuarios";
        // Executa a consulta SQL
        $stmt = $this->conexao->query($sql);
        // Retorna todos os resultados da consulta como um array associativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para editar um perfil no banco de dados
    public function editar($id, $CPF, $email, $telefone, $nome, $senha, $nascimento) {
        $senhaHash = hash('sha256', $senha); // Criptografa a senha usando SHA-256
        
        $sql = "UPDATE usuarios SET nascimento = :nascimento, CPF = :CPF, nome = :nome, telefone = :telefone, email = :email, senha = :senha WHERE id = :id";
        // Prepara a consulta SQL
        $stmt = $this->conexao->prepare($sql);

        // Associa os valores aos parâmetros da consulta
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nascimento', $nascimento);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':CPF', $CPF);
        $stmt->bindParam(':senha', $senhaHash); // Usa a senha criptografada
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);

        // Executa a consulta e retorna o resultado
        return $stmt->execute();
    }

    // Método para deletar perfis selecionados
    public function deletar($ids) {
        // SQL para deletar os usuarios cujos IDs estão no array $ids
        $sql = "DELETE FROM usuarios WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
        // Prepara a consulta SQL
        $stmt = $this->conexao->prepare($sql);
        // Executa a consulta e retorna o resultado
        return $stmt->execute();
    }

    // Método para verificar a senha fornecida
    public function verificarSenha($senhaFornecida, $senhaHash) {
        return hash('sha256', $senhaFornecida) === $senhaHash;
    }
}
?>
