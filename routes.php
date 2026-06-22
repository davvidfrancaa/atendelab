<?php
$controller = $_GET['controller'] ?? $_POST['controller'] ?? 'home';
$action     = $_GET['action'] ?? $_POST['action'] ?? 'index';

switch ($controller) {
    case 'pessoas':
        require_once __DIR__ . '/controlles/PessoasController.php';
        $pessoasController = new PessoasController();
        if ($action === 'listar') $pessoasController->listar();
        if ($action === 'buscarPorId') $pessoasController->buscarPorId();
        if ($action === 'cadastrar') $pessoasController->cadastrar();
        if ($action === 'atualizar') $pessoasController->atualizar();
        if ($action === 'inativar') $pessoasController->inativar();
        break;

    case 'tipos_atendimentos':
        require_once __DIR__ . '/controlles/TiposAtendimentosController.php';
        $taController = new TiposAtendimentosController();
        if ($action === 'listar') $taController->listar();
        if ($action === 'buscarPorId') $taController->buscarPorId();
        if ($action === 'cadastrar') $taController->cadastrar();
        if ($action === 'atualizar') $taController->atualizar();
        if ($action === 'inativar') $taController->inativar();
        break;

    case 'atendimentos':
        require_once __DIR__ . '/controlles/AtendimentosController.php';
        $atendimentosController = new AtendimentosController();
        if ($action === 'listar') $atendimentosController->listar();
        if ($action === 'buscarPorId') $atendimentosController->buscarPorId();
        if ($action === 'cadastrar') $atendimentosController->cadastrar();
        if ($action === 'iniciar') $atendimentosController->iniciar();
        if ($action === 'concluir') $atendimentosController->concluir();
        break;

    default:
        echo json_encode(['erro' => 'Rota não encontrada.']);
        break;
}