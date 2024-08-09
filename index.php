
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

// Buscar produtos do banco de dados
$stmt = $pdo->query('SELECT * FROM produtos');
$produtos = $stmt->fetchAll();
?>

  <?php require 'header.php'?>
    
        <section class="banner">
            <div class="txt-banner">
                <p>PROMOÇÃO</p>
                <p class="discount">50%</p>
                <p class="discount">OFF</p>
                <p>DESCONTO EM TODA A COLEÇÃO DE VERÃO</p>
            </div>
        </section>

        <section class="catalogo">
            <h1 class="extra">Desconto em Toda a Coleção de Verão</h1>
            
            <div class="card-container">
                <div class="card">
                    <img src="img/jaqueta.png" alt="Jaqueta" class="card-img">
                    <h2>Jaqueta</h2>
                </div>
                <div class="card">
                    <img src="img/modelo.png" alt="Macacão" class="card-img">
                    <h2>Macacão</h2>
                </div>
                <div class="card">
                    <img src="img/Sapatos.png" alt="Sapatos" class="card-img">
                    <h2>Sapatos</h2>
                </div>
                <div class="card">
                    <img src="img/vestido.png" alt="Vestido" class="card-img">
                    <h2>Vestido</h2>
                </div>
                <div class="card">
                    <img src="img/oculos.png" alt="Óculos" class="card-img">
                    <h2>Óculos</h2>
                </div>
            </div>
            
        </section>
    </main>
    
</body>
    
    

<?php require 'footer.php'?>

</html>