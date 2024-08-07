<?php
session_start();
require 'config.php';

// Verificar se a ID do produto está definida e é um número válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produto = $_GET['id'];
    
    // Preparar a consulta para buscar o produto
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id_prod = ?');
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch();
    
    if (!$produto) {
        die('Produto não encontrado.');
    }
} else {
    die('ID do produto inválida.');
}

// Verificar se o usuário está logado e é um administrador
$is_admin = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT perfil FROM usuarios WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $is_admin = $user['perfil'] === 'administrador';
}
if (isset($_SESSION['user_id'])) {
    // Lógica para adicionar à wishlist
    if (isset($_POST['adicionarwish'])) {
        $stmt = $pdo->prepare('INSERT INTO wishlist (user_id, produto_id) VALUES (?, ?)');
        $stmt->execute([$user_id, $id_produto]);
    }
}
?>

<?php require 'header.php'; ?>

<section class="produto-detalhe">
    <div class="container">
        <div class="produto-imagem">
            <div class="imagens-pequenas">
                <?php if (!empty($produto['imagens'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($produto['imagens']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                <?php else: ?>
                    <img src="img/default.png" alt="Imagem não disponível">
                <?php endif; ?>
            </div>
            <div class="imagem-grande">
                <?php if (!empty($produto['imagens'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($produto['imagens']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                <?php else: ?>
                    <img src="img/default.png" alt="Imagem não disponível">
                <?php endif; ?>
            </div>
        </div>
        <div class="produto-info">
            <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
            <div class="preco">
                <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            </div>
            <div class="variacoes">
                <p>Escolha a sua variação:</p>
                <button>PP</button>
                <button>P</button>
                <button>M</button>
                <button>G</button>
                <button>GG</button>
            </div>
            <div class="descricao">
                <p><?php echo htmlspecialchars($produto['DESCRICAO']); ?></p>
                <p>Informações Adicionais:</p>
                <ul>
                    <li>Tamanho: <?php echo htmlspecialchars($produto['tamanho']); ?></li>
                    <li>Material: <?php echo htmlspecialchars($produto['material']); ?></li>
                    <li>Marca: LudoFashion</li>
                </ul>
            </div>
            <?php if ($is_admin): ?>
                <a href="cadastrarprod.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>">Editar</a>
                <a href="cadastrarprod.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>">Excluir</a>
            <?php else: ?>
                <a href="comprar.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>" class="btn-comprar">Comprar</a>
                <a href="wishlist.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>" class="btn-wishlist">Ir para a lista de desejos</a>
                <form method="post">
                    <button type="submit" name="adicionarwish" class="btn-wishlist">Adicionar à lista de desejos</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>
</body>
</html>
