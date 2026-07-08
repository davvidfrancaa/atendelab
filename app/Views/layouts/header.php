<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AtendeLab - Sistema Acadêmico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; }
        .sidebar a { color: rgba(255,255,255,0.75); text-decoration: none; }
        .sidebar a:hover { color: #fff; background-color: #343a40; }
        .sidebar a.active { color: #fff; background-color: #0d6efd; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="d-flex align-items-center mb-4 text-white text-decoration-none">
                <i class="fa-solid fa-laptop-code fa-2x me-2"></i>
                <span class="fs-4">AtendeLab</span>
            </div>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="index.php?controller=dashboard&action=index" class="nav-link text-white">
                        <i class="fa-solid fa-chart-pie me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="index.php?controller=atendimentos&action=listar" class="nav-link text-white">
                        <i class="fa-solid fa-headset me-2"></i> Atendimentos
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="index.php?controller=pessoas&action=listar" class="nav-link text-white">
                        <i class="fa-solid fa-users me-2"></i> Pessoas
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="index.php?controller=tipos&action=listar" class="nav-link text-white">
                        <i class="fa-solid fa-tags me-2"></i> Tipos de Atendimento
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <span class="text-white d-block mb-2 small">Logado como: <br><strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?></strong></span>
                <a href="index.php?controller=auth&action=logout" class="btn btn-danger btn-sm w-100">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
                </a>
            </div>
        </nav>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div id="alerta"></div>