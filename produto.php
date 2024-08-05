<?php
require 'header.php'
?>

<?php
session_start();
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produto = $_GET['id'];
    //verificações
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id_prod = ?');
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch();
    
    if (!$produto) {
        die('Produto não encontrado.');
    }
} else {
    die('ID do produto inválida.');
}
?>
    <section class="produto-detalhe">
        <div class="container">
            <div class="produto-imagem">
                <?php if (!empty($produto['imagens'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($produto['imagens']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                <?php else: ?>
                    <img src="img/default.png" alt="Imagem não disponível">
                <?php endif; ?>
            </div>
            <div class="produto-info">
                <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
                <p>Descrição: <?php echo htmlspecialchars($produto['DESCRICAO']); ?></p>
                <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                <p>Categoria: <?php echo htmlspecialchars($produto['categoria']); ?></p>
                <a href="cadastrarprod.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>">Editar</a>
                <a href="excluir.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>">Excluir</a>
            </div>
        </div>
    </section>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> LudoFashion</p>
    </footer>
</body>
</html>



</main>
</body>