    <?php @require 'header.php' ?>
    <?php
session_start();
require 'config.php';

// Verificar login e perfil
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('SELECT perfil FROM usuarios WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user['perfil'] !== 'normal') {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>


    <h1>Olá Usuário Normal!</h1>
    <a href="logout.php">Sair</a>
</body>
</html>
</html>