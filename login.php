
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

    <h1>Login</h1>
    <form method="post" action="">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Entrar</button>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </form>

    <!-- Formulário para login do administrador -->
    <h2>Login do Administrador</h2>
    <form method="post" action="">
        <input type="hidden" name="email" value="admin@exemplo.com">
        <input type="hidden" name="senha" value="senha_admin">
        <button type="submit">Entrar como Administrador</button>
    </form>
</body>
</html>

</html>