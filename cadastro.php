<?php
require 'conexao.php';
require 'cadastroclass.php';

// Cria a conexão com o banco de dados
$conexao = (new Conexao())->conectar();
// Cria uma instância da classe Usuario
$usuario = new Usuario($conexao);

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $nascimento = $_POST['nascimento'];
    $CPF = $_POST['CPF'];
    $senha = $_POST['senha'];

    // Adiciona o usuario no banco de dados
    $usuario->adicionar($nome, $telefone, $email, $senha, $CPF, $nascimento);

    // Redireciona para a página inicial
    header('Location: index.php');
    exit;
}
?>
<?php require 'header.php'; ?>
<div class='retangulo3'>
    <h1>Cadastro</h1>
    <form method="post" action="">
        <label>Data de Nascimento:</label>
        <input type="date" name="nascimento" required><br>
        
        <label>CPF:</label>
        <input type="text" name="CPF" required><br>
        
        <label>Nome:</label>
        <input type="text" name="nome" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br>
        
        <label>Senha:</label>
        <input type="password" name="senha" required><br>
        
        <label>Telefone:</label>
        <input type="text" name="telefone" required><br>
        
        <button type="submit">Confirme seu cadastro</button>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </form>
</div>
