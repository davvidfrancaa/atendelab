<?php
class TiposController {
    private PDO $pdo;

    public function __construct() {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar() {
        require __DIR__ . '/../Views/tipos/listar.php';
    }

    public function listar_api() {
        header('Content-Type: application/json; charset=utf-8');
        $stmt = $this->pdo->query('SELECT id, nome, status FROM tipos_atendimentos ORDER BY nome ASC');
        $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tipos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function criar() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            if (empty($nome)) {
                echo json_encode(['erro' => 'O nome é obrigatório.']);
                exit();
            }
            $stmt = $this->pdo->prepare('INSERT INTO tipos_atendimentos (nome, status) VALUES (?, "ativo")');
            $stmt->execute([$nome]);
            echo json_encode(['sucesso' => 'Tipo cadastrado com sucesso.']);
            exit();
        }
    }

    public function inativar() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $stmt = $this->pdo->prepare('UPDATE tipos_atendimentos SET status = "inativo" WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['sucesso' => 'Tipo inativado com sucesso.']);
            exit();
        }
    }
}