<?php
session_start();
require 'config.php';

// Verificar login e perfil
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('SELECT perfil FROM usuarios WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user['perfil'] !== 'administrador') {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrador</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Olá Usuário Administrador!</h1>
    <a href="logout.php">Sair</a>
</body>
</html>