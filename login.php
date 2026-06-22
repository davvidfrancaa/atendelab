<?php
session_start();

// Em vez de chamar o index.php inteiro, criamos a conexão direta aqui para evitar conflito de rotas
$host = 'localhost';
$db   = 'atendelab';
$user = 'root';
$pass = ''; // Se o seu MySQL tiver senha no XAMPP, coloque ela aqui dentro das aspas
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro ao conectar no banco de dados: " . $e->getMessage());
}

$mensagem = "";

// Verifica se o usuário clicou no botão "Entrar"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        try {
            // Busca o usuário pelo e-mail
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND status = 'Ativo' LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario) {
                // Compara a senha convertendo para MD5
                if (md5($senha) === $usuario['senha']) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];

                    // Login com sucesso! Vai para o esqueleto do dashboard
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $mensagem = "Senha incorreta!";
                }
            } else {
                $mensagem = "Usuário não encontrado ou inativo!";
            }
        } catch (PDOException $e) {
            $mensagem = "Erro no banco: " . $e->getMessage();
        }
    } else {
        $mensagem = "Preencha todos os campos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - ATENDELAB</title>
</head>
<body>

    <h1>Acessar o Sistema (Login)</h1>

    <?php if (!empty($mensagem)): ?>
        <p style="color: red;"><strong>⚠️ <?= $mensagem ?></strong></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div>
            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" required placeholder="admin@atendelab.com">
        </div>
        <br>
        <div>
            <label for="senha">Senha:</label><br>
            <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
        </div>
        <br>
        <button type="submit">Entrar</button>
    </form>

</body>
</html>