<?php
session_start();
require 'config.php';

// Verificar se o usuário está logado e é um administrador
$is_admin = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT perfil FROM usuarios WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $is_admin = $user['perfil'] === 'administrador';
}

// Buscar produtos do banco de dados
$stmt = $pdo->query('SELECT * FROM produtos');
$produtos = $stmt->fetchAll();
?>

<?php require 'header.php'; ?>

<section class="cat-cat">
    <div class="bord">
        <div class="retangulo">
            <h5 class="cat-desc">Categorias</h5>
        </div>
        <div class="triangulo"></div>
        <div class="btn-cat">
            <!-- Categorias -->
            <div>
                <input type="radio" name="item" id="vestidos" value="vestidos" onclick="filtrarProdutos('vestidos')">
                <label for="vestidos">Vestidos</label>
            </div>
            <div>
                <input type="radio" name="item" id="calcados" value="calcados" onclick="filtrarProdutos('calcados')">
                <label for="calcados">Calçados</label>
            </div>
            <div>
                <input type="radio" name="item" id="roupas-intimas" value="roupas-intimas" onclick="filtrarProdutos('roupas-intimas')">
                <label for="roupas-intimas">Roupas Íntimas</label>
            </div>
            <div>
                <input type="radio" name="item" id="saias" value="saias" onclick="filtrarProdutos('saias')">
                <label for="saias">Saias</label>
            </div>
            <div>
                <input type="radio" name="item" id="cosmeticos" value="cosmeticos" onclick="filtrarProdutos('cosmeticos')">
                <label for="cosmeticos">Cosméticos</label>
            </div>
            <div>
                <input type="radio" name="item" id="bolsas" value="bolsas" onclick="filtrarProdutos('bolsas')">
                <label for="bolsas">Bolsas</label>
            </div>
            <div>
                <input type="radio" name="item" id="todos" value="todos" onclick="filtrarProdutos('todos')" checked>
                <label for="todos">Todos</label>
            </div>
        </div>
    </div>
    
    <div class="cat-prod">
        <?php foreach ($produtos as $produto): ?>
            <div class="produto <?php echo htmlspecialchars($produto['categoria']); ?>">
                <?php if (!empty($produto['imagens'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($produto['imagens']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                <?php else: ?>
                    <img src="img/default.png" alt="Imagem não disponível">
                <?php endif; ?>
                <a href="produto.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>">
                    <?php echo htmlspecialchars($produto['nome']); ?><br>
                    R$: <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                    <span class="material-symbols-outlined">favorite</span>
                </a>
                <?php if ($is_admin): ?>
                    <a href="cadastrarprod.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>">Editar</a>
                    <a href="cadastrarprod.php?id=<?php echo htmlspecialchars($produto['id_prod']); ?>">Excluir</a>
                    <a href="cadastrarprod.php">Adicionar Novo Produto</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <h5>Conheça nosso catálogo de produtos</h5>
        <p>Os mais variados produtos femininos com os melhores preços do mercado!</p>
    </div>
</section>

</body>
<?php require 'footer.php'?>
</html>
