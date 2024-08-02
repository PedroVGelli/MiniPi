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

// Processar inclusão de produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inclusão de um novo produto
    if (isset($_POST['adicionar'])) {
        $nome = $_POST['nome'];
        $tamanho = $_POST['tamanho'];
        $material = $_POST['material'];
    

        $stmt = $pdo->prepare('INSERT INTO produtos (nome, material,tamanho) VALUES (?, ?, ?)');
        $stmt->execute([$nome, $material, $tamanho]);
    }

    // Exclusão de produtos selecionados
    if (isset($_POST['excluir'])) {
        $ids = $_POST['id_prod'];
        $ids = implode(',', array_map('intval', $ids));
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_prod IN ($ids)");
        $stmt->execute();
    }

    // Edição de informações de um 
    if (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $tamanho = $_POST['tamanho'];
        $material = $_POST['material'];

        $stmt = $pdo->prepare('UPDATE produtos SET nome = ?, tamanho = ? WHERE id_prod = ?');
        $stmt->execute([$nome, $tamanho, $id]);
    }
}
?>

<?php require 'header.php'?>
    <h1>Gerenciamento de Produtos</h1>

    <!-- Formulário para adicionar um novo produto -->
    <h2>Adicionar Produto</h2>
    <form method="post" action="">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>tamanho:</label>
        <input type="text" name="tamanho" required>
        <label>material:</label>
        <input type="text" name="material" required>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <!-- Tabela de usuários com opções de edição e exclusão -->
    <h2>Produtos</h2>
    <form method="post" action="">
        <table>
            <tr>
                <th>Selecionar</th>
                <th>Nome</th>
                <th>tamanhos</th>
                <th>Material</th>
            </tr>
            <?php
            // Listar todos os usuários
            $stmt = $pdo->query('SELECT * FROM produtos');
            while ($row = $stmt->fetch()) {
                echo "<tr>
                    <td><input type='checkbox' name='ids[]' value='{$row['id_prod']}'></td>
                    <td>{$row['nome']}</td>
                    <td>{$row['tamanho']}</td>
                    <td>
                        <!-- Formulário de edição -->
                        <form method='post' action='' style='display:inline'>
                            <input type='hidden' name='id_prod' value='{$row['id_prod']}'>
                            <input type='text' name='nome' value='{$row['nome']}' required>
                            <input type='text' name='tamanho' value='{$row['tamanho']}' required>
                            <input type='text' name='material' value='{$row['material']}'
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
</html>
