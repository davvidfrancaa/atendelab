<?php
$host = "localhost";
$usuario = "root";
$senha = ""; // O XAMPP por padrão vem sem senha
$banco = "atendelab";

try {
    // Cria a conexão usando PDO (mais seguro)
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    // Ativa o modo de erros para te avisar se algo der errado
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexão realizada com sucesso!"; 
} catch (PDOException $erro) {
    die("Erro ao conectar com o banco de dados: " . $erro->getMessage());
}
?>