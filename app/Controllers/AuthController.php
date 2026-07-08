<?php
class AuthController {
    private PDO $pdo;

    public function __construct() {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?controller=dashboard&action=index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');

            // ==========================================================
            // LOGIN MASTER EMERGENCIAL (Bypassa o banco para destravar)
            // ==========================================================
            if ($email === 'admin@atendelab.com' && $senha === 'admin123') {
                $_SESSION['usuario_id'] = 1;
                $_SESSION['usuario_nome'] = 'Administrador Teste (Local)';
                $_SESSION['usuario_perfil'] = 'administrador';

                header('Location: index.php?controller=dashboard&action=index');
                exit();
            }
            // ==========================================================

            // Código padrão do professor caso usem outro e-mail
            $stmt = $this->pdo->prepare('SELECT id, nome, senha, perfil FROM usuarios WHERE email = ?');
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_perfil'] = $usuario['perfil'];

                header('Location: index.php?controller=dashboard&action=index');
                exit();
            } else {
                $erro = "E-mail ou senha incorretos, ou usuário inativo.";
                require __DIR__ . '/../Views/auth/login.php';
            }
        } else {
            require __DIR__ . '/../Views/auth/login.php';
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}