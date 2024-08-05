<?php
session_start();
require 'config.php';

// Verificar se o usuário está logado e é um administrador
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT perfil FROM usuarios WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Se não for administrador, redirecionar para login
if ($user['perfil'] !== 'administrador') {
    header('Location: index.php');
    exit();
}

// Processar inclusão de usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inclusão de um novo usuário
    if (isset($_POST['adicionar'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = hash('sha256', $_POST['senha']);

        $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nome, $email, $senha, 'normal']);
    }

    // Exclusão de usuários selecionados
    if (isset($_POST['excluir'])) {
        $ids = $_POST['ids'];
        $ids = implode(',', array_map('intval', $ids));
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id IN ($ids)");
        $stmt->execute();
    }

    // Edição de informações de um usuário
    if (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, email = ? WHERE id = ?');
        $stmt->execute([$nome, $email, $id]);
    }
}
?>

<?php @require 'header.php'?>
    <h1>Gerenciamento de Usuários</h1>

    <!-- Formulário para adicionar um novo usuário -->
    <h2>Adicionar Usuário</h2>
    <form method="post" action="">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>
    <a href="cadastrarprod.php">Cadastrar produtos</a>
    <!-- Tabela de usuários com opções de edição e exclusão -->
    <h2>Usuários</h2>
    <form method="post" action="">
        <table>
            <tr>
                <th>Selecionar</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php
            // Listar todos os usuários
            $stmt = $pdo->query('SELECT * FROM usuarios');
            while ($row = $stmt->fetch()) {
                echo "<tr>
                    <td><input type='checkbox' name='ids[]' value='{$row['id']}'></td>
                    <td>{$row['nome']}</td>
                    <td>{$row['email']}</td>
                    <td>
                        <!-- Formulário de edição -->
                        <form method='post' action='' style='display:inline'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <input type='text' name='nome' value='{$row['nome']}' required>
                            <input type='email' name='email' value='{$row['email']}' required>
                            <button type='submit' name='editar'>Editar</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </table>
        <!-- Botão para excluir os usuários selecionados -->
        <button type="submit" name="excluir">Excluir Selecionados</button>
    </form>
</body>
<?php require 'footer.php'?>

</html>