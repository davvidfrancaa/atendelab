<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carrega as configurações de banco globais
require_once __DIR__ . '/../config/database.php';

// Redireciona o fluxo para o arquivo de gerenciamento de rotas
require_once __DIR__ . '/../routes.php';