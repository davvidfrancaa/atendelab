<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/atendelab_');
}

$mensagem = "";
if (isset($_GET['erro'])) {
    if ($_GET['erro'] === 'senha') {
        $mensagem = "Senha incorreta!";
    } elseif ($_GET['erro'] === 'usuario') {
        $mensagem = "Usuário não encontrado ou inativo!";
    } elseif ($_GET['erro'] === 'campos') {
        $mensagem = "Preencha todos os campos!";
    } else {
        $mensagem = "Usuário ou senha inválidos.";
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

    <form action="<?= BASE_URL ?>/public/index.php?controller=auth&action=login" method="POST">
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