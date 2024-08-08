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
        $preco = $_POST['preco'];
        $categoria = $_POST['categoria'];
        $descricao = $_POST['DESCRICAO'];

        $imagemNome = null;

        // Lida com o upload da foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $fotoTmp = $_FILES['foto']['tmp_name'];
            $imagemNome = uniqid() . '.jpg'; // Gera um nome único para a foto
            $destino = 'uploads/' . $imagemNome;

            // Redimensiona a imagem para 218x148 px
            list($larguraOriginal, $alturaOriginal) = getimagesize($fotoTmp);
            $imagemOriginal = imagecreatefromjpeg($fotoTmp);
            $imagemRedimensionada = imagecreatetruecolor(218, 148);
            imagecopyresampled($imagemRedimensionada, $imagemOriginal, 0, 0, 0, 0, 218, 148, $larguraOriginal, $alturaOriginal);
            imagejpeg($imagemRedimensionada, $destino);
            imagedestroy($imagemOriginal);
            imagedestroy($imagemRedimensionada);
        }

        $stmt = $pdo->prepare('INSERT INTO produtos (nome, material, tamanho,preco,DESCRICAO, categoria, imagens) VALUES (?, ?, ?, ?, ?, ?,?)');
        $stmt->execute([$nome, $material, $tamanho,$preco, $categoria, $descricao,$imagemNome]);
    }

    // Exclusão de produtos selecionados
    if (isset($_POST['excluir'])) {
        $ids = $_POST['ids']; 

        // Verifica se $ids é um array e aplica array_map
        if (is_array($ids)) {
            $ids = array_map('intval', $ids);
            $ids = implode(',', $ids);

            // Remove as imagens dos produtos antes de excluir
            $stmt = $pdo->query("SELECT imagens FROM produtos WHERE id_prod IN ($ids)");
            while ($row = $stmt->fetch()) {
                $imagem = $row['imagens'];
                if ($imagem && file_exists("uploads/$imagem")) {
                    unlink("uploads/$imagem"); // Remove o arquivo da imagem
                }
            }

            // Prepara e executa a exclusão dos produtos
            $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_prod IN ($ids)");
            $stmt->execute();
        } else {
            // Se $ids não for um array, não faz nada ou mostra uma mensagem de erro
            echo "Nenhum produto selecionado para exclusão.";
        }
    }

    // Edição de informações de um produto
    if (isset($_POST['editar'])) {
        $id = $_POST['id_prod']; 
        $nome = $_POST['nome'];
        $tamanho = $_POST['tamanho'];
        $material = $_POST['material'];
        $preco = $_POST['preco'];
        $categoria = $_POST['categoria'];
        $descricao = $_POST['DESCRICAO'];
        $imagemNome = $_POST['imagem_antiga'];

        // Lida com o upload da nova foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $fotoTmp = $_FILES['foto']['tmp_name'];
            $imagemNome = uniqid() . '.jpg'; // Gera um nome único para a foto
            $destino = 'uploads/' . $imagemNome;

            // Redimensiona a imagem para 218x148 px
            list($larguraOriginal, $alturaOriginal) = getimagesize($fotoTmp);
            $imagemOriginal = imagecreatefromjpeg($fotoTmp);
            $imagemRedimensionada = imagecreatetruecolor(218, 148);
            imagecopyresampled($imagemRedimensionada, $imagemOriginal, 0, 0, 0, 0, 218, 148, $larguraOriginal, $alturaOriginal);
            imagejpeg($imagemRedimensionada, $destino);
            imagedestroy($imagemOriginal);
            imagedestroy($imagemRedimensionada);

            // Remove a imagem antiga, se existir
            if ($imagemNome !== $_POST['imagem_antiga'] && file_exists("uploads/{$_POST['imagem_antiga']}")) {
                unlink("uploads/{$_POST['imagem_antiga']}");
            }
        }

        $stmt = $pdo->prepare('UPDATE produtos SET nome = ?, tamanho = ?, material = ?, preco = ?, imagens = ?, DESCRICAO = ?, categoria = ? WHERE id_prod = ?');
        $stmt->execute([$nome, $tamanho, $material,$preco, $imagemNome, $id]);
    }
}
?>

<?php require 'header.php'?>
<h1 class="titulo-pagina">Gerenciamento de Produtos</h1>
<div class="usuario-adm">
<!-- Formulário para adicionar um novo produto -->
<h2>Adicionar Produto</h2>
<form method="post" action="" enctype="multipart/form-data">
    <label>Nome:</label>
    <input type="text" name="nome" required>
    <label>Tamanho:</label>
    <input type="text" name="tamanho" required>
    <label>Material:</label>
    <input type="text" name="material" required>
    <label>Descrição</label>
    <input type="text" name="categoria" required>
    <label>categoria:</label>
    <input type="text" name="DESCRICAO" required>
    <label>Preço:</label>
    <input type="text" name="preco" required>
    <label for="foto">Foto:</label>
    <input type="file" name="foto" id="foto" class="form-control-file" accept="image/jpeg">
    <button type="submit" name="adicionar">Adicionar</button>
</form>

<!-- Tabela de produtos com opções de edição e exclusão -->
<h2>Produtos</h2>
<form method="post" action="">
    <table>
        <tr>
            <th>Selecionar</th>
            <th>Nome</th>
            <th>Tamanho</th>
            <th>Material</th>
            <th>Preço</th>
            <th>Imagem</th>
            <th>Descricao</th>
            <th>Categoria</th>
        </tr>
        <?php
        // Listar todos os produtos
        $stmt = $pdo->query('SELECT * FROM produtos');
        while ($row = $stmt->fetch()) {
            $imagem = $row['imagens'] ? "uploads/{$row['imagens']}" : 'uploads/default.jpg'; // Caminho da imagem ou imagem padrão
            echo "<tr>
                <td><input type='checkbox' name='ids[]' value='{$row['id_prod']}'></td>
                <td>{$row['nome']}</td>
                <td>{$row['tamanho']}</td>
                <td>{$row['material']}</td>
                <td>{$row['preco']}</td>
                <td>{$row['DESCRICAO']}</td>
                <td>{$row['categoria']}</td>
                <td><img src='$imagem' alt='Imagem do produto' style='width: 300px; height: auto;'></td>
                <td>
                    <!-- Formulário de edição -->
                    <form method='post' action='' enctype='multipart/form-data' style='display:inline'>
                        <input type='hidden' name='id_prod' value='{$row['id_prod']}'>
                        <input type='hidden' name='imagem_antiga' value='{$row['imagens']}'>
                        <input type='text' name='nome' value='{$row['nome']}' required>
                        <input type='text' name='preco' value='{$row['nome']}' required>
                        <input type='text' name='tamanho' value='{$row['tamanho']}' required>
                        <input type='text' name='material' value='{$row['material']}' required>
                        <input type='text' name='categoria' value'{$row['categoria']}' required>
                        <input type='text' name'DESCRICAO' value'{$row['DESCRICAO']}' required>
                        <input type='file' name='foto' class='form-control-file' accept='image/jpeg'>
                        <button type='submit' name='editar'>Editar</button>
                    </form>
                </td>
            </tr>";
        }
        ?>
    </table>
    <!-- Botão para excluir os produtos selecionados -->
    <button type="submit" name="excluir">Excluir Selecionados</button>
</form>
    </div>
</body>
<?php require 'footer.php'?>
</html>
