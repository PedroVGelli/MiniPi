
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
  <?php @require 'header.php'?>
  <h1>Olá Usuário Normal!</h1>
        <section class="banner">
         <h4>   
            <div class="txt-banner" >
                <p>PROMOÇÃO</p>
                <p style="font-size: 70px;">50%</p>
                    <p  style="font-size: 70px;">OFF</p>
                    <p>DESCONTO EM TODA A COLEÇÃO DE VERÃO</p>
            </div>
        </h4>  
        </section>
        
        <section class="card" >
            <h1 class="extra">DESCONTO EM TODA A COLEÇÃO DE VERÃO</h1>
            
                     <div class="card-img">
                        <img src="img/jaqueta.png" alt="">
                        <img src="img/modelo.png" alt="">
                        <img src="img/Sapatos.png" alt="">
                        <img src="img/vestido.png" alt="">
                        <img src="img/oculos.png" alt="">
                    </div>
    
        </section>    
    </main>
    <footer>
        <div>
            <a href="catalogo.php">Catalogo </a>
            <a href="Sobre.php">Sobre a loja </a>
        <div>
        <div>
        <p>Aqui na LudoFashion você irá encontrar a melhor variedade de moda e cosmeticos femininos e com os melhores preços do mercado</p>
        </div>
        <a href="logout.php">Sair</a>

    </footer>
    
    


</body>

</html>