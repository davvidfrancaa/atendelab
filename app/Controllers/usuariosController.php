<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Usuario.php';

class usuariosController {
    private PDO $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                header('Location: /atendelab_/public/index.php?controller=auth&action=login&erro=campos');
                exit;
            }

            try {
                $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND status = 'Ativo' LIMIT 1");
                $stmt->execute([':email' => $email]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    if (md5($senha) === $usuario['senha']) {
                        $_SESSION['usuario'] = [
                            'id' => $usuario['id'],
                            'nome' => $usuario['nome'],
                            'email' => $usuario['email']
                        ];
                        header('Location: /atendelab_/public/index.php?controller=dashboard&action=index');
                        exit;
                    } else {
                        header('Location: /atendelab_/public/index.php?controller=auth&action=login&erro=senha');
                        exit;
                    }
                } else {
                    header('Location: /atendelab_/public/index.php?controller=auth&action=login&erro=usuario');
                    exit;
                }
            } catch (PDOException $e) {
                die("Erro no banco: " . $e->getMessage());
            }
        }
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        header('Location: /atendelab_/public/index.php?controller=auth&action=login');
        exit;
    }

    public function listar(): void {
        header("Content-Type: application/json; charset=utf-8");
        try {
            $sql = "SELECT id, nome, email, perfil, status, criado_em FROM usuarios ORDER BY id DESC";
            $stmt = $this->pdo->query($sql);
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao listar: ' . $e->getMessage()]);
        }
    }

    public function buscarPorId(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id) {
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        $sql = "SELECT id, nome, email, perfil, status, criado_em FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($usuario ?: ['erro' => 'Usuário não encontrado.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void {
        header("Content-Type: application/json; charset=utf-8");
        $nome   = $_POST['nome'] ?? '';
        $email  = $_POST['email'] ?? '';
        $senha  = $_POST['senha'] ?? '';
        $perfil = $_POST['perfil'] ?? 'Atendente';
        $status = $_POST['status'] ?? 'Ativo';

        if (empty($nome) || empty($email) || empty($senha)) {
            echo json_encode(['erro' => 'Dados incompletos.']);
            return;
        }

        try {
            $sql = "INSERT INTO usuarios (nome, email, senha, perfil, status) VALUES (:nome, :email, :senha, :perfil, :status)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha, ':perfil' => $perfil, ':status' => $status]);
            
            echo json_encode(['mensagem' => 'Usuário criado com sucesso!', 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao inserir: ' . $e->getMessage()]);
        }
    }

    public function atualizar(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id     = $_POST['id'] ?? '';
        $nome   = $_POST['nome'] ?? '';
        $email  = $_POST['email'] ?? '';
        
        try {
            $sql = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':email' => $email, ':id' => $id]);
            echo json_encode(['mensagem' => 'Usuário atualizado com sucesso.']);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao atualizar.']);
        }
    }

    public function excluir(): void {
        header("Content-Type: application/json; charset=utf-8");
        $id = $_POST['id'] ?? $_GET['id'] ?? '';

        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            echo json_encode(['mensagem' => 'Usuário excluído com sucesso.']);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao excluir.']);
        }
    }
}