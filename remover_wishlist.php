<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para remover itens da wishlist.');
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['produto_id'])) {
    $produto_id = $_POST['produto_id'];

    // Remover da wishlist
    $stmt = $pdo->prepare('DELETE FROM wishlist WHERE user_id = ? AND produto_id = ?');
    $stmt->execute([$user_id, $produto_id]);

    header('Location: wishlist.php');
    exit;
}
?>
