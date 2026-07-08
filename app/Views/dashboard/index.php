<?php
require_once __DIR__ . '/../../Middleware/auth.php';
verificarAutenticacao();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>AtendeLab - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">AtendeLab - Painel</span>
        <span class="text-white">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?> (<?= $_SESSION['usuario_perfil'] ?>) | 
            <a href="index.php?controller=auth&action=logout" class="btn btn-danger btn-sm">Sair</a>
        </span>
    </div>
</nav>
<div class="container mt-4">
    <h1>Dashboard Principal</h1>
    <p>Área interna protegida com sucesso por controle de sessões PHP.</p>
</div>
</body>
</html>