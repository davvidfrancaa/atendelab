<?php
class PessoasController {
    private PDO $pdo;

    public function __construct() {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar() {
        require __DIR__ . '/../Views/pessoas/listar.php';
    }

    public function listar_api() {
        header('Content-Type: application/json; charset=utf-8');
        $stmt = $this->pdo->query('SELECT id, nome, email, telefone FROM pessoas ORDER BY nome ASC');
        $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($pessoas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function criar() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');

            if (empty($nome) || empty($email)) {
                echo json_encode(['erro' => 'Nome e E-mail são obrigatórios.']);
                exit();
            }

            $stmt = $this->pdo->prepare('INSERT INTO pessoas (nome, email, telefone) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $email, $telefone]);
            echo json_encode(['sucesso' => 'Pessoa cadastrada com sucesso.']);
            exit();
        }
    }

    public function excluir() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $stmt = $this->pdo->prepare('DELETE FROM pessoas WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['sucesso' => 'Pessoa excluída com sucesso.']);
            exit();
        }
    }
}