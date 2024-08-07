<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para ver a wishlist.');
}

$user_id = $_SESSION['user_id'];

// Buscar produtos da wishlist
$stmt = $pdo->prepare('SELECT p.* FROM wishlist w INNER JOIN produtos p ON w.produto_id = p.id_prod WHERE w.user_id = ?');
$stmt->execute([$user_id]);
$produtos_wishlist = $stmt->fetchAll();
?>

<?php require 'header.php'; ?>

<section class="wishlist">
    <div class="container">
        <h1>Minha Lista de desejos</h1>
        <?php if (empty($produtos_wishlist)): ?>
            <p>Sua Lista de desejos está vazia.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($produtos_wishlist as $produto): ?>
                    <li>
                        <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
                        <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                        <a href="produto.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>">Ver Produto</a>
                        <?php if (!empty($produto['imagens'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($produto['imagens']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                    <?php else: ?>
                        <img src="img/default.png" alt="Imagem não disponível">
                    <?php endif; ?>
                       
                        <!-- Opção de remover da lista -->
                        <form method="post" action="remover_wishlist.php">
                            <input type="hidden" name="produto_id" value="<?php echo htmlspecialchars($produto['id_prod']); ?>">
                            <button type="submit">Remover</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>

<?php require 'footer.php'; ?>
</html>
