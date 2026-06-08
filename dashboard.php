<?php
// para protecao da pagina só entra quem estiver logado e ativo conforme RN01, RN09 e RF04
require_once __DIR__ . '/config/autenticacao.php';
require_once __DIR__ . '/config/database.php';

// RF13: busca indicadores rápidos no banco de dados 
try {
    // total de pessoas atendidas ativdas RN11)
    $stmtPessoas = $pdo->query("SELECT COUNT(*) FROM pessoas WHERE status = 'ativo'");
    $totalPessoas = $stmtPessoas->fetchColumn();

    // total de andamento em aberto RN05
    $stmtAtendimentos = $pdo->query("SELECT COUNT(*) FROM atendimentos WHERE status IN ('aberto', 'em_andamento')");
    $atendimentosAtivos = $stmtAtendimentos->fetchColumn();
} catch (PDOException $e) {
    // se as tabelas ainda não existirem, define como zero para não quebrar a tela
    $totalPessoas = 0;
    $atendimentosAtivos = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Atendelab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Atendelab</a>
            <button class="navbar-toggler" type="text/center" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pessoas/index.php">Pessoas Atendidas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tipos/index.php">Tipos de Atendimento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="atendimentos/index.php">Atendimentos</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center text-white">
                    <span class="me-3">Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong></span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair (Logout)</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="h3 mb-0 text-gray-800">Painel Geral (Dashboard)</h2>
                <p class="text-muted">Bem-vindo ao sistema de gerenciamento de atendimentos.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-start border-primary border-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pessoas Cadastradas</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $totalPessoas ?></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-start border-warning border-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Atendimentos Ativos</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $atendimentosAtivos ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>