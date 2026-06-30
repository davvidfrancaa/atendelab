<?php

$controller = $_GET['controller'] ?? $_POST['controller'] ?? 'auth';
$action     = $_GET['action']     ?? $_POST['action']     ?? 'login';

function exigirAutenticacao() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: /atendelab_/public/index.php?controller=auth&action=login');
        exit;
    }
}

$ds = DIRECTORY_SEPARATOR;
$dirControllers = __DIR__ . $ds . 'app' . $ds . 'controllers' . $ds;

switch ($controller) {
    case 'auth':
        require_once $dirControllers . 'usuariosController.php';
        $authController = new usuariosController();
        if ($action === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            } else {
                require_once __DIR__ . $ds . 'app' . $ds . 'Views' . $ds . 'auth' . $ds . 'login.php';
            }
        } elseif ($action === 'logout') {
            $authController->logout();
        }
        break;

    case 'dashboard':
        exigirAutenticacao();
        if ($action === 'index') {
            require_once __DIR__ . $ds . 'app' . $ds . 'Views' . $ds . 'dashboard' . $ds . 'index.php';
        } elseif ($action === 'resumo') {
            header('Content-Type: application/json');
            echo json_encode([
                'indicadores' => [
                    'total_pessoas' => 0,
                    'total_tipos' => 0,
                    'total_atendimentos' => 0
                ]
            ]);
            exit;
        }
        break;

    case 'pessoas':
        exigirAutenticacao();
        require_once $dirControllers . 'pessoasController.php';
        $pessoasController = new pessoasController();
        if ($action === 'listar') $pessoasController->listar();
        if ($action === 'buscarPorId' || $action === 'buscar') $pessoasController->buscarPorId();
        if ($action === 'cadastrar' || $action === 'criar') $pessoasController->cadastrar();
        if ($action === 'atualizar') $pessoasController->atualizar();
        if ($action === 'inativar') $pessoasController->inativar();
        break;

    case 'tipos':
    case 'tipos_atendimentos':
        exigirAutenticacao();
        require_once $dirControllers . 'TiposAtendimentosController.php';
        $taController = new TiposAtendimentosController();
        if ($action === 'listar') $taController->listar();
        if ($action === 'buscarPorId' || $action === 'buscar') $taController->buscarPorId();
        if ($action === 'cadastrar' || $action === 'criar') $taController->cadastrar();
        if ($action === 'atualizar') $taController->atualizar();
        if ($action === 'inativar') $taController->inativar();
        break;

    case 'atendimentos':
        exigirAutenticacao();
        require_once $dirControllers . 'atendimentosController.php';
        $atendimentosController = new atendimentosController();
        if ($action === 'listar') $atendimentosController->listar();
        if ($action === 'buscarPorId' || $action === 'visualizar') $atendimentosController->buscarPorId();
        if ($action === 'cadastrar' || $action === 'criar') $atendimentosController->cadastrar();
        if ($action === 'iniciar' || $action === 'alterarStatus' || $action === 'atualizarStatus') $atendimentosController->iniciar();
        if ($action === 'concluir') $atendimentosController->concluir();
        break;

    default:
        header('Content-Type: application/json');
        echo json_encode(['erro' => "Rota ou controlador '$controller' não encontrado."]);
        break;
}