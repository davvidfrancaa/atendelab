<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/atendelab_');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - ATENDELAB</title>
</head>
<body>

    <h1>Painel do Dashboard</h1>
    <p>Bem-vindo ao sistema ATENDELAB!</p>
    
    <p><a href="<?= BASE_URL ?>/public/index.php?controller=auth&action=logout">Sair do Sistema</a></p>

</body>
</html>