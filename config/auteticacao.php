<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// caso não existir a sessão do usuário ou ele não estiver ativo 
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_status'] !== 'ativo') {
    // exclui a sessão por segurança e manda de volta para o login
    session_unset();
    session_destroy();
    header("Location: /atendelab_/login.php?erro=autenticacao");
    exit();
}
?>