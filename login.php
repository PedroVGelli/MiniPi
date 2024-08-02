
    <?php require 'header.php'?>
    <?php
session_start();
require 'config.php';

// Processar o login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar se o login é para o administrador
    if ($email === 'admin@exemplo.com' && hash('sha256', $senha) === hash('sha256', 'senha_admin')) {
        $_SESSION['user_id'] = 1; // ID do administrador
        header('Location: crud.php');
        exit();
    } else {
        // Buscar informações do usuário normal
        $stmt = $pdo->prepare('SELECT id, perfil, senha FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verificar se a senha está correta
        if ($user && hash('sha256', $senha) === $user['senha']) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: crud.php');
            exit();
        } else {
            $error = 'Email ou senha incorretos!';
        }
    }
}
?>
    <div class="login-container">
  <h1>Login</h1>
  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required>
    <button type="submit">Entrar</button>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
  </form>
  <p class="register-link">Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
</div>

</body>


</html>