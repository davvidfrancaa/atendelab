<?php
class AtendimentosController {
    private PDO $pdo;

    public function __construct() {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar() {
        require __DIR__ . '/../Views/atendimentos/listar.php';
    }

    public function listar_api() {
        header('Content-Type: application/json; charset=utf-8');
        $sql = 'SELECT a.id, a.descricao, a.criado_em, p.nome AS pessoa_nome, t.nome AS tipo_nome 
                FROM atendimentos a 
                INNER JOIN pessoas p ON a.pessoa_id = p.id 
                INNER JOIN tipos_atendimentos t ON a.tipo_id = t.id 
                ORDER BY a.criado_em DESC';
        $stmt = $this->pdo->query($sql);
        $atendimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($atendimentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function criar() {
        header('Content-Type: application/json; charset=utf-8');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pessoa_id = $_POST['pessoa_id'] ?? '';
            $tipo_id = $_POST['tipo_id'] ?? '';
            $descricao = trim($_POST['descricao'] ?? '');
            $usuario_id = $_SESSION['usuario_id'] ?? 1;

            if (empty($pessoa_id) || empty($tipo_id) || empty($descricao)) {
                echo json_encode(['erro' => 'Todos os campos são obrigatórios.']);
                exit();
            }

            $stmt = $this->pdo->prepare('INSERT INTO atendimentos (pessoa_id, tipo_id, usuario_id, descricao, criado_em) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute([$pessoa_id, $tipo_id, $usuario_id, $descricao]);
            echo json_encode(['sucesso' => 'Atendimento registrado com sucesso.']);
            exit();
        }
    }
}