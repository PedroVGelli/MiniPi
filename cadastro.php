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
<div class="container-cadastro">
  <h1>Cadastro</h1>
  <form method="post" action="">
    <label for="nascimento">Data de Nascimento:</label>
    <input type="date" id="nascimento" name="nascimento" required><br>

    <label for="cpf">CPF:</label>
    <input type="text" id="cpf" name="CPF" required><br>

    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required><br>

    <label for="telefone">Telefone:</label>
    <input type="text" id="telefone" name="telefone" required><br>

    <button type="submit">Confirme seu cadastro</button>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
  </form>
</div>
</body>
<?php require 'footer.php'?>

