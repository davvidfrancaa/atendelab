<?php
// Função simples para verificar se o usuário está logado
function verificarAutenticacao() {
    // Se a sessão não estiver ativa, inicia
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Se não existir a variável de sessão do usuário, redireciona para o login
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}