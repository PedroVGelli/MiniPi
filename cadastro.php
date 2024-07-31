
<?php
require 'cadastroclass.php';
require 'gerenciarclass.php';

// Verifica se os dados do formulário foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nascimento = $_POST['nascimento'];
    $CPF = $_POST['CPF'];
    $usuarios = $_POST['nome'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone']

    // Cria um novo objeto Usuario
    $usuario = new usuario($nascimento, $CPF, $usuarios, $senha, $telefone);

    // Gerencia o CRUD usando Gerenciador
    $gerenciadorUsuarios = new GerenciadorUsuarios();
    $gerenciadorUsuarios->adicionarUsuarios($usuario);

    // Redireciona para a página principal
    header('Location: index.php');
    exit;
}

<div class='retangulo3'>
    <h1>Cadastro</h1>
    <form method="post" action="">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Confirme seu cadastro</button>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </form>
 <div>
</body>