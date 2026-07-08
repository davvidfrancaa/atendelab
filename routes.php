<?php
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/UsuarioController.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/TiposController.php';

// Define controller e action padrão por query string caso não sejam enviados na URL.
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

if ($controller === 'usuarios') {
    $usuariosController = new UsuariosController();
    
    switch ($action) {
        case 'listar':
            $usuariosController->listar();
            break;
        case 'buscar':
            $usuariosController->buscarPorId();
            break;
        case 'criar':
            $usuariosController->criar();
            break;
        case 'atualizar':
            $usuariosController->atualizar();
            break;
        case 'excluir':
            $usuariosController->excluir();
            break;
        default:
            echo 'Ação de usuários não encontrada.';
            break;
    }

} elseif ($controller === 'pessoas') {
    $pessoasController = new PessoasController();
    switch ($action) {
        case 'listar':
            $pessoasController->listar();
            break;
        case 'listar_api':
            $pessoasController->listar_api();
            break;
        case 'criar':
            $pessoasController->criar();
            break;
        case 'excluir':
            $pessoasController->excluir();
            break;
        default:
            echo 'Ação não encontrada em pessoas.';
            break;
    }
} elseif ($controller === 'atendimentos') {
    $atendimentosController = new AtendimentosController();
    switch ($action) {
        case 'listar':
            $atendimentosController->listar();
            break;
        case 'listar_api':
            $atendimentosController->listar_api();
            break;
        case 'criar':
            $atendimentosController->criar();
            break;
        default:
            echo 'Ação não encontrada em atendimentos.';
            break;
    }
} elseif ($controller === 'dashboard') {
    if ($action === 'index') {
        require_once __DIR__ . '/app/Views/dashboard/index.php';
    } else {
        echo 'Página não encontrada no dashboard.';
    }

} elseif ($controller === 'tipos') {
    $tiposController = new TiposController();
    
    switch ($action) {
        case 'listar':
            $tiposController->listar();
            break;
        case 'listar_api':
            $tiposController->listar_api();
            break;
        default:
            echo 'Ação não encontrada em tipos de atendimento.';
            break;
    }

} else {
    header('Location: index.php?controller=auth&action=login');
    exit();
}