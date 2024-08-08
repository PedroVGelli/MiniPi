<?php
session_start();
require 'config.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Buscar informações do usuário
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->execute([$user_id]);
$user_info = $stmt->fetch();

// Processar edição de informações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    
    // Verificar se a senha foi fornecida e atualizá-la
    if (!empty($_POST['senha'])) {
        $senha = hash('sha256', $_POST['senha']);
        $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, email = ?, telefone = ?, senha = ? WHERE id = ?');
        $stmt->execute([$nome, $email, $telefone, $senha, $user_id]);
    } else {
        // Atualizar apenas os campos não sensíveis
        $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id = ?');
        $stmt->execute([$nome, $email, $telefone, $user_id]);
    }
    
    // Redirecionar para evitar reenvio de formulário
    header('Location: perfil.php');
    exit();
}
?>

<?php require 'header.php' ?>
<div class="container-perfil">
    <h1 class="titulo-pagina">Meu perfil</h1>

    <div class="perfil-container">
        <div class="formulario-container">
            <!-- Formulário para editar as informações do usuário -->
            <form method="post" action="" class="formulario-perfil">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user_info['nome']); ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
                
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($user_info['telefone']); ?>" required>
                
                <label for="senha">Digite sua nova senha:</label>
                <input type="password" id="senha" name="senha">
                
                <button type="submit" class="botao-salvar">Gravar</button>
            </form>
        </div>

        <div class="informacoes-usuario">
            <h2 class="titulo-informacoes">Gerenciar e proteger sua conta</h2>
            <ul>
                <li><strong>Nome:</strong> <?php echo htmlspecialchars($user_info['nome']); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></li>
                <li><strong>Telefone:</strong> <?php echo htmlspecialchars($user_info['telefone']); ?></li>
            </ul>
        </div>
    </div>
</div>

<?php require 'footer.php' ?>
</body>
</html>
