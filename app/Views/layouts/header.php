<?php
require_once __DIR__ . '/config-view.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AtendeLab - Sistema de Atendimentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/css/style.css" rel="stylesheet">
    <script src="<?= BASE_URL ?>/public/assets/js/api.js"></script>
</head>
<body>

<nav class="navbar navbar-dark bg-dark sticky-top p-3 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?= BASE_URL ?>/public/index.php?controller=dashboard&action=index">
        🔬 AtendeLab
    </a>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3 text-dangerfw-bold" href="<?= BASE_URL ?>/public/index.php?controller=auth&action=logout">Sair</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse p-3 border-end" style="min-height: calc(100vh - 72px);">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/public/index.php?controller=dashboard&action=index">
                             Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/public/index.php?controller=pessoas&action=listar">
                            👥 Pessoas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/public/index.php?controller=tipos&action=listar">
                            🏷️ Tipos de Atendimento
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/public/index.php?controller=atendimentos&action=listar">
                            Atendimentos
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">