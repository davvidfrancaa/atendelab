<?php
session_start();

// Conexão direta com o banco para o esqueleto rodar de forma isolada e limpa
$host = 'localhost';
$db   = 'atendelab';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Busca os dados direto das tabelas para o esqueleto HTML
    $atendimentos = $pdo->query("SELECT * FROM atendimentos ORDER BY id DESC")->fetchAll();
    $pessoas = $pdo->query("SELECT * FROM pessoas ORDER BY id DESC")->fetchAll();
    $tipos = $pdo->query("SELECT * FROM tipo_atendimentos ORDER BY id DESC")->fetchAll();
} catch (\PDOException $e) {
    die("Erro ao carregar os dados do banco: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Esqueleto ATENDELAB</title>
</head>
<body>

    <h1>Esqueleto do Sistema - Dados do Banco</h1>
    <p>Olá, <strong><?= $_SESSION['usuario_nome'] ?? 'Usuário Autenticado' ?></strong>! Você está logado no sistema.</p>
    <p><a href="login.php">🚪 Sair / Voltar para o Login</a></p>

    <hr>

    <h2>Tabela: Atendimentos</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr style="background-color: #eee;">
                <th>ID</th>
                <th>Pessoa ID</th>
                <th>Tipo ID</th>
                <th>Status</th>
                <th>Observação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($atendimentos)): ?>
                <tr><td colspan="5">Nenhum atendimento registrado.</td></tr>
            <?php else: ?>
                <?php foreach ($atendimentos as $at): ?>
                <tr>
                    <td><strong><?= $at['id'] ?></strong></td>
                    <td><?= $at['pessoa_id'] ?></td>
                    <td><?= $at['tipo_atendimento'] ?></td>
                    <td><?= $at['status'] ?></td>
                    <td><?= $at['observacao'] ?? '-' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Tabela: Pessoas</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr style="background-color: #eee;">
                <th>ID</th>
                <th>Nome</th>
                <th>Documento</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pessoas)): ?>
                <tr><td colspan="4">Nenhuma pessoa registrada.</td></tr>
            <?php else: ?>
                <?php foreach ($pessoas as $p): ?>
                <tr>
                    <td><strong><?= $p['id'] ?></strong></td>
                    <td><?= $p['nome'] ?></td>
                    <td><?= $p['documento'] ?></td>
                    <td><?= $p['status'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Tabela: Tipos de Atendimento</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr style="background-color: #eee;">
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tipos)): ?>
                <tr><td colspan="3">Nenhum tipo cadastrado.</td></tr>
            <?php else: ?>
                <?php foreach ($tipos as $t): ?>
                <tr>
                    <td><strong><?= $t['id'] ?></strong></td>
                    <td><?= $t['nome'] ?></td>
                    <td><?= $t['descricao'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>